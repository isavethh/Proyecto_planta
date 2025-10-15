<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Http\Request;

/**
 * Controlador para gestión de envíos
 */
class EnvioController extends Controller
{
    /**
     * Muestra la lista de envíos (dashboard principal)
     */
    public function index()
    {
        // Si es admin, mostrar todos los envíos
        if (session('user_role') === 'admin') {
            return redirect()->route('admin.envios');
        }

        // Para usuarios regulares, mostrar sus propios envíos
        $envios = Envio::where('cliente_id', session('user_id'))
                      ->orderBy('fecha_creacion', 'desc')
                      ->get();

        return view('envios.dashboard', compact('envios'));
    }

    /**
     * Muestra el formulario para crear un nuevo envío
     */
    public function create()
    {
        return view('envios.create');
    }

    /**
     * Guarda un nuevo envío en la base de datos
     */
    public function store(Request $request)
    {
        // Validación dual: aceptar esquema antiguo o nuevo con items[]
        $hasItems = is_array($request->input('items')) && count($request->input('items')) > 0;
        if ($hasItems) {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.categoria_producto' => 'required|in:' . implode(',', Envio::CATEGORIAS_PRODUCTO),
                'items.*.producto' => 'required|string|max:255',
                'items.*.peso_producto_unidad' => 'required|numeric|min:0',
                'items.*.unidades_totales' => 'required|integer|min:1',
                'items.*.precio_producto' => 'required|numeric|min:0',
                'transporte_seleccionado' => 'required|in:' . implode(',', array_keys(Envio::TRANSPORTES_DISPONIBLES)),
                'destino_direccion' => 'nullable|string|max:255',
                'destino_lat' => 'nullable|numeric',
                'destino_lng' => 'nullable|numeric',
                'distancia_km' => 'nullable|numeric',
                'fecha_entrega_deseada' => 'nullable|date'
            ]);
        } else {
            $request->validate([
                'categoria_producto' => 'required|in:' . implode(',', Envio::CATEGORIAS_PRODUCTO),
                'producto' => 'required|string|max:255',
                'peso_producto_unidad' => 'required|numeric|min:0',
                'unidades_totales' => 'required|integer|min:1',
                'transporte_seleccionado' => 'required|in:' . implode(',', array_keys(Envio::TRANSPORTES_DISPONIBLES)),
                'precio_producto' => 'required|numeric|min:0',
                'destino_direccion' => 'nullable|string|max:255',
                'destino_lat' => 'nullable|numeric',
                'destino_lng' => 'nullable|numeric',
                'distancia_km' => 'nullable|numeric',
                'fecha_entrega_deseada' => 'nullable|date'
            ]);
        }

        // Dirección desde planta hardcodeada
        $direccionPlanta = 'Planta Central - Calle Principal 123, Ciudad Industrial';

        // Calcular sugerencia de transporte en base al total del pedido
        if ($hasItems) {
            $pesoTotal = 0.0;
            $categoriaPrioritaria = null;
            foreach ($request->items as $it) {
                $pesoTotal += ((float)$it['peso_producto_unidad']) * ((int)$it['unidades_totales']);
                // Dar prioridad a alimentos/medicinas si existen
                if (in_array($it['categoria_producto'], ['alimentos','medicinas'])) {
                    $categoriaPrioritaria = $it['categoria_producto'];
                }
            }
            $baseCategoria = $categoriaPrioritaria ?? ($request->items[0]['categoria_producto'] ?? 'otros');
            $transporteSugerido = $this->sugerirTransporte($baseCategoria, $pesoTotal);
        } else {
            $transporteSugerido = $this->sugerirTransporte(
                $request->categoria_producto,
                $request->peso_producto_unidad * $request->unidades_totales
            );
        }

        // Crear el envío (soporte para items múltiples y compatibilidad con esquema simple)
        if ($hasItems) {
            // Generar agregados mínimos para compatibilidad legada
            $primer = $request->items[0];
            $envio = Envio::create([
                'direccion_desde_planta' => $direccionPlanta,
                'destino_direccion' => $request->destino_direccion,
                'destino_lat' => $request->destino_lat,
                'destino_lng' => $request->destino_lng,
                'distancia_km' => $request->distancia_km,
                'categoria_producto' => $primer['categoria_producto'],
                'producto' => $primer['producto'],
                'peso_producto_unidad' => $primer['peso_producto_unidad'],
                'unidades_totales' => $primer['unidades_totales'],
                'transporte_sugerido' => $transporteSugerido,
                'transporte_seleccionado' => $request->transporte_seleccionado,
                'estado' => Envio::ESTADO_PENDIENTE,
                'precio_producto' => $primer['precio_producto'],
                'items' => array_values($request->items),
                'fecha_entrega_deseada' => $request->fecha_entrega_deseada,
                'cliente_id' => session('user_id')
            ]);
        } else {
            Envio::create([
                'direccion_desde_planta' => $direccionPlanta,
                'destino_direccion' => $request->destino_direccion,
                'destino_lat' => $request->destino_lat,
                'destino_lng' => $request->destino_lng,
                'distancia_km' => $request->distancia_km,
                'categoria_producto' => $request->categoria_producto,
                'producto' => $request->producto,
                'peso_producto_unidad' => $request->peso_producto_unidad,
                'unidades_totales' => $request->unidades_totales,
                'transporte_sugerido' => $transporteSugerido,
                'transporte_seleccionado' => $request->transporte_seleccionado,
                'estado' => Envio::ESTADO_PENDIENTE,
                'precio_producto' => $request->precio_producto,
                'fecha_entrega_deseada' => $request->fecha_entrega_deseada,
                'cliente_id' => session('user_id')
            ]);
        }

        return redirect()->route('envios.mis')->with('success', 'Envío creado exitosamente');
    }

    /**
     * Muestra un envío específico
     */
    public function show(Envio $envio)
    {
        // Verificar que el usuario solo pueda ver sus propios envíos
        if ($envio->cliente_id !== session('user_id') && session('user_role') !== 'admin') {
            abort(403);
        }

        return view('envios.show', compact('envio'));
    }

    /**
     * Muestra el formulario para editar un envío
     */
    public function edit(Envio $envio)
    {
        // Verificar permisos
        if ($envio->cliente_id !== session('user_id') && session('user_role') !== 'admin') {
            abort(403);
        }

        // Solo permitir editar si está pendiente
        if (!$envio->isPendiente()) {
            return back()->with('error', 'Solo se pueden editar envíos pendientes');
        }

        return view('envios.edit', compact('envio'));
    }

    /**
     * Actualiza un envío
     */
    public function update(Request $request, Envio $envio)
    {
        // Verificar permisos
        if ($envio->cliente_id !== session('user_id') && session('user_role') !== 'admin') {
            abort(403);
        }

        // Solo permitir editar si está pendiente
        if (!$envio->isPendiente()) {
            return back()->with('error', 'Solo se pueden editar envíos pendientes');
        }

        $request->validate([
            'categoria_producto' => 'required|in:' . implode(',', Envio::CATEGORIAS_PRODUCTO),
            'producto' => 'required|string|max:255',
            'peso_producto_unidad' => 'required|numeric|min:0',
            'unidades_totales' => 'required|integer|min:1',
            'transporte_seleccionado' => 'required|in:' . implode(',', array_keys(Envio::TRANSPORTES_DISPONIBLES)),
            'precio_producto' => 'required|numeric|min:0'
        ]);

        // Sugerir transporte basado en categoría y peso
        $transporteSugerido = $this->sugerirTransporte(
            $request->categoria_producto,
            $request->peso_producto_unidad * $request->unidades_totales
        );

        $envio->update([
            'categoria_producto' => $request->categoria_producto,
            'producto' => $request->producto,
            'peso_producto_unidad' => $request->peso_producto_unidad,
            'unidades_totales' => $request->unidades_totales,
            'transporte_sugerido' => $transporteSugerido,
            'transporte_seleccionado' => $request->transporte_seleccionado,
            'precio_producto' => $request->precio_producto
        ]);

        return redirect()->route('envios.mis')->with('success', 'Envío actualizado exitosamente');
    }

    /**
     * Elimina un envío
     */
    public function destroy(Envio $envio)
    {
        // Verificar permisos
        if ($envio->cliente_id !== session('user_id') && session('user_role') !== 'admin') {
            abort(403);
        }

        // Solo permitir eliminar si está pendiente
        if (!$envio->isPendiente()) {
            return back()->with('error', 'Solo se pueden eliminar envíos pendientes');
        }

        $envio->delete();

        return redirect()->route('envios.mis')->with('success', 'Envío eliminado exitosamente');
    }

    /**
     * Muestra los envíos del usuario actual
     */
    public function misEnvios()
    {
        $envios = Envio::where('cliente_id', session('user_id'))
                      ->orderBy('fecha_creacion', 'desc')
                      ->get();

        return view('envios.mis-envios', compact('envios'));
    }

    /**
     * Muestra la página de reglamento
     */
    public function reglamento()
    {
        return view('envios.reglamento');
    }

    /**
     * Muestra el documento del pedido
     */
    public function documentoPedido(Envio $envio)
    {
        // Verificar que el usuario solo pueda ver sus propios envíos
        if ($envio->cliente_id !== session('user_id') && session('user_role') !== 'admin') {
            abort(403);
        }

        return view('envios.documento-pedido', compact('envio'));
    }

    /**
     * Lista documentos del usuario autenticado (pendientes y confirmados)
     */
    public function misDocumentos()
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        $envios = Envio::where('cliente_id', session('user_id'))
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        return view('envios.mis-documentos', compact('envios'));
    }

    /**
     * Vista de administración para gestionar envíos (solo admin)
     */
    public function adminIndex()
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        $query = Envio::with(['transportista', 'vehiculo'])
            ->orderBy('fecha_creacion', 'desc');
        $estado = request('estado');
        if ($estado === Envio::ESTADO_PENDIENTE || $estado === Envio::ESTADO_CONFIRMADO) {
            $query->where('estado', $estado);
        }
        $envios = $query->get();

        // Conteos por estado para dashboard admin (independientes del filtro aplicado)
        $conteos = [
            'total' => Envio::count(),
            'pendientes' => Envio::where('estado', Envio::ESTADO_PENDIENTE)->count(),
            'confirmados' => Envio::where('estado', Envio::ESTADO_CONFIRMADO)->count(),
        ];

        $transportistas = \App\Models\Transportista::all();
        $vehiculos = \App\Models\Vehiculo::all();

        // Hardcode de fallback si no existen registros
        if ($transportistas->isEmpty()) {
            $transportistas = collect([
                (object)['id' => 1, 'nombre' => 'Juan Pérez'],
                (object)['id' => 2, 'nombre' => 'María García'],
                (object)['id' => 3, 'nombre' => 'Luis Fernández'],
            ]);
        }
        if ($vehiculos->isEmpty()) {
            $vehiculos = collect([
                (object)['id' => 1, 'placa' => 'ABC-123'],
                (object)['id' => 2, 'placa' => 'XYZ-789'],
                (object)['id' => 3, 'placa' => 'JKL-456'],
            ]);
        }

        return view('envios.admin', compact('envios', 'transportistas', 'vehiculos', 'conteos'));
    }

    /**
     * Vista de detalle de un envío para admin: muestra todos los productos y permite confirmar.
     */
    public function adminShow(Envio $envio)
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        $transportistas = \App\Models\Transportista::all();
        $vehiculos = \App\Models\Vehiculo::all();

        if ($transportistas->isEmpty()) {
            $transportistas = collect([
                (object)['id' => 1, 'nombre' => 'Juan Pérez'],
                (object)['id' => 2, 'nombre' => 'María García'],
                (object)['id' => 3, 'nombre' => 'Luis Fernández'],
            ]);
        }
        if ($vehiculos->isEmpty()) {
            $vehiculos = collect([
                (object)['id' => 1, 'placa' => 'ABC-123'],
                (object)['id' => 2, 'placa' => 'XYZ-789'],
                (object)['id' => 3, 'placa' => 'JKL-456'],
            ]);
        }

        return view('envios.admin-envio-show', compact('envio', 'transportistas', 'vehiculos'));
    }

    /**
     * Asigna transporte y conductor a un envío (solo admin)
     */
    public function asignarTransporte(Request $request, Envio $envio)
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        // Validación relajada: permitir confirmar sin selección explícita
        $request->validate([
            'transportista_id' => 'nullable|integer',
            'vehiculo_id' => 'nullable|integer'
        ]);

        // Resolver/crear placeholders si faltan datos o no existen
        $transportistaId = $request->transportista_id;
        $vehiculoId = $request->vehiculo_id;

        if (!$transportistaId || !\App\Models\Transportista::where('id', $transportistaId)->exists()) {
            $placeholderT = \App\Models\Transportista::first();
            if (!$placeholderT) {
                $placeholderT = \App\Models\Transportista::create([
                    'nombre' => 'Transportista Asignado',
                    'telefono' => 'N/A',
                    'licencia' => 'N/A',
                    'empresa' => 'N/A',
                    'activo' => true,
                ]);
            }
            $transportistaId = $placeholderT->id;
        }

        if (!$vehiculoId || !\App\Models\Vehiculo::where('id', $vehiculoId)->exists()) {
            $placeholderV = \App\Models\Vehiculo::first();
            if (!$placeholderV) {
                $placeholderV = \App\Models\Vehiculo::create([
                    'transportista_id' => $transportistaId,
                    'placa' => 'PLACA-000',
                    'tipo' => 'camion',
                    'capacidad_kg' => 1000,
                    'activo' => true,
                ]);
            }
            $vehiculoId = $placeholderV->id;
        }

        $envio->update([
            'transportista_id' => $transportistaId,
            'vehiculo_id' => $vehiculoId,
            'estado' => Envio::ESTADO_CONFIRMADO,
            'fecha_confirmacion' => now()
        ]);

        return redirect()->route('admin.envios')->with('success', 'Envío confirmado exitosamente');
    }

    /**
     * Lista usuarios para administración (simplificado: obtiene IDs únicos en envíos y el usuario hardcodeado)
     */
    public function adminUsuarios()
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        // Usuarios desde tabla usuarios si existen, si no, armar desde envíos y usuario hardcodeado
        $usuarios = \App\Models\Usuario::select('id', 'nombre', 'email')
            ->orderBy('id', 'asc')
            ->get();

        if ($usuarios->isEmpty()) {
            $ids = Envio::select('cliente_id')->distinct()->pluck('cliente_id');
            $usuarios = $ids->map(function ($id) {
                return (object) ['id' => (string) $id, 'nombre' => 'Usuario', 'email' => 'usuario@example.com'];
            });
        }

        return view('envios.admin-usuarios', compact('usuarios'));
    }

    /**
     * Muestra envíos de un usuario específico para admin
     */
    public function adminEnviosDeUsuario(string $clienteId)
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        $envios = Envio::where('cliente_id', $clienteId)
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $usuario = null;
        if (is_numeric($clienteId)) {
            $usuario = \App\Models\Usuario::where('id', (int) $clienteId)->first();
        }
        if (!$usuario) {
            $usuario = (object) [
                'id' => $clienteId,
                'nombre' => is_string($clienteId) ? ucfirst((string) $clienteId) : 'Usuario',
            ];
        }

        return view('envios.admin-usuario-envios', compact('envios', 'usuario'));
    }

    /**
     * Lista documentos (solo de envíos confirmados) para admin
     */
    public function adminDocumentos()
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }

        $envios = Envio::with(['transportista','vehiculo'])
            ->where('estado', Envio::ESTADO_CONFIRMADO)
            ->orderBy('fecha_confirmacion', 'desc')
            ->get();

        return view('envios.admin-documentos', compact('envios'));
    }

    /**
     * Sugiere el tipo de transporte basado en categoría y peso total
     */
    private function sugerirTransporte($categoria, $pesoTotal)
    {
        // Lógica simple de sugerencia de transporte
        if ($categoria === 'alimentos' || $categoria === 'medicinas') {
            return 'camion_refrigerado';
        }

        if ($pesoTotal > 1000) { // Más de 1 tonelada
            return 'camion_grande';
        } elseif ($pesoTotal > 500) { // Entre 500kg y 1 tonelada
            return 'camion_mediano';
        } else { // Menos de 500kg
            return 'camion_pequeno';
        }
    }
}

