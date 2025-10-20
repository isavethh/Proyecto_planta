@extends('admin')

@section('title', 'Mis Envíos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mis Envíos</h3>
                    <div class="card-tools">
                        <a href="{{ route('envios.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>Nuevo Envío
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-body text-center">
                    @php
                        $collection = isset($envios) ? $envios : collect();
                    @endphp
                    @if($collection->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-truck fa-4x text-muted mb-3"></i>
                            <h4>No tienes envíos registrados</h4>
                            <p class="text-muted">Crea tu primer envío para comenzar</p>
                            <a href="{{ route('envios.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Crear Primer Envío
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($collection as $e)
                                @php
                                    // Totales considerando items si existen
                                    $items = is_array($e->items ?? null) ? $e->items : [];
                                    if (count($items) > 0) {
                                        $pesoTotal = 0; $precioTotal = 0; $unidadesTotal = 0;
                                        foreach ($items as $it) {
                                            $unidades = (int)($it['unidades_totales'] ?? 0);
                                            $pesoTotal += (float)($it['peso_producto_unidad'] ?? 0) * $unidades;
                                            $precioTotal += (float)($it['precio_producto'] ?? 0) * $unidades;
                                            $unidadesTotal += $unidades;
                                        }
                                    } else {
                                        $pesoTotal = (float)($e->peso_producto_unidad ?? 0) * (int)($e->unidades_totales ?? 0);
                                        $precioTotal = (float)($e->precio_producto ?? 0) * (int)($e->unidades_totales ?? 0);
                                        $unidadesTotal = (int)($e->unidades_totales ?? 0);
                                    }
                                    $estadoClass = $e->estado === 'confirmado' ? 'success' : ($e->estado === 'pendiente' ? 'warning' : 'info');
                                    $map = \App\Models\Envio::TRANSPORTES_DISPONIBLES;
                                    $transKey = $e->transporte_seleccionado;
                                    $transLabel = isset($map[$transKey]) ? $map[$transKey] : ($e->transporte_sugerido ?? 'No asignado');
                                    $fechaCreacionRaw = $e->created_at ?? $e->fecha_creacion ?? null;
                                    $fechaCreacion = $fechaCreacionRaw ? \Illuminate\Support\Carbon::parse($fechaCreacionRaw)->format('d/m/Y H:i') : '-';
                                    $fechaEntrega = $e->fecha_entrega_deseada ? \Illuminate\Support\Carbon::parse($e->fecha_entrega_deseada)->format('d/m/Y H:i') : '-';
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card card-{{ $estadoClass }} card-outline text-center">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                Envío #{{ $e->id }}
                                                <span class="badge badge-{{ $estadoClass }} ml-2">{{ ucfirst($e->estado) }}</span>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2"><div class="col-12"><strong>Producto:</strong> {{ (is_array($e->items ?? null) && count($e->items)>0) ? (count($e->items) . ' productos') : ($e->producto) }}</div></div>
                                            <div class="row mb-2"><div class="col-12"><strong>Categoría:</strong> {{ (is_array($e->items ?? null) && count($e->items)>0) ? 'Múltiples' : ucfirst($e->categoria_producto) }}</div></div>
                                            <div class="row mb-2"><div class="col-12"><strong>Unidades:</strong> {{ $unidadesTotal }}</div></div>
                                            <div class="row mb-2"><div class="col-12"><strong>Peso Total:</strong> {{ number_format($pesoTotal, 2) }} kg</div></div>
                                            <div class="row mb-2"><div class="col-12"><strong>Precio Total:</strong> Bs {{ number_format($precioTotal, 2) }}</div></div>
                                            <div class="row mb-2"><div class="col-12"><strong>Entrega Deseada:</strong> {{ $fechaEntrega }}</div></div>
                                            <div class="row mb-2"><div class="col-12"><strong>Transporte:</strong> {{ $transLabel }}</div></div>
                                            <div class="row mb-3"><div class="col-12"><strong>Fecha:</strong> {{ $fechaCreacion }}</div></div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('envios.show', $e->id) }}" class="btn btn-primary btn-block btn-sm">
                                                <i class="fas fa-eye"></i> Detalle de Envío
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-home mr-1"></i>Volver al Dashboard
                    </a>
                    <a href="{{ route('envios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>Nuevo Envío
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection

