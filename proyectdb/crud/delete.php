<?php
require_once("../conexion.php");

if (isset($_POST['borrar'])) {
    $cedula = trim($_POST['cedula']);

    try {
        $consulta = "DELETE FROM cliente WHERE cedula = :cedula";
        $sql = $connect->prepare($consulta);
        $sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $message = "Registro eliminado exitosamente.";
        } else {
            $message = "No se pudo eliminar el registro.";
        }
    } catch (PDOException $e) {
        $message = "Error al eliminar el registro: " . $e->getMessage();
    }

    // Redirigir a read.php con el mensaje
    header("Location: read.php?message=" . urlencode($message));
    exit();
}
