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

                <div class="card-body">
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
                                    $pesoTotal = (float)($e->peso_producto_unidad ?? 0) * (int)($e->unidades_totales ?? 0);
                                    $precioTotal = (float)($e->precio_producto ?? 0) * (int)($e->unidades_totales ?? 0);
                                    $estadoClass = $e->estado === 'confirmado' ? 'success' : ($e->estado === 'pendiente' ? 'warning' : 'info');
                                    $map = \App\Models\Envio::TRANSPORTES_DISPONIBLES;
                                    $transKey = $e->transporte_seleccionado;
                                    $transLabel = isset($map[$transKey]) ? $map[$transKey] : ($e->transporte_sugerido ?? 'No asignado');
                                    $fechaCreacionRaw = $e->created_at ?? $e->fecha_creacion ?? null;
                                    $fechaCreacion = $fechaCreacionRaw ? \Illuminate\Support\Carbon::parse($fechaCreacionRaw)->format('d/m/Y H:i') : '-';
                                    $fechaEntrega = $e->fecha_entrega_deseada ? \Illuminate\Support\Carbon::parse($e->fecha_entrega_deseada)->format('d/m/Y H:i') : '-';
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card card-{{ $estadoClass }} card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                Envío #{{ $e->id }}
                                                <span class="badge badge-{{ $estadoClass }} float-right">{{ ucfirst($e->estado) }}</span>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2"><div class="col-6"><strong>Producto:</strong></div><div class="col-6">{{ $e->producto }}</div></div>
                                            <div class="row mb-2"><div class="col-6"><strong>Categoría:</strong></div><div class="col-6">{{ ucfirst($e->categoria_producto) }}</div></div>
                                            <div class="row mb-2"><div class="col-6"><strong>Unidades:</strong></div><div class="col-6">{{ $e->unidades_totales }}</div></div>
                                            <div class="row mb-2"><div class="col-6"><strong>Peso Total:</strong></div><div class="col-6">{{ number_format($pesoTotal, 2) }} kg</div></div>
                                            <div class="row mb-2"><div class="col-6"><strong>Precio Total:</strong></div><div class="col-6">Bs {{ number_format($precioTotal, 2) }}</div></div>
                                            <div class="row mb-2"><div class="col-6"><strong>Entrega Deseada:</strong></div><div class="col-6">{{ $fechaEntrega }}</div></div>
                                            <div class="row mb-2"><div class="col-6"><strong>Transporte:</strong></div><div class="col-6">{{ $transLabel }}</div></div>
                                            <div class="row mb-3"><div class="col-6"><strong>Fecha:</strong></div><div class="col-6">{{ $fechaCreacion }}</div></div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group btn-group-sm w-100">
                                                <a href="{{ route('envios.documento', $e->id) }}" class="btn btn-success" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Documento
                                                </a>
                                            </div>
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

