@extends('admin')

@section('title', 'Detalle del Pedido')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">Pedido #{{ $envio->id }}</h3>
          <div class="card-tools">
            <a href="{{ route('admin.envios') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Volver a Envíos</a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-light">
                <div class="card-header"><h5 class="card-title mb-0">Datos del Pedido</h5></div>
                <div class="card-body">
                  <div class="row mb-2"><div class="col-6"><strong>Cliente ID:</strong></div><div class="col-6">{{ $envio->cliente_id }}</div></div>
                  <div class="row mb-2"><div class="col-6"><strong>Destino:</strong></div><div class="col-6">{{ $envio->destino_direccion ?? '-' }}</div></div>
                  <div class="row mb-2"><div class="col-6"><strong>Entrega Deseada:</strong></div><div class="col-6">{{ optional($envio->fecha_entrega_deseada)->format('d/m/Y H:i') ?? '-' }}</div></div>
                  <div class="row mb-2"><div class="col-6"><strong>Estado:</strong></div><div class="col-6"><span class="badge badge-{{ $envio->estado==='confirmado'?'success':'warning' }}">{{ ucfirst($envio->estado) }}</span></div></div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-light">
                <div class="card-header"><h5 class="card-title mb-0">Resumen</h5></div>
                <div class="card-body">
                  @php
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
                  @endphp
                  <div class="row">
                    <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Productos</span><span class="info-box-number">{{ count($items)>0 ? (count($items).' productos') : 1 }}</span></div></div></div>
                    <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Unidades</span><span class="info-box-number">{{ $unidadesTotal }}</span></div></div></div>
                    <div class="col-md-4"><div class="info-box bg-success"><div class="info-box-content"><span class="info-box-text">Total</span><span class="info-box-number">Bs {{ number_format($precioTotal,2) }}</span></div></div></div>
                  </div>
                  @php
                    $sugerenciaTam = 'Pequeño';
                    if($unidadesTotal > 500 || $pesoTotal > 1500){ $sugerenciaTam = 'Grande'; }
                    elseif($unidadesTotal > 200 || $pesoTotal > 700){ $sugerenciaTam = 'Mediano'; }
                  @endphp
                  <div class="alert alert-info mt-2"><i class="fas fa-truck mr-1"></i> Sugerencia de tamaño de transporte: <strong>{{ $sugerenciaTam }}</strong> (el administrador tiene la última palabra).</div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <div class="card card-outline card-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">Productos del Pedido</h5>
                  <small class="text-muted">Verifica nombres, unidades, peso y precio por unidad</small>
                </div>
                <div class="card-body">
                  @if(count($items)>0)
                    <div class="table-responsive">
                      <table class="table table-sm table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Categoría</th>
                            <th>Producto</th>
                            <th>Precio Unidad (Bs)</th>
                            <th>Unidades</th>
                            <th>Peso Unidad (kg)</th>
                            <th>Subtotal (Bs)</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php $i=1; @endphp
                          @foreach($items as $it)
                            @php $u=(int)($it['unidades_totales']??0); $sub=(float)($it['precio_producto']??0)*$u; @endphp
                            <tr>
                              <td>{{ $i++ }}</td>
                              <td>{{ ucfirst($it['categoria_producto']??'-') }}</td>
                              <td>{{ $it['producto']??'-' }}</td>
                              <td>{{ number_format((float)($it['precio_producto']??0),2) }}</td>
                              <td>{{ $u }}</td>
                              <td>{{ number_format((float)($it['peso_producto_unidad']??0),2) }}</td>
                              <td>{{ number_format($sub,2) }}</td>
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
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <div class="card card-outline card-primary">
                <div class="card-header"><h5 class="card-title mb-0">Confirmación</h5></div>
                <div class="card-body">
                  @if($envio->estado === \App\Models\Envio::ESTADO_PENDIENTE)
                    <form method="POST" action="{{ route('admin.asignar-transporte', $envio->id) }}">
                      @csrf
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Transportista</label>
                            <select class="form-control" name="transportista_id">
                              <option value="">Seleccionar transportista...</option>
                              @foreach(($transportistas ?? []) as $t)
                                <option value="{{ $t->id }}">{{ $t->nombre ?? ('ID '.$t->id) }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Tamaño de Transporte</label>
                            <div class="d-flex" style="gap:.5rem">
                              <label class="btn btn-outline-secondary mb-0"><input type="radio" name="tamano_transporte" value="pequeno" class="mr-1"> Pequeño</label>
                              <label class="btn btn-outline-secondary mb-0"><input type="radio" name="tamano_transporte" value="mediano" class="mr-1"> Mediano</label>
                              <label class="btn btn-outline-secondary mb-0"><input type="radio" name="tamano_transporte" value="grande" class="mr-1"> Grande</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Vehículo (opcional)</label>
                            <select class="form-control" name="vehiculo_id">
                              <option value="">Asignar automáticamente según tamaño</option>
                              @foreach(($vehiculos ?? []) as $v)
                                <option value="{{ $v->id }}">{{ ($v->tipo ?? 'camion') }} — {{ $v->placa ?? ('ID '.$v->id) }}</option>
                              @endforeach
                            </select>
                            <small class="form-text text-muted">Si no seleccionas uno, se asignará según el tamaño elegido.</small>
                          </div>
                        </div>
                      </div>
                      <div class="text-right">
                        <button class="btn btn-success"><i class="fas fa-check mr-1"></i>Confirmar Pedido</button>
                      </div>
                    </form>
                  @else
                    <div class="alert alert-success mb-0"><i class="fas fa-check-circle mr-1"></i> Pedido confirmado el {{ optional($envio->fecha_confirmacion)->format('d/m/Y H:i') }}</div>
                  @endif
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection


