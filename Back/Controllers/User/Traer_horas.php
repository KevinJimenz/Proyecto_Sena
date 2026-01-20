<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
try {
    $mes_actual = obtenerMesActual();
    session_start();
    $id_usuario = $_SESSION['id_usuario'];
    $consulta = "SELECT
    COALESCE((SELECT total_horas FROM matrices_de_horas WHERE mes=:mes  AND id_usuario =:id_usuario AND tipo_matriz = 'Evento de 1 Hora' LIMIT 1), 0) AS 'Evento 1',
    COALESCE((SELECT total_horas FROM matrices_de_horas WHERE mes=:mes  AND id_usuario =:id_usuario AND tipo_matriz = 'Evento de 2 Horas' LIMIT 1), 0) AS 'Evento 2',
    COALESCE((SELECT total_horas FROM matrices_de_horas WHERE mes=:mes  AND id_usuario =:id_usuario AND tipo_matriz = 'Evento de 6 Horas' LIMIT 1), 0) AS 'Evento 3'";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':mes', $mes_actual, PDO::PARAM_STR);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
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