<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$correo_electronico = $_POST['correo'];
$pass = $_POST['password'];
try {
    $consulta = "SELECT id, nombre, email, pass, rol FROM usuarios WHERE email = :email";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':email', $correo_electronico, PDO::PARAM_STR);
    $stmt->execute();

    $info_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$info_db || !password_verify($pass, $info_db[0]['pass'])) {
        $mensaje = array(
            'code' => '404',
            'icon' => 'error',
            'message' => 'Usuario o contraseña incorrectos.'
        );
        echo json_encode($mensaje);
        exit;
    }
    session_start();
    session_regenerate_id(true);
    $_SESSION['acceso'] = true;
    $_SESSION['id_usuario'] = $info_db[0]['id'];
    $_SESSION['usuario'] = $info_db[0]['nombre'];
    echo json_encode([
        'code' => '200',
        'icon' => 'success',
        'message' => 'Bienvenido ' . $info_db[0]['nombre'],
        'rol' => $info_db[0]['rol']
    ]);
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
