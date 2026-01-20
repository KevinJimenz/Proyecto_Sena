<?php
include('../../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$id_usuario = $_POST['id_usuario'];
try {
    // Valido si el usuario tiene matrices registradas
    $consulta = "SELECT 
    CASE 
    	WHEN COUNT(*) >= 1 THEN 1 
        WHEN COUNT(*) = 0 THEN 0
    END AS resultado FROM matrices_de_horas 
    INNER JOIN usuarios ON matrices_de_horas.id_usuario = usuarios.id WHERE usuarios.id =:id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    $existen_matrices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $consulta = "SELECT COUNT(*) as Cantidad FROM usuarios WHERE rol = 'Administrador'";
    $stmt = $conexion->prepare($consulta);
    $stmt->execute();
    $cantidad_admin = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $consulta = "SELECT rol as Rol FROM usuarios WHERE id =:id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $rol_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cantidad_admin[0]['Cantidad'] == 1 and $rol_usuario[0]['Rol'] == 'Administrador') {
        echo json_encode([
            'icon' => 'error',
            'message' => 'No se puede eliminar al ultimo Administrador.',
            'time' => 2800
        ]);
        exit;
    }

    if ($existen_matrices[0]['resultado'] == 1) {
        // Elimino todos los registros de todas las matrices que esten asociadas al usuario
        $consulta = "DELETE detalle_matriz FROM detalle_matriz INNER JOIN matrices_de_horas ON detalle_matriz.id_matriz = matrices_de_horas.id 
        WHERE matrices_de_horas.id_usuario =:id_usuario";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        // Elimino todas las matrices del usuario
        $consulta = "DELETE FROM matrices_de_horas WHERE id_usuario =:id_usuario";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        // Elimino a el usuario
        $consulta = "DELETE FROM usuarios WHERE id =:id_usuario";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode([
            'icon' => 'success',
            'message' => 'Usuario eliminado exitosamente.',
            'time' => 2700
        ]);
        exit;
    }

    $consulta = "DELETE FROM usuarios WHERE id =:id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode([
        'icon' => 'success',
        'message' => 'Usuario eliminado exitosamente.',
        'time' => 2700
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
