<?php
include('../../..//Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
try {
    $mes_actual = obtenerMesActual();
    $consulta = "SELECT matrices_de_horas.id as Id, usuarios.nombre as 'Nombre del Instructor', matrices_de_horas.tipo_matriz as 'Tipo de Matriz' FROM usuarios 
    INNER JOIN matrices_de_horas ON usuarios.id = matrices_de_horas.id_usuario WHERE matrices_de_horas.mes =:mes";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':mes', $mes_actual, PDO::PARAM_STR);
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
