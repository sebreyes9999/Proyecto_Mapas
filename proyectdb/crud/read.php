<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>READ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <h1 class="my-4">READ</h1>

    <?php
    // Incluir archivo de conexión
    require_once("../conexion.php");

    // Mostrar mensaje si existe
    if (isset($_GET['message'])) {
      $message = htmlspecialchars($_GET['message']);
      echo "<div class='alert alert-info'>$message</div>";
    }

    // Definir la consulta SQL
    $sql = "SELECT * FROM cliente";

    try {
      // Preparar la consulta
      $query = $connect->prepare($sql);

      // Ejecutar la consulta
      $query->execute();

      // Verificar si se encontraron resultados
      if ($query->rowCount() > 0) {
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead class='thead-dark'>";
        echo "<tr>";
        echo "<th>Cédula</th>";
        echo "<th>Nombre</th>";
        echo "<th>Apellido</th>";
        echo "<th>Dirección</th>";
        echo "<th>Latitud</th>";
        echo "<th>Longitud</th>";
        echo "<th>Editar & Borrar</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($results = $query->fetchAll(PDO::FETCH_OBJ) as $result) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($result->cedula) . "</td>";
          echo "<td>" . htmlspecialchars($result->nombre) . "</td>";
          echo "<td>" . htmlspecialchars($result->apellido) . "</td>";
          echo "<td>" . htmlspecialchars($result->direccion) . "</td>";
          echo "<td>" . (isset($result->latitud) ? htmlspecialchars($result->latitud) : 'N/A') . "</td>";
          echo "<td>" . (isset($result->longitud) ? htmlspecialchars($result->longitud) : 'N/A') . "</td>";
          echo "<td>";

          // Formulario de edición
          echo "<form method='GET' action='update.php' style='display:inline;'>";
          echo "<input type='hidden' name='cedula' value='" . htmlspecialchars($result->cedula) . "'>";
          echo "<button type='submit' name='editar' class='btn btn-warning'>Editar</button>";
          echo "</form>";

          // Formulario de borrado
          echo "<form method='POST' action='delete.php' style='display:inline;'>";
          echo "<input type='hidden' name='cedula' value='" . htmlspecialchars($result->cedula) . "'>";
          echo "<button type='submit' name='borrar' class='btn btn-danger'>Borrar</button>";
          echo "</form>";

          echo "</td>";
          echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
      } else {
        echo "<p>No se encontraron registros en la tabla 'cliente'.</p>";
      }
    } catch (PDOException $e) {
      echo "Error al ejecutar la consulta: " . $e->getMessage();
    }
    ?>

    <!-- Botón para redirigir a index.php en la carpeta pages -->
    <div class="my-4">
      <a href="../pages/index.php" class="btn btn-primary">Volver al Inicio</a>
    </div>
  </div>
</body>

</html>