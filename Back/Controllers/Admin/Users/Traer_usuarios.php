<?php
include('../../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
try {
    $consulta = "SELECT id as Id, nombre as Nombre, rol as Rol, tipo as Tipo FROM usuarios";
    $stmt = $conexion->prepare($consulta);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $mensaje = array(
        "data" => $data
    );
    echo json_encode($mensaje);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
