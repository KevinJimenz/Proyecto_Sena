<?php
include('../../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$nombre_usuario = $_POST['nombre_usuario'];
$correo_usuario = $_POST['correo_usuario'];
$password_usuario = $_POST['password_usuario'];
$hash = password_hash($password_usuario, PASSWORD_DEFAULT);
$tipo = $_POST['tipo'];
$rol =  "Instructor";
try {
    $consulta = "SELECT COUNT(*) as Cantidad FROM usuarios WHERE email =:correo_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':correo_usuario', $correo_usuario, PDO::PARAM_STR);
    $stmt->execute();
    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($response[0]['Cantidad'] > 0) {
        echo json_encode([
            'icon' => 'error',
            'message' => 'Este correo ya se encuentra registrado.',
            'time' => 2800
        ]);
        exit;
    }
    $consulta = "INSERT INTO usuarios (nombre, email, pass, rol, tipo) VALUES(:nombre, :correo, :pass, :rol, :tipo)";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':nombre', $nombre_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $hash, PDO::PARAM_STR);
    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->execute();
    echo json_encode([
        'icon' => 'success',
        'message' => 'Usuario creado exitosamente.',
        'time' => 2700
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
