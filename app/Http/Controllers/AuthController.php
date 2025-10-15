<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador personalizado de autenticación con cuentas hardcodeadas
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de login
     */
    public function showLogin()
    {
        // Verificar si ya hay una sesión activa
        if (session('user_id')) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el login con cuentas hardcodeadas
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Cuentas hardcodeadas
        $users = [
            [
                'email' => 'admin',
                'password' => 'admin1',
                'name' => 'Administrador',
                'role' => 'admin'
            ],
            [
                'email' => 'usuario',
                'password' => 'usuario1',
                'name' => 'Usuario Regular',
                'role' => 'user'
            ]
        ];

        foreach ($users as $user) {
            if ($user['email'] === $credentials['email'] &&
                $credentials['password'] === $user['password']) {

                // Crear sesión con datos del usuario
                session([
                    'user_id' => $user['email'],
                    'user_name' => $user['name'],
                    'user_role' => $user['role']
                ]);

                return redirect('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput();
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout()
    {
        session()->forget(['user_id', 'user_name', 'user_role']);

        return redirect('/login');
    }
}

