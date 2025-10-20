@extends('admin')

@section('title', 'Detalle de Envío')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">Detalle de Envío #{{ $envio->id }}</h3>
        </div>
        <div class="card-body">
          @php
            $estado = $envio->estado;
            $estadoClass = $estado==='confirmado' ? 'success' : ($estado==='pendiente' ? 'warning' : ($estado==='en_proceso' ? 'primary' : 'info'));
            $items = is_array($envio->items ?? null) ? $envio->items : [];
            if (count($items)>0) {
              $pesoTotal=0; $precioTotal=0; $unidadesTotal=0;
              foreach($items as $it){
                $u=(int)($it['unidades_totales']??0);
                $pesoTotal += (float)($it['peso_producto_unidad']??0)*$u;
                $precioTotal += (float)($it['precio_producto']??0)*$u;
                $unidadesTotal += $u;
              }
            } else {
              $pesoTotal=(float)($envio->peso_producto_unidad??0)*(int)($envio->unidades_totales??0);
              $precioTotal=(float)($envio->precio_producto??0)*(int)($envio->unidades_totales??0);
              $unidadesTotal=(int)($envio->unidades_totales??0);
            }
            $map = \App\Models\Envio::TRANSPORTES_DISPONIBLES;
            $transKey = $envio->transporte_seleccionado;
            $transLabel = $map[$transKey] ?? ($envio->transporte_sugerido ? ($map[$envio->transporte_sugerido] ?? $envio->transporte_sugerido) : 'No asignado');
          @endphp

          <div class="row mb-3">
            <div class="col-md-3"><strong>Estado:</strong> <span class="badge badge-{{ $estadoClass }}">{{ ucfirst(str_replace('_',' ', $estado)) }}</span></div>
            <div class="col-md-3"><strong>Transporte:</strong> {{ $transLabel }}</div>
            <div class="col-md-3"><strong>Entrega deseada:</strong> {{ optional($envio->fecha_entrega_deseada)->format('d/m/Y H:i') ?? '-' }}</div>
            <div class="col-md-3"><strong>Destino:</strong> {{ $envio->destino_direccion ?? (($envio->destino_lat && $envio->destino_lng) ? (number_format($envio->destino_lat,5).', '.number_format($envio->destino_lng,5)) : '-') }}</div>
          </div>

          <div class="row">
            <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Unidades</span><span class="info-box-number">{{ $unidadesTotal }}</span></div></div></div>
            <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Peso Total</span><span class="info-box-number">{{ number_format($pesoTotal,2) }} kg</span></div></div></div>
            <div class="col-md-4"><div class="info-box bg-success"><div class="info-box-content"><span class="info-box-text">Total (Bs)</span><span class="info-box-number">{{ number_format($precioTotal,2) }}</span></div></div></div>
          </div>

          <div class="mt-3">
            <h5>Productos</h5>
            @if(count($items)>0)
              <div class="table-responsive">
                <table class="table table-sm table-striped">
                  <thead><tr><th>#</th><th>Categoría</th><th>Producto</th><th>Precio (Bs)</th><th>Unidades</th><th>Peso Unidad</th></tr></thead>
                  <tbody>
                    @php $i=1; @endphp
                    @foreach($items as $it)
                      <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ ucfirst($it['categoria_producto']??'-') }}</td>
                        <td>{{ $it['producto']??'-' }}</td>
                        <td>{{ number_format((float)($it['precio_producto']??0),2) }}</td>
                        <td>{{ (int)($it['unidades_totales']??0) }}</td>
                        <td>{{ number_format((float)($it['peso_producto_unidad']??0),2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="row">
                <div class="col-md-3"><strong>Categoría:</strong> {{ ucfirst($envio->categoria_producto) }}</div>
                <div class="col-md-3"><strong>Producto:</strong> {{ $envio->producto }}</div>
                <div class="col-md-3"><strong>Unidades:</strong> {{ $envio->unidades_totales }}</div>
                <div class="col-md-3"><strong>Peso Unidad:</strong> {{ number_format((float)($envio->peso_producto_unidad??0),2) }} kg</div>
              </div>
            @endif
          </div>

          <div class="mt-4">
            <a href="{{ route('envios.mis') }}" class="btn btn-outline-secondary"><i class="fas fa-list mr-1"></i> Volver a Mis Envíos</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


