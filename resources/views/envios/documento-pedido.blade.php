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
                        @php $estado=$envio->estado ?? 'pendiente'; $cls=$estado==='confirmado'?'success':($estado==='en_proceso'?'primary':($estado==='recibido'?'info':'warning')); @endphp
                        <span id="doc-estado" class="badge badge-{{ $cls }}">{{ ucfirst($estado) }}</span>
                    </h3>
                    <div class="card-tools">
                        <button onclick="window.print()" class="btn btn-light btn-sm shadow-sm">
                            <i class="fas fa-print mr-1"></i>Imprimir
                        </button>
                        @if(session('user_role')==='admin')
                            <a href="{{ route('admin.envios') }}" class="btn btn-warning btn-sm shadow-sm">
                                <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                            </a>
                        @else
                            <a href="{{ route('envios.mis.documentos') }}" class="btn btn-warning btn-sm shadow-sm">
                                <i class="fas fa-arrow-left mr-1"></i>Volver a Documentos de Envío
                            </a>
                        @endif
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

                    <!-- Detalles del producto(s) -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-light">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Detalles de los Productos</h5>
                                    @php $hayMultiples = is_array($envio->items ?? null) && count($envio->items ?? [])>0; @endphp
                                    <small class="text-muted">{{ $hayMultiples ? 'Múltiples productos' : 'Producto único' }}</small>
                                </div>
                                <div class="card-body">
                                    @if(is_array($envio->items ?? null) && count($envio->items ?? [])>0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
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
                                                    @php $i=1; $total=0; $totalPeso=0; $totalUnidades=0; @endphp
                                                    @foreach($envio->items as $it)
                                                        @php
                                                            $sub = (float)($it['precio_producto'] ?? 0) * (int)($it['unidades_totales'] ?? 0);
                                                            $total += $sub;
                                                            $totalPeso += (float)($it['peso_producto_unidad'] ?? 0) * (int)($it['unidades_totales'] ?? 0);
                                                            $totalUnidades += (int)($it['unidades_totales'] ?? 0);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ ucfirst($it['categoria_producto'] ?? '-') }}</td>
                                                            <td>{{ $it['producto'] ?? '-' }}</td>
                                                            <td>{{ number_format((float)($it['precio_producto'] ?? 0),2) }}</td>
                                                            <td>{{ (int)($it['unidades_totales'] ?? 0) }}</td>
                                                            <td>{{ number_format((float)($it['peso_producto_unidad'] ?? 0),2) }}</td>
                                                            <td>{{ number_format($sub,2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4" class="text-right">Totales</th>
                                                        <th>{{ $totalUnidades }}</th>
                                                        <th>{{ number_format($totalPeso,2) }}</th>
                                                        <th>Bs {{ number_format($total,2) }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
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
                                    @endif
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
                                    @php
                                        $precioTotal = method_exists($envio, 'getPrecioTotalAttribute') ? $envio->precio_total : ((float)($envio->precio_producto ?? 0) * (int)($envio->unidades_totales ?? 0));
                                        $unidadesTotalizadas = property_exists($envio, 'unidades_totalizadas') ? $envio->unidades_totalizadas : (int)($envio->unidades_totales ?? 0);
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Unidades Totales</span><span class="info-box-number">{{ $unidadesTotalizadas }}</span></div></div></div>
                                        <div class="col-md-4"><div class="info-box bg-light"><div class="info-box-content"><span class="info-box-text">Peso Total</span><span class="info-box-number">{{ number_format($envio->peso_total ?? 0, 2) }} kg</span></div></div></div>
                                        <div class="col-md-4"><div class="info-box bg-success"><div class="info-box-content"><span class="info-box-text">Total a Pagar</span><span class="info-box-number">Bs {{ number_format($precioTotal, 2) }}</span></div></div></div>
                                    </div>

                                    <div class="alert alert-info mt-3 text-center">
                                        <h6><i class="fas fa-info-circle"></i> Formas de Pago Aceptadas</h6>
                                        <div class="row justify-content-center">
                                            <div class="col-md-4 text-center">
                                                <i class="fas fa-coins text-success"></i> Bolivianos (transferencia o efectivo)
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <i class="fas fa-coins text-warning"></i> USDT (Tether)
                                            </div>
                                            <div class="col-md-4 text-center">
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

