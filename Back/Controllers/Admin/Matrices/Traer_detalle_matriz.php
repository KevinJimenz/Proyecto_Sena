<?php
include('../../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$id_matriz = $_POST['id_matriz'];
try {
    $consulta = "SELECT detalle_matriz.codigo_ficha as 'Codigo de Ficha', detalle_matriz.nombre_programa as 'Nombre del Programa', detalle_matriz.dias_mes as Dias, detalle_matriz.rango_horas as 'Rango de Horas', detalle_matriz.actividad_aprendizaje as 'Actividad de Aprendizaje', detalle_matriz.resultados_aprendizaje as 'Resultados de Aprendizaje' FROM matrices_de_horas 
    INNER JOIN detalle_matriz ON matrices_de_horas.id = detalle_matriz.id_matriz WHERE matrices_de_horas.id =:id_matriz";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_matriz', $id_matriz, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'data' => $data,
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
