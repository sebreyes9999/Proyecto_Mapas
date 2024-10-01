<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4">Formulario de Registro</h1>
        <form method="post" action="../crud/create.php">
            <input type="hidden" name="accion" value="editar">
            <div class="mb-3">
                <label for="cedula" class="form-label" required>Cédula</label>
                <input type="text" class="form-control" id="cedula" name="cedula" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label" required>Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label" required>Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label" required>Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="map" class="form-label">Ubicación</label>
                <div id="map"></div>
                <input type="hidden" id="latitud" name="latitud">
                <input type="hidden" id="longitud" name="longitud">
            </div>
            <button type="submit" name="enviar" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;

        function initMap() {
            // Coordenadas de Bogotá
            const bogotaLat = 4.611;
            const bogotaLng = -74.082;

            // Crear el mapa centrado en Bogotá
            map = L.map('map').setView([bogotaLat, bogotaLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Crear un marcador en la ubicación predeterminada
            marker = L.marker([bogotaLat, bogotaLng]).addTo(map);

            // Actualizar los campos ocultos con las coordenadas
            document.getElementById("latitud").value = bogotaLat;
            document.getElementById("longitud").value = bogotaLng;

            // Manejar clics en el mapa para colocar el marcador
            map.on('click', function(e) {
                placeMarker(e.latlng);
            });
        }

        function placeMarker(location) {
            marker.setLatLng(location);
            document.getElementById("latitud").value = location.lat;
            document.getElementById("longitud").value = location.lng;
        }

        // Inicializa el mapa cuando la página se carga
        window.onload = initMap;
    </script>
</body>

</html>