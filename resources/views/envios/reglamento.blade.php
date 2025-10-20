@extends('admin')

@section('title', 'Reglamento de Envíos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Reglamento y Condiciones de Servicio</h3>
                    <div class="card-tools">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Información General -->
                            <div class="mb-4">
                                <h4><i class="fas fa-info-circle text-primary"></i> Información General</h4>
                                <p>Este documento establece las condiciones generales para la utilización de nuestros servicios de envío y transporte de mercancías desde nuestra planta hacia destinos especificados por el cliente.</p>
                            </div>

                            <!-- Política de Accidentes -->
                            <div class="mb-4">
                                <h4><i class="fas fa-exclamation-triangle text-warning"></i> Política en Caso de Accidentes</h4>
                                <div class="alert alert-warning">
                                    <strong>Importante:</strong> En caso de que el transporte sufra un accidente durante el trayecto, <strong>se procederá a la devolución completa del monto pagado por el cliente</strong>. Esta política aplica independientemente de la causa del accidente o las circunstancias que lo rodeen.
                                </div>
                                <p>Esta medida busca garantizar la tranquilidad y confianza de nuestros clientes, asegurando que no sufran pérdidas económicas en situaciones fortuitas fuera de su control.</p>
                            </div>

                            <!-- Formas de Pago Aceptadas -->
                            <div class="mb-4">
                                <h4><i class="fas fa-credit-card text-success"></i> Formas de Pago Aceptadas</h4>
                                <div class="row">
                                    <div class="col-md-4 text-center mb-3">
                                        <i class="fas fa-dollar-sign fa-3x text-success mb-2"></i>
                                        <h6>Bolivianos (BOB)</h6>
                                        <p>Aceptamos pagos en bolivianos por transferencia bancaria o en efectivo.</p>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <i class="fas fa-coins fa-3x text-warning mb-2"></i>
                                        <h6>USDT (Tether)</h6>
                                        <p>Criptomoneda estable vinculada al dólar estadounidense</p>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <i class="fas fa-circle-notch fa-3x text-info mb-2"></i>
                                        <h6>USDC (USD Coin)</h6>
                                        <p>Criptomoneda estable regulada y auditada</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Proceso de Envío -->
                            <div class="mb-4">
                                <h4><i class="fas fa-truck text-primary"></i> Proceso de Envío</h4>
                                <ol>
                                    <li><strong>Creación del Pedido:</strong> El cliente completa el formulario de envío con todos los detalles requeridos.</li>
                                    <li><strong>Revisión Inicial:</strong> El sistema sugiere automáticamente el tipo de transporte más adecuado.</li>
                                    <li><strong>Selección:</strong> El cliente elige entre Transporte Aislado, Ventilado o Refrigerado.</li>
                                    <li><strong>Estado Pendiente:</strong> El pedido queda en estado "pendiente" hasta que el administrador lo revise.</li>
                                    <li><strong>Asignación de Transporte:</strong> El administrador asigna transportista y vehículo específicos y define el tamaño del transporte.</li>
                                    <li><strong>Confirmación Final:</strong> Una vez asignado el transporte, el pedido se marca como "confirmado".</li>
                                    <li><strong>Entrega:</strong> El cliente recibe el producto en la dirección especificada.</li>
                                </ol>
                            </div>

                            <!-- Estados del Pedido -->
                            <div class="mb-4">
                                <h4><i class="fas fa-list text-info"></i> Estados del Pedido</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card card-warning"><div class="card-body text-center"><i class="fas fa-clock fa-2x text-warning mb-2"></i><h6>Pendiente</h6><small>En espera de revisión del administrador.</small></div></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-success"><div class="card-body text-center"><i class="fas fa-check-circle fa-2x text-success mb-2"></i><h6>Confirmado</h6><small>Cuando el admin designa transportista, transporte y lo envía.</small></div></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-primary"><div class="card-body text-center"><i class="fas fa-route fa-2x text-primary mb-2"></i><h6>En Proceso</h6><small>Cuando el transportista acepta el pedido desde su aplicación.</small></div></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card card-info"><div class="card-body text-center"><i class="fas fa-box-open fa-2x text-info mb-2"></i><h6>Recibido</h6><small>Cuando el cliente acepta el recibimiento del pedido.</small></div></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Responsabilidades -->
                            <div class="mb-4">
                                <h4><i class="fas fa-shield-alt text-danger"></i> Responsabilidades</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Del Cliente:</h6>
                                        <ul>
                                            <li>Proporcionar información precisa del envío</li>
                                            <li>Estar disponible para recibir el envío</li>
                                            <li>Realizar el pago según las condiciones acordadas</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Roles Operativos:</h6>
                                        <ul>
                                            <li><strong>Empresa:</strong> Garantizar la integridad del producto durante el transporte; asignar el transporte más adecuado; cumplir con los tiempos de entrega estimados.</li>
                                            <li><strong>Transportista:</strong> Aceptar o rechazar pedidos asignados; compartir su ubicación en tiempo real con el cliente durante el trayecto; confirmar cuando haya entregado el pedido al cliente.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contacto -->
                            <div class="mb-4">
                                <h4><i class="fas fa-phone text-success"></i> Contacto y Soporte</h4>
                                <p>Para cualquier consulta o problema relacionado con su envío, puede contactarnos:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Teléfono:</strong> +1 (555) 123-4567<br>
                                        <strong>Email:</strong> soporte@empresa.com<br>
                                        <strong>Horario:</strong> 24/7
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Dirección:</strong><br>
                                        Planta Central<br>
                                        Calle Principal 123<br>
                                        Ciudad Industrial
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar con información rápida -->
                        <div class="col-md-4">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h5 class="card-title">Información Rápida</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-map-marker-alt"></i> Origen de Envíos</h6>
                                        <p class="text-sm">Planta Central<br>Calle Principal 123<br>Ciudad Industrial</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-clock"></i> Tiempo de Procesamiento</h6>
                                        <p class="text-sm">Los pedidos son procesados dentro de las 24 horas siguientes a su creación.</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-shield-alt"></i> Seguro Incluido</h6>
                                        <p class="text-sm">Todos los envíos incluyen cobertura contra accidentes y pérdidas.</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-truck"></i> Tipos de Transporte</h6>
                                        <ul class="list-unstyled text-sm">
                                            <li><i class="fas fa-check text-success"></i> Transporte Aislado</li>
                                            <li><i class="fas fa-check text-success"></i> Transporte Ventilado</li>
                                            <li><i class="fas fa-check text-success"></i> Transporte Refrigerado</li>
                                        </ul>
                                        <small class="text-muted d-block">El tamaño del transporte lo define el administrador según la cantidad total de productos/peso.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Última actualización -->
                            <div class="card card-secondary">
                                <div class="card-body text-center">
                                    <small class="text-muted">
                                        Última actualización: {{ date('d/m/Y') }}<br>
                                        Versión 1.0
                                    </small>
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

