<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
try {
    $mes_actual = obtenerMesActual();
    session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $consulta = "SELECT detalle_matriz.id as Id, 
    codigo_ficha as Codigo, 
    nombre_programa as Programa, 
    dias_mes as Dias, 
    rango_horas as 'Rango Horas', 
    actividad_aprendizaje as Actividad, 
    resultados_aprendizaje as Resultados
    FROM detalle_matriz INNER JOIN matrices_de_horas ON detalle_matriz.id_matriz = matrices_de_horas.id 
    WHERE matrices_de_horas.mes = :mes and matrices_de_horas.id_usuario = :id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':mes', $mes_actual, PDO::PARAM_STR);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'data' => $data
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
function obtenerMesActual()
{
    $mes_actual = "";
    $meses = [
        "01" => "Enero",
        "02" => "Febrero",
        "03" => "Marzo",
        "04" => "Abril",
        "05" => "Mayo",
        "06" => "Junio",
        "07" => "Julio",
        "08" => "Agosto",
        "09" => "Septiembre",
        "10" => "Octubre",
        "11" => "Noviembre",
        "12" => "Diciembre",
    ];
    $numero_mes = date("m");
    $mes_actual = $meses[$numero_mes];
    return $mes_actual;
}