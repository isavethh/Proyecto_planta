@extends('admin')

@section('title', 'Dashboard de Envíos')

@section('content')
<div class="container-fluid">
    <!-- Header con información del usuario -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 id="dash-nombre">Bienvenido usuario</h4>
                            <p class="text-muted">Sistema de Gestión de Envíos</p>
                        </div>
                        <div class="text-right">
                            <span id="dash-rol" class="badge badge-info">Usuario</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas del dashboard: Visibles por rol -->
    <div class="row" id="dash-cards"></div>

    <!-- Estadísticas rápidas (solo para admin, usando localStorage) -->
    <div id="dash-admin-stats" class="row" style="display:none;"></div>

    <!-- Se eliminó el bloque de "Tus pedidos" para un dashboard más limpio -->
</div>
@endsection

@section('js')
<script>
$(function(){
  // Simula nombre y rol desde sesión mínima
  const nombre = @json(session('user_name')) || 'Usuario';
  const rol = @json(session('user_role')) || 'user';
  $('#dash-nombre').text(`Bienvenido usuario`);
  $('#dash-rol').removeClass('badge-info badge-primary').addClass(rol==='admin'?'badge-primary':'badge-info').text(rol==='admin'?'Administrador':'Usuario');

  function obtenerEnvios(){ try { return JSON.parse(localStorage.getItem('envios')||'[]'); } catch(e){ return []; } }
  if(rol==='admin'){
    const envios = obtenerEnvios();
    const pendientes = envios.filter(x=>x.estado==='pendiente').length;
    const confirmados = envios.filter(x=>x.estado==='confirmado').length;
    const total = envios.length;
    const transportistas = 3; // número fijo solo UI
    const html = `
      <div class="col-md-3"><div class="small-box bg-info"><div class="inner"><h3>${total}</h3><p>Total de Envíos</p></div><div class="icon"><i class="fas fa-truck"></i></div><a href="{{ url('admin/envios') }}" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a></div></div>
      <div class="col-md-3"><div class="small-box bg-warning"><div class="inner"><h3>${pendientes}</h3><p>Envíos Pendientes</p></div><div class="icon"><i class="fas fa-clock"></i></div><a href="{{ url('admin/envios') }}" class="small-box-footer">Gestionar <i class="fas fa-arrow-circle-right"></i></a></div></div>
      <div class="col-md-3"><div class="small-box bg-success"><div class="inner"><h3>${confirmados}</h3><p>Envíos Confirmados</p></div><div class="icon"><i class="fas fa-check-circle"></i></div><a href="{{ url('admin/envios') }}" class="small-box-footer">Ver detalles <i class="fas fa-arrow-circle-right"></i></a></div></div>
      <div class="col-md-3"><div class="small-box bg-primary"><div class="inner"><h3>${transportistas}</h3><p>Transportistas</p></div><div class="icon"><i class="fas fa-users"></i></div><div class="small-box-footer"><span class="text-white-50">Activos</span></div></div></div>`;
    $('#dash-admin-stats').html(html).show();

    // Admin: tarjetas completas (6)
    const adminCards = `
      <div class="col-md-4 col-sm-6 mb-4"><div class="card card-primary card-outline"><div class="card-body text-center"><i class="fas fa-plus-circle fa-3x text-primary mb-3"></i><h5>Crear Nuevo Envío</h5><p class="text-muted">Inicia el proceso de envío</p><a href="{{ url('envios/create') }}" class="btn btn-primary btn-block"><i class="fas fa-truck-moving mr-2"></i>Crear Envío</a></div></div></div>
      <div class="col-md-4 col-sm-6 mb-4"><div class="card card-info card-outline"><div class="card-body text-center"><i class="fas fa-list fa-3x text-info mb-3"></i><h5>Mis Envíos</h5><p class="text-muted">Consulta tus envíos</p><a href="{{ url('mis-envios') }}" class="btn btn-info btn-block"><i class="fas fa-eye mr-2"></i>Ver Envíos</a></div></div></div>
      <div class="col-md-4 col-sm-6 mb-4"><div class="card card-warning card-outline"><div class="card-body text-center"><i class="fas fa-file-contract fa-3x text-warning mb-3"></i><h5>Reglamento</h5><p class="text-muted">Políticas y condiciones</p><a href="{{ url('reglamento') }}" class="btn btn-warning btn-block"><i class="fas fa-book mr-2"></i>Ver Reglamento</a></div></div></div>
      <div class="col-md-4 col-sm-6 mb-4"><div class="card card-success card-outline"><div class="card-body text-center"><i class="fas fa-file-pdf fa-3x text-success mb-3"></i><h5>Documentos de Pedido</h5><p class="text-muted">Accede a la documentación</p><a href="{{ url('documento-pedido') }}" class="btn btn-success btn-block"><i class="fas fa-download mr-2"></i>Ver Documentos</a></div></div></div>
      <div class="col-md-4 col-sm-6 mb-4"><div class="card card-secondary card-outline"><div class="card-body text-center"><i class="fas fa-search fa-3x text-secondary mb-3"></i><h5>Seguimiento</h5><p class="text-muted">Seguimiento en tiempo real</p><button class="btn btn-secondary btn-block" onclick="alert('Funcionalidad próximamente')"><i class="fas fa-satellite-dish mr-2"></i>Seguimiento</button></div></div></div>
      <div class="col-md-4 col-sm-6 mb-4"><div class="card card-danger card-outline"><div class="card-body text-center"><i class="fas fa-headset fa-3x text-danger mb-3"></i><h5>Soporte</h5><p class="text-muted">¿Necesitas ayuda?</p><button class="btn btn-danger btn-block" onclick="alert('Soporte disponible 24/7')"><i class="fas fa-phone mr-2"></i>Contactar Soporte</button></div></div></div>`;
    $('#dash-cards').html(adminCards);
  } else {
    $('#dash-user-info').show();
    // Usuario: solo Crear envío y Mis envíos
    const userCards = `
      <div class="col-md-6 col-sm-6 mb-4"><div class="card card-primary card-outline"><div class="card-body text-center"><i class="fas fa-plus-circle fa-3x text-primary mb-3"></i><h5>Crear Nuevo Envío</h5><p class="text-muted">Inicia el proceso</p><a href="{{ url('envios/create') }}" class="btn btn-primary btn-block"><i class="fas fa-truck-moving mr-2"></i>Crear Envío</a></div></div></div>
      <div class="col-md-6 col-sm-6 mb-4"><div class="card card-info card-outline"><div class="card-body text-center"><i class="fas fa-list fa-3x text-info mb-3"></i><h5>Mis Envíos</h5><p class="text-muted">Estados de tus pedidos</p><a href="{{ url('mis-envios') }}" class="btn btn-info btn-block"><i class="fas fa-eye mr-2"></i>Ver Envíos</a></div></div></div>`;
    $('#dash-cards').html(userCards);
  }
});
</script>
@endsection
