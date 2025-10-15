@extends('admin')

@section('title', 'Documentos (Confirmados)')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Documentos de Envíos Confirmados</h3>
        </div>
        <div class="card-body">
          @if(($envios ?? collect())->isEmpty())
            <div class="text-center py-5">
              <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
              <h4>No hay documentos disponibles</h4>
            </div>
          @else
            <div class="row">
              @foreach($envios as $e)
                @php
                  $precioTotal = (float)($e->precio_producto ?? 0) * (int)($e->unidades_totales ?? 0);
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card card-success card-outline">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Pedido #{{ $e->id }}</h5>
                      <span class="badge badge-success">Confirmado</span>
                    </div>
                    <div class="card-body">
                      <div class="row mb-2"><div class="col-6"><strong>Producto:</strong></div><div class="col-6">{{ $e->producto }}</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Unidades:</strong></div><div class="col-6">{{ $e->unidades_totales }}</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Total:</strong></div><div class="col-6">Bs {{ number_format($precioTotal, 2) }}</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Confirmado:</strong></div><div class="col-6">{{ optional($e->fecha_confirmacion)->format('d/m/Y H:i') }}</div></div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                      <a href="{{ route('envios.documento', $e->id) }}" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> Ver Documento</a>
                      <a href="{{ route('admin.envios', ['estado' => 'confirmado']) }}" class="btn btn-outline-secondary btn-sm">Ver lista</a>
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


