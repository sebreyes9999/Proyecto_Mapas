<?php
require_once("../conexion.php");

// Inicializar variables
$cedula = $nombre = $apellido = $direccion = $latitud = $longitud = "";
$errors = [];
$message = "";

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula'])) {
        $cedula = trim($_POST['cedula']);
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $latitud = $_POST['latitud'] ?? '';
        $longitud = $_POST['longitud'] ?? '';

        try {
            // Preparar la consulta SQL para actualizar el cliente
            $sql = "UPDATE cliente SET nombre = :nombre, apellido = :apellido, direccion = :direccion, latitud = :latitud, longitud = :longitud WHERE cedula = :cedula";
            $stmt = $connect->prepare($sql);

            // Bind de los parámetros
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':latitud', $latitud, PDO::PARAM_STR);
            $stmt->bindParam(':longitud', $longitud, PDO::PARAM_STR);
            $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $message = "Cliente actualizado exitosamente.";
                // Redirigir al archivo read.php
                header("Location: read.php");
                exit(); // Asegúrate de que el script se detenga después de redirigir
            } else {
                $errors[] = "Error al actualizar el cliente.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error en la actualización: " . $e->getMessage();
        }
    } else {
        $errors[] = "Cédula no especificada en POST.";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cedula'])) {
    // Recuperar datos del cliente para mostrar en el formulario
    $cedula = trim($_GET['cedula']);

    try {
        // Preparar la consulta SQL para obtener el cliente
        $sql = "SELECT * FROM cliente WHERE cedula = :cedula";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            $nombre = $cliente['nombre'];
            $apellido = $cliente['apellido'];
            $direccion = $cliente['direccion'];
            $latitud = $cliente['latitud'];
            $longitud = $cliente['longitud'];
        } else {
            $errors[] = "Cliente no encontrado.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error al recuperar datos del cliente: " . $e->getMessage();
    }
} else {
    $errors[] = "Cédula no especificada en GET.";
}
?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            /* Ajusta la altura según sea necesario */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4">Actualizar Cliente</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($cedula); ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label" required>Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($nombre); ?>">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label" required>Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required value="<?php echo htmlspecialchars($apellido); ?>">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label" required>Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required value="<?php echo htmlspecialchars($direccion); ?>">
            </div>
            <div class="mb-3">
                <label for="map" class="form-label" required>Ubicación</label>
                <div id="map"></div>
                <input type="hidden" id="latitud" name="latitud" value="<?php echo htmlspecialchars($latitud); ?>">
                <input type="hidden" id="longitud" name="longitud" value="<?php echo htmlspecialchars($longitud); ?>">
            </div>
            <button type="submit" name="enviar" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;

        function initMap() {
            // Obtener las coordenadas desde los campos ocultos
            const lat = parseFloat(document.getElementById('latitud').value) || 4.611;
            const lng = parseFloat(document.getElementById('longitud').value) || -74.082;

            // Crear el mapa centrado en la ubicación del cliente
            map = L.map('map').setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Crear un marcador en la ubicación del cliente
            marker = L.marker([lat, lng]).addTo(map);

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