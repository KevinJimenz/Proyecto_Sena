<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$id_registro = $_POST['id_registro'];
try {
    $consulta = "SELECT
    codigo_ficha as 'Codigo de Ficha', 
    nombre_programa as 'Nombre de Programa', 
    dias_mes as Dias, 
    rango_horas as 'Horas Programadas', 
    actividad_aprendizaje as 'Actividad de Aprendizaje', 
    resultados_aprendizaje as 'Resultados de Aprendizaje',
    CASE
		WHEN matrices_de_horas.tipo_matriz = 'Evento de 1 Hora' THEN 1
		WHEN matrices_de_horas.tipo_matriz = 'Evento de 2 Horas' THEN 2
		WHEN matrices_de_horas.tipo_matriz = 'Evento de 6 Horas' THEN 3
    END AS Tipo
    FROM detalle_matriz INNER JOIN matrices_de_horas ON detalle_matriz.id_matriz = matrices_de_horas.id 
    WHERE detalle_matriz.id = :id_registro";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        "data" => $data
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
