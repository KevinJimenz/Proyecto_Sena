<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
session_start();
$nombre_usuario = $_SESSION['usuario'];
$old_password = md5($_POST['old-password']);
$new_password = md5($_POST['new-password']);
try {
    $consulta = "SELECT pass FROM usuarios WHERE nombre = :nombre";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':nombre', $nombre_usuario, PDO::PARAM_STR);
    $stmt->execute();

    $info_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($old_password != $info_db[0]['pass']) {
        $mensaje = array(
            'code' => '401', // Contraseña incorrecta (no autorizado)
            'icon' => 'error',
            'message' => 'La constraseña no coincide con la que esta registrada'
        );
        echo json_encode($mensaje);
        exit;
    }
    $consulta = "UPDATE usuarios SET pass =:new_pass WHERE nombre =:nombre";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':new_pass', $new_password, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre_usuario, PDO::PARAM_STR);
    $stmt->execute();
    $mensaje = array(
        'code' => '200',
        'icon' => 'success',
        'message' => 'Contraseña actualizada exitosamente.'
    );
    echo json_encode($mensaje);
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
