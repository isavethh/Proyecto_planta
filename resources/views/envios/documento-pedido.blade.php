@extends('admin')

@section('title', 'Documento del Pedido')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Encabezado del documento -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <span id="doc-title">Documento de Pedido</span>
                        <span id="doc-estado" class="badge badge-warning">Pendiente</span>
                    </h3>
                    <div class="card-tools">
                        <button onclick="window.print()" class="btn btn-secondary btn-sm">
                            <i class="fas fa-print mr-1"></i>Imprimir
                        </button>
                        <a href="{{ route('envios.mis') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Volver a Mis Envíos
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Información de la empresa -->
                        <div class="col-md-6">
                            <div class="card card-light">
                                <div class="card-header">
                                    <h5 class="card-title">Datos de la Empresa</h5>
                                </div>
                                <div class="card-body">
                                    <strong>Empresa de Transportes S.A.</strong><br>
                                    Planta Central<br>
                                    Calle Principal 123<br>
                                    Ciudad Industrial<br>
                                    Teléfono: +1 (555) 123-4567<br>
                                    Email: info@empresa.com
                                </div>
                            </div>
                        </div>

                        <!-- Información del pedido -->
                        <div class="col-md-6">
                            <div class="card card-light">
                                <div class="card-header">
                                    <h5 class="card-title">Información del Pedido</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2"><div class="col-6"><strong>Número de Pedido:</strong></div><div class="col-6">#{{ ($envio->id ?? '-') }}</div></div>
                                    <div class="row mb-2"><div class="col-6"><strong>Fecha de Creación:</strong></div><div class="col-6">{{ optional($envio->created_at ?? $envio->fecha_creacion ?? null)->format('d/m/Y H:i') }}</div></div>
                                    <div class="row mb-2"><div class="col-6"><strong>Fecha de Confirmación:</strong></div><div class="col-6">{{ optional($envio->fecha_confirmacion ?? null)->format('d/m/Y H:i') ?? '-' }}</div></div>
                                    <div class="row mb-2"><div class="col-6"><strong>Estado:</strong></div><div class="col-6"><span class="badge badge-{{ ($envio->estado==='confirmado'?'success':'warning') }}">{{ ucfirst($envio->estado ?? 'pendiente') }}</span></div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del cliente -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-light">
                                <div class="card-header">
                                    <h5 class="card-title">Información del Cliente</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>ID de Cliente:</strong> <span>{{ $envio->cliente_id ?? '-' }}</span><br>
                                            <strong>Cliente:</strong> <span>{{ session('user_name') ?? 'Usuario' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Dirección de Origen:</strong><br>
                                            Planta Central - Calle Principal 123, Ciudad Industrial
                                            <br>
                                            <strong>Destino:</strong>
                                            <div>{{ $envio->destino_direccion ?? '-' }}</div>
                                            <br>
                                            <strong>Entrega Deseada:</strong> <span>{{ optional($envio->fecha_entrega_deseada ?? null)->format('d/m/Y H:i') ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del producto -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-light">
                                <div class="card-header">
                                    <h5 class="card-title">Detalles del Producto</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Categoría:</strong><br>
                                            <span>{{ ucfirst($envio->categoria_producto ?? '-') }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Producto:</strong><br>
                                            <span>{{ $envio->producto ?? '-' }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Peso por Unidad:</strong><br>
                                            <span>{{ number_format((float)($envio->peso_producto_unidad ?? 0), 2) }}</span> kg
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Unidades Totales:</strong><br>
                                            <span>{{ (int)($envio->unidades_totales ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de transporte -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-light">
                                <div class="card-header">
                                    <h5 class="card-title">Información de Transporte</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Transporte Sugerido:</strong><br>
                                            <span>{{ $envio->transporte_sugerido ?? '-' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Transporte Seleccionado:</strong><br>
                                            <span>{{ \App\Models\Envio::TRANSPORTES_DISPONIBLES[$envio->transporte_seleccionado] ?? ($envio->transporte_seleccionado ?? '-') }}</span>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong>Transportista Asignado:</strong><br>
                                            <span>{{ optional($envio->transportista)->nombre ?? 'No asignado' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Vehículo Asignado:</strong><br>
                                            <span>{{ optional($envio->vehiculo)->placa ?? 'No asignado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información financiera -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-light">
                                <div class="card-header">
                                    <h5 class="card-title">Información Financiera</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Precio por Unidad</span><span class="info-box-number">Bs {{ number_format((float)($envio->precio_producto ?? 0), 2) }}</span></div></div></div>
                                        <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Cantidad</span><span class="info-box-number">{{ (int)($envio->unidades_totales ?? 0) }}</span></div></div></div>
                                        <div class="col-md-4"><div class="info-box bg-success"><div class="info-box-content"><span class="info-box-text">Total a Pagar</span><span class="info-box-number">Bs {{ number_format((float)($envio->precio_producto ?? 0) * (int)($envio->unidades_totales ?? 0), 2) }}</span></div></div></div>
                                    </div>

                                    <div class="alert alert-info mt-3">
                                        <h6><i class="fas fa-info-circle"></i> Formas de Pago Aceptadas</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <i class="fas fa-dollar-sign text-success"></i> Dólares en efectivo
                                            </div>
                                            <div class="col-md-4">
                                                <i class="fas fa-coins text-warning"></i> USDT (Tether)
                                            </div>
                                            <div class="col-md-4">
                                                <i class="fas fa-circle-notch text-info"></i> USDC (USD Coin)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional según estado -->
                    <div id="doc-pendiente" class="row mt-3" style="display:none;">
                        <div class="col-12">
                            <div class="card card-warning">
                                <div class="card-header"><h5 class="card-title">Estado Pendiente</h5></div>
                                <div class="card-body">
                                    <p>Su pedido está siendo revisado por nuestro equipo administrativo. Una vez que asignemos el transportista y vehículo, recibirá una notificación de confirmación.</p>
                                    <p><strong>Tiempo estimado de procesamiento:</strong> 24 horas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="doc-confirmado" class="row mt-3" style="display:none;">
                        <div class="col-12">
                            <div class="card card-success">
                                <div class="card-header"><h5 class="card-title">Pedido Confirmado</h5></div>
                                <div class="card-body">
                                    <p><strong>¡Su pedido ha sido confirmado!</strong> El transportista asignado se pondrá en contacto con usted para coordinar la entrega.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Términos y condiciones -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h5 class="card-title">Términos y Condiciones</h5>
                                </div>
                                <div class="card-body">
                                    <p>Al solicitar este servicio, el cliente acepta los términos y condiciones establecidos en nuestro reglamento. En caso de accidente durante el transporte, se procederá a la devolución completa del monto pagado.</p>
                                    <p><strong>Fecha de emisión:</strong> {{ date('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <small class="text-muted">
                        Este documento es válido únicamente en formato digital.
                        Para consultas, contacte a nuestro departamento de atención al cliente.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
@media print {
    .btn, .card-tools, .card-footer {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-body {
        padding: 15px 0 !important;
    }
}
</style>
@endsection

@section('js')
@endsection

