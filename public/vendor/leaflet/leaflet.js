/* Leaflet loader shim - this file lazy-loads Leaflet from CDN if not present.
   It exists so that asset('vendor/leaflet/leaflet.js') returns 200 even si no está en disco.
   Si este archivo es llamado, intentará cargar desde un CDN. */
(function(){
  if (typeof L !== 'undefined') return;
  var s = document.createElement('script');
  s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
  s.async = true;
  document.body.appendChild(s);
})();

