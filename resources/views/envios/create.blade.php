@extends('admin')

@section('title', 'Crear Nuevo Envío')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Crear Nuevo Envío</h3>
                    <div class="card-tools">
                        <a href="{{ route('envios.mis') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Volver a Mis Envíos
                        </a>
                    </div>
                </div>

                <form id="form-crear-envio" method="POST" action="{{ route('envios.store') }}">
                    @csrf
                    <div class="card-body">
                        <!-- Información de la planta (hardcodeada) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-map-marker-alt"></i> Información de Origen</h5>
                                    <strong>Dirección desde Planta:</strong> Planta Central - Calle Principal 123, Ciudad Industrial
                                </div>
                            </div>
                        </div>

                        <!-- Ítems del pedido (múltiples productos) -->
                        <div class="row mb-2">
                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <h5 class="mb-0"><i class="fas fa-box mr-2"></i>Productos del Pedido</h5>
                                <button type="button" id="btn-add-item" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus mr-1"></i>Agregar otro producto
                                </button>
                            </div>
                        </div>

                        <div id="items-container">
                            <div class="card card-outline card-secondary mb-3 item-card" data-index="0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center" style="gap:.5rem">
                                        <span class="badge badge-secondary">Ítem <span class="item-number">1</span></span>
                                        <small class="text-muted">Ingrese categoría, producto, peso, unidades y precio por unidad</small>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" style="display:none">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                <div class="form-group">
                                                <label>Categoría <span class="text-danger">*</span></label>
                                                <select class="form-control item-categoria" name="items[0][categoria_producto]" required>
                                        <option value="">Seleccionar categoría...</option>
                                        <option value="alimentos">Alimentos</option>
                                        <option value="medicinas">Medicinas</option>
                                        <option value="electronica">Electrónica</option>
                                        <option value="ropa">Ropa</option>
                                        <option value="otros">Otros</option>
                                    </select>
                                </div>
                            </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Producto <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control item-producto" name="items[0][producto]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                <div class="form-group">
                                                <label>Precio por Unidad (Bs) <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                                    <input type="number" step="0.01" class="form-control item-precio" name="items[0][precio_producto]" placeholder="Ej.: 120.50" required>
                                                </div>
                                </div>
                            </div>
                        </div>
                                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                                <label>Peso por Unidad (kg) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" class="form-control item-peso" name="items[0][peso_producto_unidad]" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                                <label>Número de Unidades <span class="text-danger">*</span></label>
                                                <input type="number" min="1" class="form-control item-unidades" name="items[0][unidades_totales]" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transporte sugerido y seleccionado -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Transporte Sugerido</label>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <span id="transporte_sugerido_text">Se calculará basado en categorías y peso total</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Transporte <span class="text-danger">*</span></label>
                                    <div id="chips-transporte" class="d-flex flex-wrap" style="gap:.5rem">
                                        <button type="button" class="btn btn-outline-secondary btn-sm chip-transporte" data-key="camion_pequeno">Camión Pequeño</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm chip-transporte" data-key="camion_mediano">Camión Mediano</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm chip-transporte" data-key="camion_grande">Camión Grande</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm chip-transporte" data-key="camion_refrigerado">Camión Refrigerado</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm chip-transporte" data-key="avion_carga">Avión de Carga</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm chip-transporte" data-key="barco">Transporte Marítimo</button>
                                    </div>
                                    <input type="hidden" id="transporte_seleccionado" name="transporte_seleccionado" required>
                                    <small id="chip-ayuda" class="form-text text-muted">Haz clic para seleccionar. El sugerido se resaltará.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha/hora de entrega (única para todo el pedido) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_entrega_deseada">Fecha y Hora de Entrega Deseada</label>
                                    <input type="datetime-local" class="form-control" id="fecha_entrega_deseada" name="fecha_entrega_deseada">
                                </div>
                            </div>
                        </div>

                        <!-- Dirección de destino y mapa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title mb-0"><i class="fas fa-location-dot mr-2"></i>Destino del Cliente</h5>
                                        <small class="text-muted">Selecciona la dirección y visualiza la ruta</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row align-items-end">
                                            <div class="form-group col-md-8">
                                                <label for="direccion_destino">Dirección del Cliente <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="direccion_destino" placeholder="Ej.: Av. Siempre Viva 742, Ciudad">
                                                <input type="hidden" id="destino_lat" name="destino_lat">
                                                <input type="hidden" id="destino_lng" name="destino_lng">
                                                <input type="hidden" id="destino_direccion_input" name="destino_direccion">
                                                <input type="hidden" id="distancia_km_input" name="distancia_km">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="d-flex flex-column" style="gap:.5rem">
                                                    <button type="button" id="btn-mi-ubicacion" class="btn btn-primary btn-block">
                                                        <i class="fas fa-location-arrow mr-1"></i>Usar mi ubicación
                                                    </button>
                                                    <button type="button" id="btn-buscar-direccion" class="btn btn-secondary btn-block" type="button">
                                                        <i class="fas fa-search-location mr-1"></i>Buscar y trazar ruta
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="mapa_ruta" style="height: 360px; border-radius: .25rem; overflow: hidden;"></div>
                                        <small class="form-text text-muted mt-2">
                                            La ruta se traza desde la Planta Central — Santa Cruz de la Sierra, Bolivia, hasta el destino del cliente.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen del pedido -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Resumen del Pedido</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Total de Unidades:</strong><br>
                                                <span id="resumen_unidades">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Peso Total:</strong><br>
                                                <span id="resumen_peso">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Precio Total:</strong><br>
                                                <span id="resumen_precio">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Transporte Seleccionado:</strong><br>
                                                <span id="resumen_transporte">-</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <strong>Destino:</strong><br>
                                                <span id="resumen_destino">-</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Distancia Estimada:</strong><br>
                                                <span id="resumen_distancia">-</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <small class="text-muted">Moneda: Bolivianos (BOB)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Crear Envío
                        </button>
                        <a href="{{ route('envios.mis') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Carga robusta de Leaflet local o multi-CDN y callback una sola vez
(function loadLeafletRobust(){
    if(typeof window.__leafletLoading !== 'undefined') return; // evitar doble carga
    window.__leafletLoading = true;
    function addCss(href){
        if(document.querySelector('link[data-leaflet-css]')) return;
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        link.setAttribute('data-leaflet-css','1');
        document.head.appendChild(link);
    }
    function addScript(src, onload, onerror){
        var s = document.createElement('script');
        s.src = src;
        s.async = true;
        s.onload = onload;
        s.onerror = onerror;
        document.body.appendChild(s);
    }
    if(typeof L !== 'undefined') return; // ya cargado por otro sitio
    // Intento local primero
    addCss("{{ asset('vendor/leaflet/leaflet.css') }}");
    var cdns = [
        "{{ asset('vendor/leaflet/leaflet.js') }}",
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js',
        'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js'
    ];
    var i = 0;
    (function tryLoad(){
        if(typeof L !== 'undefined') return; // ya cargó
        if(i >= cdns.length) return; // no más CDNs
        addScript(cdns[i++], function(){}, function(){ tryLoad(); });
        // Si en 1500ms no está, intenta siguiente CDN
        setTimeout(function(){ if(typeof L === 'undefined') tryLoad(); }, 1500);
    })();
})();

$(document).ready(function() {
    function leerItems() {
        const items = [];
        $('#items-container .item-card').each(function(){
            const card = $(this);
            items.push({
                categoria_producto: card.find('.item-categoria').val() || '',
                producto: card.find('.item-producto').val() || '',
                peso_producto_unidad: parseFloat(card.find('.item-peso').val()) || 0,
                unidades_totales: parseInt(card.find('.item-unidades').val()) || 0,
                precio_producto: parseFloat(card.find('.item-precio').val()) || 0
            });
        });
        return items;
    }

    function calcularResumen() {
        const items = leerItems();
        let totalUnidades = 0, totalPeso = 0, totalPrecio = 0;
        let tieneFrio = false;
        for (const it of items) {
            totalUnidades += it.unidades_totales;
            totalPeso += it.peso_producto_unidad * it.unidades_totales;
            totalPrecio += it.precio_producto * it.unidades_totales;
            if (it.categoria_producto === 'alimentos' || it.categoria_producto === 'medicinas') tieneFrio = true;
        }
        $('#resumen_unidades').text(totalUnidades);
        $('#resumen_peso').text(totalPeso.toFixed(2) + ' kg');
        $('#resumen_precio').text('Bs ' + totalPrecio.toFixed(2));

        const sugerido = calcularTransporteSugerido(items, totalPeso);
        resaltarSugerido(sugerido.key);
        const seleccionadoKey = $('#transporte_seleccionado').val();
        $('#resumen_transporte').text(nombreTransporte(seleccionadoKey) || '-');
    }

    function calcularTransporteSugerido(items, pesoTotal) {
        let key = 'camion_pequeno';
        const tieneFrio = items.some(it => it.categoria_producto === 'alimentos' || it.categoria_producto === 'medicinas');
        if (tieneFrio) {
            key = 'camion_refrigerado';
        } else if (pesoTotal > 1000) {
            key = 'camion_grande';
        } else if (pesoTotal > 500) {
            key = 'camion_mediano';
        } else {
            key = 'camion_pequeno';
        }
        const label = nombreTransporte(key);
        $('#transporte_sugerido_text').text(label);
        $('#chip-ayuda').text('Haz clic para seleccionar. Sugerido: ' + label);
        return { key, label };
    }

    function renumerarItems() {
        $('#items-container .item-card').each(function(index){
            const card = $(this);
            card.attr('data-index', index);
            card.find('.item-number').text(index + 1);
            card.find('.item-categoria').attr('name', `items[${index}][categoria_producto]`);
            card.find('.item-producto').attr('name', `items[${index}][producto]`);
            card.find('.item-peso').attr('name', `items[${index}][peso_producto_unidad]`);
            card.find('.item-unidades').attr('name', `items[${index}][unidades_totales]`);
            card.find('.item-precio').attr('name', `items[${index}][precio_producto]`);
        });
        // Mostrar botón eliminar solo si hay más de 1 ítem
        const many = $('#items-container .item-card').length > 1;
        $('#items-container .item-card .btn-remove-item').toggle(many);
    }

    $('#btn-add-item').on('click', function(){
        const index = $('#items-container .item-card').length;
        const $tpl = $(
            `<div class="card card-outline card-secondary mb-3 item-card" data-index="${index}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap:.5rem">
                        <span class="badge badge-secondary">Ítem <span class="item-number">${index+1}</span></span>
                        <small class="text-muted">Ingrese categoría, producto, peso, unidades y precio por unidad</small>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Categoría <span class="text-danger">*</span></label>
                                <select class="form-control item-categoria" name="items[${index}][categoria_producto]" required>
                                    <option value="">Seleccionar categoría...</option>
                                    <option value="alimentos">Alimentos</option>
                                    <option value="medicinas">Medicinas</option>
                                    <option value="electronica">Electrónica</option>
                                    <option value="ropa">Ropa</option>
                                    <option value="otros">Otros</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Producto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control item-producto" name="items[${index}][producto]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Precio por Unidad (Bs) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                    <input type="number" step="0.01" class="form-control item-precio" name="items[${index}][precio_producto]" placeholder="Ej.: 120.50" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Peso por Unidad (kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control item-peso" name="items[${index}][peso_producto_unidad]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número de Unidades <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control item-unidades" name="items[${index}][unidades_totales]" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);
        $('#items-container').append($tpl);
        renumerarItems();
        calcularResumen();
    });

    $(document).on('click', '.btn-remove-item', function(){
        $(this).closest('.item-card').remove();
        renumerarItems();
        calcularResumen();
    });

    $(document).on('input change', '.item-categoria, .item-producto, .item-peso, .item-unidades, .item-precio', calcularResumen);

    // Calcular inicialmente
    renumerarItems();
    calcularResumen();

    function obtenerEnvios() {
        try { return JSON.parse(localStorage.getItem('envios') || '[]'); } catch(e) { return []; }
    }

    function guardarEnvios(envios) {
        localStorage.setItem('envios', JSON.stringify(envios));
    }

    $('#form-crear-envio').on('submit', function(e) {
        e.preventDefault();
        const items = leerItems();
        const pesoTotal = items.reduce((acc, it) => acc + (it.peso_producto_unidad * it.unidades_totales), 0);
        const sugerido = calcularTransporteSugerido(items, pesoTotal);
        let transporteSeleccionadoKey = $('#transporte_seleccionado').val();
        const direccionDestino = $('#direccion_destino').val();
        const destinoLat = parseFloat($('#destino_lat').val()) || null;
        const destinoLng = parseFloat($('#destino_lng').val()) || null;
        const distanciaKm = parseFloat($('#resumen_distancia').data('km')) || null;
        if(!destinoLat || !destinoLng){ alert('Selecciona un destino en el mapa o con el buscador.'); return; }
        if(!transporteSeleccionadoKey){ transporteSeleccionadoKey = sugerido.key; $('#transporte_seleccionado').val(sugerido.key); }
        // Validar que todos los ítems tengan datos
        if(items.length === 0){ alert('Agrega al menos un producto.'); return; }
        for(const it of items){
            if(!it.categoria_producto || !it.producto || it.peso_producto_unidad<=0 || it.unidades_totales<=0 || it.precio_producto<=0){
                alert('Completa todos los campos de cada producto.');
                return;
            }
        }
        // Asignar campos ocultos para enviar al backend
        $('#destino_direccion_input').val(direccionDestino);
        $('#distancia_km_input').val(distanciaKm || '');
        // Enviar al backend
        e.currentTarget.submit();
    });

    // -------------------- Mapa (Leaflet) y ruta demo --------------------
    const planta = { lat: -17.783333, lng: -63.182129, nombre: 'Planta Central — Santa Cruz de la Sierra' };
    let map, markerPlanta, markerDestino, routeLine;
    function initMap(){
        if(map) return;
        map = L.map('mapa_ruta', { zoomControl: true });
        const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' });
        tiles.addTo(map);
        map.setView([planta.lat, planta.lng], 13);
        markerPlanta = L.marker([planta.lat, planta.lng], { title: planta.nombre }).addTo(map).bindPopup('Planta Central').openPopup();
        map.on('click', function(ev){
            const { lat, lng } = ev.latlng;
            setDestino(lat, lng, `Destino seleccionado (${lat.toFixed(5)}, ${lng.toFixed(5)})`);
        });
    }
    // Espera a que Leaflet cargue si llegó tarde por CDN
    (function waitLeaflet(){
        if(typeof L === 'undefined'){ setTimeout(waitLeaflet, 100); return; }
        initMap();
    })();

    function setDestino(lat, lng, label){
        if(markerDestino){ map.removeLayer(markerDestino); }
        markerDestino = L.marker([lat, lng], { title: label }).addTo(map).bindPopup(label);
        trazarRuta([planta.lat, planta.lng], [lat, lng]);
        $('#destino_lat').val(lat);
        $('#destino_lng').val(lng);
        $('#resumen_destino').text(label);
    }

    function trazarRuta(origen, destino){
        if(routeLine){ map.removeLayer(routeLine); }
        const url = `https://router.project-osrm.org/route/v1/driving/${origen[1]},${origen[0]};${destino[1]},${destino[0]}?overview=full&geometries=geojson`;
        fetch(url)
          .then(r=>r.json())
          .then(json=>{
            if(!json || !json.routes || !json.routes.length) throw new Error('no_route');
            const route = json.routes[0];
            const coords = route.geometry.coordinates.map(([lng,lat])=>[lat,lng]);
            routeLine = L.polyline(coords, { color: '#007bff', weight: 4, opacity: 0.9 }).addTo(map);
            map.fitBounds(routeLine.getBounds(), { padding: [20,20] });
            const km = (route.distance||0) / 1000;
            $('#resumen_distancia').text(km.toFixed(1) + ' km').data('km', km);
            animarMarcador(coords);
          })
          .catch(()=>{
            // Fallback: línea recta + Haversine
            routeLine = L.polyline([origen, destino], { color: '#007bff', weight: 4, opacity: 0.85 }).addTo(map);
            map.fitBounds(routeLine.getBounds(), { padding: [20,20] });
            const km = haversine(origen[0], origen[1], destino[0], destino[1]);
            $('#resumen_distancia').text(km.toFixed(1) + ' km').data('km', km);
            animarMarcador([origen, destino]);
          });
    }

    function haversine(lat1, lon1, lat2, lon2){
        const R = 6371; // km
        const toRad = v => v * Math.PI/180;
        const dLat = toRad(lat2-lat1);
        const dLon = toRad(lon2-lon1);
        const a = Math.sin(dLat/2)**2 + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*Math.sin(dLon/2)**2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    let animMarker;
    function animarMarcador(coordList){
        if(animMarker){ map.removeLayer(animMarker); animMarker = null; }
        const start = coordList[0];
        animMarker = L.circleMarker(start, { radius: 6, color: '#28a745', fillColor: '#28a745', fillOpacity: 1 }).addTo(map);
        // Recorre la polyline en segmentos pequeños siguiendo la ruta
        let segIndex = 0;
        let interp = 0;
        const speed = 0.02; // menor = más lento
        const tick = () => {
            if(segIndex >= coordList.length-1){ return; }
            const a = coordList[segIndex];
            const b = coordList[segIndex+1];
            const lat = a[0] + (b[0]-a[0]) * interp;
            const lng = a[1] + (b[1]-a[1]) * interp;
            animMarker.setLatLng([lat,lng]);
            interp += speed;
            if(interp >= 1){ interp = 0; segIndex++; }
            if(segIndex < coordList.length-1){ requestAnimationFrame(tick); }
        };
        requestAnimationFrame(tick);
    }

    // Geocoding básico usando Nominatim (solo para demo; sin clave)
    $('#btn-buscar-direccion').on('click', function(){
        const q = $('#direccion_destino').val();
        if(!q){ alert('Ingresa la dirección del cliente'); return; }
        initMap();
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}`)
            .then(r=>r.json())
            .then(res=>{
                if(!res || !res.length){ alert('No se encontró la dirección'); return; }
                const { lat, lon, display_name } = res[0];
                setDestino(parseFloat(lat), parseFloat(lon), display_name);
                $('#direccion_destino').val(display_name);
            })
            .catch(()=> alert('Error al buscar la dirección'));
    });

    // Geolocalización del navegador para "Usar mi ubicación"
    $('#btn-mi-ubicacion').on('click', function(){
        if(!navigator.geolocation){ alert('Geolocalización no soportada por tu navegador'); return; }
        initMap();
        navigator.geolocation.getCurrentPosition(function(pos){
            const lat = pos.coords.latitude; const lng = pos.coords.longitude;
            // Reverse geocoding para mostrar una etiqueta amigable
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
              .then(r=>r.json())
              .then(data=>{
                  const label = data && data.display_name ? data.display_name : `Mi ubicación (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
                  setDestino(lat, lng, label);
                  $('#direccion_destino').val(label);
              })
              .catch(()=>{
                  const label = `Mi ubicación (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
                  setDestino(lat, lng, label);
              });
        }, function(){ alert('No se pudo obtener tu ubicación'); }, { enableHighAccuracy: true, timeout: 8000 });
    });

    // -------------------- Chips de transporte --------------------
    function nombreTransporte(key){
        const map = {
            camion_pequeno:'Camión Pequeño',
            camion_mediano:'Camión Mediano',
            camion_grande:'Camión Grande',
            camion_refrigerado:'Camión Refrigerado',
            avion_carga:'Avión de Carga',
            barco:'Transporte Marítimo'
        };
        return map[key] || '';
    }
    function resaltarSugerido(arg){
        const keySet = new Set(['camion_pequeno','camion_mediano','camion_grande','camion_refrigerado','avion_carga','barco']);
        let key = null;
        if(keySet.has(arg)){
            key = arg;
        } else {
            const labelToKey = { 'Camión Pequeño':'camion_pequeno', 'Camión Mediano':'camion_mediano', 'Camión Grande':'camion_grande', 'Camión Refrigerado':'camion_refrigerado', 'Avión de Carga':'avion_carga', 'Transporte Marítimo':'barco' };
            key = labelToKey[arg];
        }
        if(!key) return;
        $('.chip-transporte').removeClass('btn-warning');
        $(`.chip-transporte[data-key="${key}"]`).addClass('btn-warning');
    }
    $(document).on('click', '.chip-transporte', function(){
        $('.chip-transporte').removeClass('active btn-primary').addClass('btn-outline-secondary');
        $(this).addClass('active btn-primary').removeClass('btn-outline-secondary btn-warning');
        $('#transporte_seleccionado').val($(this).data('key'));
        calcularResumen();
    });
});
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(function(){
  if(window.flatpickr){
    flatpickr('#fecha_entrega_deseada', {
      enableTime: true,
      time_24hr: true,
      minuteIncrement: 15,
      dateFormat: 'Y-m-d H:i',
      minDate: 'today'
    });
  }
});
</script>
@endsection

