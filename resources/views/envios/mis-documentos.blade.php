@extends('admin')

@section('title', 'Mis Documentos')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">Mis Documentos</h3>
          <a href="{{ route('envios.mis') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Volver a Mis Envíos</a>
        </div>
        <div class="card-body">
          @if(($envios ?? collect())->isEmpty())
            <div class="text-center py-5"><i class="fas fa-inbox fa-4x text-muted mb-3"></i><h4>No hay documentos</h4></div>
          @else
            <div class="row">
              @foreach($envios as $e)
                @php $estadoClass = $e->estado==='confirmado' ? 'success' : ($e->estado==='pendiente' ? 'warning' : 'info'); @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card card-{{ $estadoClass }} card-outline">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Pedido #{{ $e->id }}</h5>
                      <span class="badge badge-{{ $estadoClass }}">{{ ucfirst($e->estado) }}</span>
                    </div>
                    <div class="card-body">
                      <div class="row mb-2"><div class="col-6"><strong>Producto:</strong></div><div class="col-6">{{ $e->producto }}</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Unidades:</strong></div><div class="col-6">{{ $e->unidades_totales }}</div></div>
                      <div class="row mb-2"><div class="col-6"><strong>Creado:</strong></div><div class="col-6">{{ optional($e->created_at ?? $e->fecha_creacion)->format('d/m/Y H:i') }}</div></div>
                    </div>
                    <div class="card-footer">
                      <a href="{{ route('envios.documento', $e->id) }}" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> Ver Documento</a>
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


