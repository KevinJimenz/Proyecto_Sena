<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
session_start();
$id_usuario = $_SESSION['id_usuario'];
$id_registro = $_POST['id_registro'];
$codigo_ficha = $_POST['codigo_ficha'];
$nombre_programa = $_POST['nombre_programa'];
$dias = $_POST['dias'];
$horas = $_POST['horas'];
$actividad = $_POST['actividad'];
$resultados = $_POST['resultados'];
try {
    $consulta = "SELECT tipo as Tipo FROM usuarios WHERE id = :id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $tipo_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Valido el rango de horas para que no cruce con otro registro
    list($hora_inicio, $hora_fin) = descomponerRango($horas);
    $clausula_find = construirClausulaFind($dias, 'dias_mes');
    $consulta = "SELECT
        CASE
            WHEN EXISTS (
            SELECT 1
            FROM detalle_matriz
            INNER JOIN matrices_de_horas ON id_matriz = matrices_de_horas.id
            INNER JOIN usuarios ON matrices_de_horas.id_usuario = usuarios.id
            WHERE matrices_de_horas.id_usuario != :id_usuario
                AND ($clausula_find)
                AND NOT (
                STR_TO_DATE(:hora_inicio, '%l%p') >= STR_TO_DATE(
                    CONCAT(
                    SUBSTRING_INDEX(SUBSTRING_INDEX(rango_horas, '-', 2), '-', -1),
                    SUBSTRING_INDEX(rango_horas, '-', -1)
                    ),'%l%p')
                OR
                STR_TO_DATE(:hora_fin, '%l%p') <= STR_TO_DATE(
                    CONCAT(
                    SUBSTRING_INDEX(rango_horas, '-', 1),
                    SUBSTRING_INDEX(rango_horas, '-', -1)
                    ),'%l%p')
                )
                AND (
                    (:tipo_usuario != 'Instructor Tecnico' OR usuarios.tipo = 'Instructor Tecnico')
                )
            )
            THEN 'Hay cruce'
            ELSE 'No hay cruce'
        END AS resultado";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_usuario', $tipo_usuario[0]['Tipo'], PDO::PARAM_STR);
    $stmt->execute();
    $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($respuesta[0]['resultado'] == 'Hay cruce') {
        echo json_encode([
            'icon' => 'error',
            'message' => 'Horario Cruzado',
            'time' => 2700,
        ]);
        exit;
    }

    $consulta = "SELECT dias_mes as Dias, 
    matrices_de_horas.tipo_matriz as Tipo,
    matrices_de_horas.total_horas as 'Total Horas',
    COUNT(*) as 'Cantidad Registros'
    FROM detalle_matriz INNER JOIN matrices_de_horas ON detalle_matriz.id_matriz = matrices_de_horas.id 
    WHERE detalle_matriz.id =:id_registro";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
    $stmt->execute();

    $detalle_registro = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($detalle_registro[0]['Cantidad Registros'] > 1) {
        $cantidad_horas = obtenerCantidadHoras($detalle_registro[0]['Dias'], $detalle_registro[0]['Tipo']); // obtengo la cantidad de horas que tenia el registro antes de editar
        $total_horas = $detalle_registro[0]['Total Horas'] - $cantidad_horas; // le quito las horas del registro editado
        $total_horas = abs($total_horas); // convierto el resultado en numero positivo
        $cantidad_horas = obtenerCantidadHoras($dias, $detalle_registro[0]['Tipo']); // obtengo la nueva cantidad de horas que va a tener el registro
        $nuevo_total = $total_horas + $cantidad_horas; // le sumo la nueva cantidad de horas 
    } else {
        $nuevo_total = obtenerCantidadHoras($dias, $detalle_registro[0]['Tipo']);
    }

    $consulta = "UPDATE matrices_de_horas SET total_horas =:total_horas WHERE id_usuario=:id";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':total_horas', $nuevo_total, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    $dias = rtrim($dias, ",");
    $dias_array = array_filter(explode(',', $dias), function ($dia) {
        return trim($dia) !== '';
    });
    $dias = implode(',', $dias_array);

    $consulta = "UPDATE detalle_matriz SET codigo_ficha=:codigo, nombre_programa=:nombre, 
    dias_mes=:dias, rango_horas=:horas, actividad_aprendizaje=:actividad, resultados_aprendizaje=:resultados WHERE id = :id_registro";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':codigo', $codigo_ficha, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre_programa, PDO::PARAM_STR);
    $stmt->bindParam(':dias', $dias, PDO::PARAM_STR);
    $stmt->bindParam(':horas', $horas, PDO::PARAM_STR);
    $stmt->bindParam(':actividad', $actividad, PDO::PARAM_STR);
    $stmt->bindParam(':resultados', $resultados, PDO::PARAM_STR);
    $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode([
        'icon' => 'success',
        'message' => 'Registro editado exitosamente.',
        'time' => 2700
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}
function construirClausulaFind($valores, $columna)
{
    $valores = rtrim($valores, ",");
    $dias_array = array_filter(explode(',', $valores), function ($dia) {
        return trim($dia) !== '';
    });
    $find_in_set = [];
    foreach ($dias_array as $valor) {
        $find_in_set[] = "FIND_IN_SET('$valor', $columna) > 0";
    }
    return implode(' OR ', $find_in_set);
}
function descomponerRango($rango)
{
    $periodo = substr($rango, -2);
    $horas = substr($rango, 0, -2);
    list($inicio, $fin) = explode('-', $horas);
    return [$inicio . $periodo, $fin . $periodo];
}
function obtenerCantidadHoras($dias, $tipo)
{
    $horas = 0;
    if (is_string($dias)) {
        $dias = array_map('intval', explode(',', $dias));
    }
    $dias = array_filter($dias);
    switch ($tipo) {
        case "Evento de 1 Hora":
            for ($i = 0; $i < count($dias); $i++) {
                $horas += 1;
            }
            break;
        case "Evento de 2 Horas":
            for ($i = 0; $i < count($dias); $i++) {
                $horas += 2;
            }
            break;
        case "Evento de 6 Horas":
            for ($i = 0; $i < count($dias); $i++) {
                $horas += 6;
            }
            break;
    }
    return $horas;
}
