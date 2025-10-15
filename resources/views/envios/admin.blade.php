@extends('admin')

@section('title', 'Administración de Envíos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestión de Envíos</h3>
                    <div class="card-tools">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @php($list = $envios ?? collect())
                    @if($list->isEmpty())
                        <div class="text-center py-5"><i class="fas fa-inbox fa-4x text-muted mb-3"></i><h4>No hay envíos para gestionar</h4><p class="text-muted">Los nuevos pedidos aparecerán aquí cuando los clientes creen envíos.</p></div>
                    @else
                        <div class="row">
                            @foreach($list as $e)
                                @php
                                    $pesoTotal = (float)($e->peso_producto_unidad ?? 0) * (int)($e->unidades_totales ?? 0);
                                    $precioTotal = (float)($e->precio_producto ?? 0) * (int)($e->unidades_totales ?? 0);
                                    $estadoClass = $e->estado === 'confirmado' ? 'success' : ($e->estado === 'pendiente' ? 'warning' : 'info');
                                @endphp
                                <div class="col-12 mb-4">
                                  <div class="card card-{{ $estadoClass }} card-outline">
                                    <div class="card-header">
                                      <h5 class="card-title mb-0">Pedido #{{ $e->id }} - {{ $e->producto }}
                                        <span class="badge badge-{{ $estadoClass }} float-right">{{ ucfirst($e->estado) }}</span>
                                      </h5>
                                    </div>
                                    <div class="card-body">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <h6>Información del Pedido</h6>
                                          <div class="row mb-2"><div class="col-6"><strong>Categoría:</strong></div><div class="col-6">{{ $e->categoria_producto }}</div></div>
                                          <div class="row mb-2"><div class="col-6"><strong>Unidades:</strong></div><div class="col-6">{{ $e->unidades_totales }}</div></div>
                                          <div class="row mb-2"><div class="col-6"><strong>Peso Total:</strong></div><div class="col-6">{{ number_format($pesoTotal,2) }} kg</div></div>
                                          <div class="row mb-2"><div class="col-6"><strong>Precio Total:</strong></div><div class="col-6">Bs {{ number_format($precioTotal,2) }}</div></div>
                                          <div class="row mb-2"><div class="col-6"><strong>Fecha:</strong></div><div class="col-6">{{ optional($e->created_at ?? $e->fecha_creacion)->format('d/m/Y H:i') }}</div></div>
                                        </div>
                                        <div class="col-md-6">
                                          <h6>Información de Transporte</h6>
                                          <div class="row mb-2"><div class="col-6"><strong>Sugerido:</strong></div><div class="col-6">{{ $e->transporte_sugerido }}</div></div>
                                          <div class="row mb-2"><div class="col-6"><strong>Seleccionado:</strong></div><div class="col-6">{{ $e->transporte_seleccionado }}</div></div>
                                          <form method="POST" action="{{ route('admin.asignar-transporte', $e->id) }}">
                                            @csrf
                                            <div class="row mb-2"><div class="col-6"><strong>Transportista:</strong></div><div class="col-6">
                                              <select class="form-control" name="transportista_id" required>
                                                <option value="">Seleccionar transportista...</option>
                                                @foreach(($transportistas ?? []) as $t)
                                                  <option value="{{ $t->id }}">{{ $t->nombre ?? ('ID '.$t->id) }}</option>
                                                @endforeach
                                              </select>
                                            </div></div>
                                            <div class="row mb-2"><div class="col-6"><strong>Vehículo:</strong></div><div class="col-6">
                                              <select class="form-control" name="vehiculo_id" required>
                                                <option value="">Seleccionar vehículo...</option>
                                                @foreach(($vehiculos ?? []) as $v)
                                                  <option value="{{ $v->id }}">{{ $v->placa ?? ('ID '.$v->id) }}</option>
                                                @endforeach
                                              </select>
                                            </div></div>
                                            <div class="text-right">
                                              <button class="btn btn-success"><i class="fas fa-check mr-1"></i>Confirmar</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card-footer">
                                      <div class="btn-group">
                                        <a href="{{ route('envios.documento', $e->id) }}" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> Ver Documento</a>
                                      </div>
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

@section('js')
<script>
$(function(){
  function obtenerEnvios(){ try { return JSON.parse(localStorage.getItem('envios')||'[]'); } catch(e){ return []; } }
  function guardarEnvios(envios){ localStorage.setItem('envios', JSON.stringify(envios)); }
  function badgeEstado(estado){ if(estado==='pendiente') return 'warning'; if(estado==='confirmado') return 'success'; return 'info'; }
  function nombreTransporte(key){ const map={ small:'Camión Pequeño', medium:'Camión Mediano', large:'Camión Grande', refrigerated:'Camión Refrigerado', air:'Avión de Carga', ship:'Transporte Marítimo' }; return map[key]||'No asignado'; }

  const envios = obtenerEnvios();
  const $root = $('#admin-envios-root');

  if(envios.length===0){
    $root.html('<div class="text-center py-5"><i class="fas fa-inbox fa-4x text-muted mb-3"></i><h4>No hay envíos para gestionar</h4><p class="text-muted">Los nuevos pedidos aparecerán aquí cuando los clientes creen envíos.</p></div>');
    return;
  }

  const rows = envios.map((e,i)=>{
    const pesoTotal = (e.peso_producto_unidad||0)*(e.unidades_totales||0);
    const precioTotal = (e.precio_producto||0)*(e.unidades_totales||0);
    const fecha = e.fecha_creacion ? new Date(e.fecha_creacion) : new Date();
    const selectTransportistas = `<select class="form-control js-transportista" data-id="${e.id}"><option value="">Seleccionar transportista...</option><option value="Juan Pérez">Juan Pérez</option><option value="María López">María López</option><option value="Luis García">Luis García</option></select>`;
    const selectVehiculos = `<select class="form-control js-vehiculo" data-id="${e.id}"><option value="">Seleccionar vehículo...</option><option value="ABC-123 (Pequeño)">ABC-123 (Pequeño)</option><option value="DEF-456 (Mediano)">DEF-456 (Mediano)</option><option value="GHI-789 (Refrigerado)">GHI-789 (Refrigerado)</option></select>`;

    return `
    <div class="col-12 mb-4">
      <div class="card card-${badgeEstado(e.estado)} card-outline">
        <div class="card-header">
          <h5 class="card-title mb-0">Pedido #${e.id} - ${e.producto}
            <span class="badge badge-${badgeEstado(e.estado)} float-right">${e.estado.charAt(0).toUpperCase()+e.estado.slice(1)}</span>
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6>Información del Pedido</h6>
              <div class="row mb-2"><div class="col-6"><strong>Categoría:</strong></div><div class="col-6">${e.categoria_producto}</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Unidades:</strong></div><div class="col-6">${e.unidades_totales}</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Peso Total:</strong></div><div class="col-6">${pesoTotal.toFixed(2)} kg</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Precio Total:</strong></div><div class="col-6">Bs ${precioTotal.toFixed(2)}</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Entrega Deseada:</strong></div><div class="col-6">${e.fecha_entrega_deseada ? new Date(e.fecha_entrega_deseada).toLocaleString() : '-'}</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Fecha:</strong></div><div class="col-6">${fecha.toLocaleDateString()} ${fecha.toLocaleTimeString()}</div></div>
            </div>
            <div class="col-md-6">
              <h6>Información de Transporte</h6>
              <div class="row mb-2"><div class="col-6"><strong>Sugerido:</strong></div><div class="col-6">${e.transporte_sugerido||'-'}</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Seleccionado:</strong></div><div class="col-6">${nombreTransporte(e.transporte_seleccionado)}</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Transportista:</strong></div><div class="col-6">${selectTransportistas}</div></div>
              <div class="row mb-2"><div class="col-6"><strong>Vehículo:</strong></div><div class="col-6">${selectVehiculos}</div></div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12 text-right">
              <button class="btn btn-success js-confirmar" data-id="${e.id}"><i class="fas fa-check mr-1"></i>Confirmar</button>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="btn-group">
            <a href="{{ url('documento-pedido') }}" class="btn btn-info btn-sm" target="_blank" onclick="localStorage.setItem('envioActual', ${e.id});"><i class="fas fa-file-pdf"></i> Ver Documento</a>
          </div>
        </div>
      </div>
    </div>`;
  }).join('');

  const pendientes = envios.filter(x=>x.estado==='pendiente').length;
  const confirmados = envios.filter(x=>x.estado==='confirmado').length;
  const total = envios.length;
  const valorTotal = envios.reduce((acc,x)=> acc + (x.precio_producto||0)*(x.unidades_totales||0), 0);

  const stats = `
    <div class="row mt-4">
      <div class="col-md-3"><div class="info-box bg-info"><span class="info-box-icon"><i class="fas fa-truck"></i></span><div class="info-box-content"><span class="info-box-text">Total Envíos</span><span class="info-box-number">${total}</span></div></div></div>
      <div class="col-md-3"><div class="info-box bg-warning"><span class="info-box-icon"><i class="fas fa-clock"></i></span><div class="info-box-content"><span class="info-box-text">Pendientes</span><span class="info-box-number">${pendientes}</span></div></div></div>
      <div class="col-md-3"><div class="info-box bg-success"><span class="info-box-icon"><i class="fas fa-check-circle"></i></span><div class="info-box-content"><span class="info-box-text">Confirmados</span><span class="info-box-number">${confirmados}</span></div></div></div>
      <div class="col-md-3"><div class="info-box bg-primary"><span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span><div class="info-box-content"><span class="info-box-text">Valor Total</span><span class="info-box-number">$${valorTotal.toFixed(2)}</span></div></div></div>
    </div>`;

  $root.html(`<div class="row">${rows}</div>${stats}`);

  // Handlers
  $root.on('click', '.js-confirmar', function(){
    const id = parseInt($(this).data('id'),10);
    const transportista = $(`.js-transportista[data-id='${id}']`).val();
    const vehiculo = $(`.js-vehiculo[data-id='${id}']`).val();
    const envs = obtenerEnvios().map(x=>{
      if(x.id===id){
        x.transportista = transportista || x.transportista;
        x.vehiculo = vehiculo || x.vehiculo;
        x.estado = 'confirmado';
        x.fecha_confirmacion = new Date().toISOString();
      }
      return x;
    });
    guardarEnvios(envs);
    location.reload();
  });
});
</script>
@endsection

