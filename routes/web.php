<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnvioController;

// Ruta raíz - siempre lleva al login
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Registro ligero que crea sesión de usuario
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Compatibilidad: permitir GET /logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// Rutas principales con backend ligero
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        if (!session('user_id')) {
            return redirect('/login');
        }
        if (session('user_role') === 'admin') {
            return redirect()->route('admin.envios');
        }
        return view('dashboard');
    })->name('dashboard');

    Route::get('/envios/create', [EnvioController::class, 'create'])->name('envios.create');
    Route::post('/envios', [EnvioController::class, 'store'])->name('envios.store');

    Route::get('/mis-envios', [EnvioController::class, 'misEnvios'])->name('envios.mis');

    Route::get('/reglamento', [EnvioController::class, 'reglamento'])->name('envios.reglamento');

    Route::get('/documento-pedido/{envio}', [EnvioController::class, 'documentoPedido'])->name('envios.documento');
    // Ruta alternativa sin parámetro para abrir el documento usando localStorage/envioActual
    Route::get('/documento-pedido', function(){ return view('envios.documento-pedido'); })->name('envios.documento.blank');

    Route::get('/admin/envios', [EnvioController::class, 'adminIndex'])->middleware('role:admin')->name('admin.envios');
    Route::post('/admin/envios/{envio}/asignar-transporte', [EnvioController::class, 'asignarTransporte'])->middleware('role:admin')->name('admin.asignar-transporte');
    // Administración de usuarios (listado y envíos por usuario)
    Route::get('/admin/usuarios', [EnvioController::class, 'adminUsuarios'])->middleware('role:admin')->name('admin.usuarios');
    Route::get('/admin/usuarios/{clienteId}/envios', [EnvioController::class, 'adminEnviosDeUsuario'])->middleware('role:admin')->name('admin.usuarios.envios');
    // Documentos de envíos confirmados (admin)
    Route::get('/admin/documentos', [EnvioController::class, 'adminDocumentos'])->middleware('role:admin')->name('admin.documentos');
});
