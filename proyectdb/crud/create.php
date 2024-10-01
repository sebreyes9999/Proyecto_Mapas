<?php
require_once("../conexion.php");

if (isset($_POST["enviar"])) {
  $cedula = $_POST['cedula'];
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $direccion = $_POST['direccion'];
  $latitud = $_POST['latitud'];
  $longitud = $_POST['longitud'];

  $sql = "INSERT INTO cliente (cedula, nombre, apellido, direccion, latitud, longitud) 
            VALUES (:cedula, :nombre, :apellido, :direccion, :latitud, :longitud)";

  $stmt = $connect->prepare($sql);
  $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
  $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
  $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
  $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
  $stmt->bindParam(':latitud', $latitud, PDO::PARAM_STR);
  $stmt->bindParam(':longitud', $longitud, PDO::PARAM_STR);

  if ($stmt->execute()) {
    header('Location: ../crud/read.php');;
  } else {
    echo "Error al guardar el registro.";
  }
}
