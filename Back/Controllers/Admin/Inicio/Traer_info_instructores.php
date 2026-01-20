<?php
include('../../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
try {
    $mes_actual = obtenerMesActual();
    $consulta = "SELECT
    usuarios.id AS Id,
    usuarios.nombre AS 'Nombre del Instructor',

    COUNT(DISTINCT CASE 
        WHEN matrices_de_horas.tipo_matriz IN ('Evento de 1 Hora', 'Evento de 2 HoraS', 'Evento de 6 Horas') 
        THEN matrices_de_horas.tipo_matriz 
        ELSE NULL 
    END) AS 'Matrices Hechas',

    COALESCE((
        SELECT SUM(mh.total_horas)
        FROM matrices_de_horas mh
        WHERE mh.id_usuario = usuarios.id
            AND mh.mes =:mes
            AND mh.tipo_matriz = 'Evento de 1 Hora'
    ), 0) AS 'Horas evento 1',

    COALESCE((
        SELECT SUM(mh.total_horas)
        FROM matrices_de_horas mh
        WHERE mh.id_usuario = usuarios.id
            AND mh.mes =:mes
            AND mh.tipo_matriz = 'Evento de 2 Horas'
    ), 0) AS 'Horas evento 2',

    COALESCE((
        SELECT SUM(mh.total_horas)
        FROM matrices_de_horas mh
        WHERE mh.id_usuario = usuarios.id
            AND mh.mes =:mes
            AND mh.tipo_matriz = 'Evento de 6 Horas'
    ), 0) AS 'Horas evento 3',

    SUM(matrices_de_horas.total_horas) AS 'Total de Horas'

    FROM matrices_de_horas
    INNER JOIN usuarios ON matrices_de_horas.id_usuario = usuarios.id

    WHERE matrices_de_horas.mes =:mes AND usuarios.rol = 'Instructor'

    GROUP BY usuarios.id, usuarios.nombre
    ORDER BY usuarios.nombre";
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
