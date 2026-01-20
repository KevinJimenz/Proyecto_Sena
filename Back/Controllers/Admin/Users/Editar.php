<?php
include('../../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$id_usuario = $_POST['id_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$correo_usuario = $_POST['correo_usuario'];
$tipo = $_POST['tipo'];
try {
    $consulta = "UPDATE usuarios SET nombre = :nombre, email = :correo, tipo = :tipo WHERE id = :id";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':nombre', $nombre_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode([
        'icon' => 'success',
        'message' => 'Usuario actualizado exitosamente.',
        'time' => 2700
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
