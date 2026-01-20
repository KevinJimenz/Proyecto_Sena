<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$codigo_ficha = $_POST['codigo_ficha'];
try {
    $consulta = "SELECT nombre_ficha as resultado
    FROM fichas WHERE codigo_ficha = :codigo
    UNION
    SELECT 'No existe' as resultado
    WHERE NOT EXISTS (SELECT 1 FROM fichas WHERE codigo_ficha = :codigo)";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':codigo', $codigo_ficha, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $mensaje = array(
        'info' => $data
    );
    echo json_encode($mensaje);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
