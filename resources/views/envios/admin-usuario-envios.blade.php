@extends('admin')

@section('title', 'Pedidos del Usuario')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h3 class="card-title mb-0">Pedidos de {{ $usuario->nombre ?? ('ID '.$usuario->id) }}</h3>
            <div class="text-muted small">Cliente ID: {{ $usuario->id }}</div>
          </div>
          <div class="card-tools">
            <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Volver</a>
          </div>
        </div>
        <div class="card-body">
          @if(($envios ?? collect())->isEmpty())
            <div class="text-center py-5"><i class="fas fa-inbox fa-4x text-muted mb-3"></i><h4>Este usuario no tiene pedidos</h4></div>
          @else
            <div class="row">
              @foreach($envios as $e)
                @php
                  $items = is_array($e->items ?? null) ? $e->items : [];
                  if (count($items) > 0) {
                      $pesoTotal = 0; $precioTotal = 0; $unidadesTotal = 0;
                      foreach ($items as $it) {
                          $u = (int)($it['unidades_totales'] ?? 0);
                          $pesoTotal += (float)($it['peso_producto_unidad'] ?? 0) * $u;
                          $precioTotal += (float)($it['precio_producto'] ?? 0) * $u;
                          $unidadesTotal += $u;
                      }
                  } else {
                      $pesoTotal = (float)($e->peso_producto_unidad ?? 0) * (int)($e->unidades_totales ?? 0);
                      $precioTotal = (float)($e->precio_producto ?? 0) * (int)($e->unidades_totales ?? 0);
                      $unidadesTotal = (int)($e->unidades_totales ?? 0);
                  }
                  $estadoClass = $e->estado === 'confirmado' ? 'success' : ($e->estado === 'pendiente' ? 'warning' : 'info');
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card card-{{ $estadoClass }} card-outline">
                    <div class="card-header">
                      <h5 class="card-title mb-0">Pedido #{{ $e->id }} <span class="badge badge-{{ $estadoClass }} float-right">{{ ucfirst($e->estado) }}</span></h5>
                    </div>
                    <div class="card-body">
                      <div class="row mb-2"><div class="col-6"><strong>Productos:</strong></div><div class="col-6">{{ (count($items)>0) ? (count($items).' productos') : $e->producto }}</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Unidades:</strong></div><div class="col-6">{{ $unidadesTotal }}</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Peso Total:</strong></div><div class="col-6">{{ number_format($pesoTotal, 2) }} kg</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Precio Total:</strong></div><div class="col-6">Bs {{ number_format($precioTotal, 2) }}</div></div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                      <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.envios.show', $e->id) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Ver/Confirmar</a>
                        <a href="{{ route('envios.documento', $e->id) }}" target="_blank" class="btn btn-info"><i class="fas fa-file-pdf"></i> Documento</a>
                      </div>
                      <a href="{{ route('admin.envios') }}" class="btn btn-outline-secondary btn-sm">Volver a Envíos</a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


