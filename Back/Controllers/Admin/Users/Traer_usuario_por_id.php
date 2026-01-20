<?php
include('../../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$id_usuario = $_POST['id_usuario'];
try {
    $consulta = "SELECT nombre as Nombre, email as Correo, tipo as Tipo FROM usuarios WHERE id=:id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
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
