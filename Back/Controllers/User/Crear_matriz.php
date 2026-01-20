<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
$registros = [];
$nuevos_dias = [];
$codigos_ficha = [];
session_start();
$id_usuario = $_SESSION['id_usuario'];
foreach ($_POST['fila'] as $fila) {
    $registros[] = [
        'codigo_ficha' => $fila['codigo_ficha'],
        'nombre_programa' => $fila['nombre_programa'],
        'dia_del_mes' => $fila['dia_del_mes'],
        'rango_horas' => $fila['rango_horas'],
        'actividad' => $fila['actividad'],
        'resultados' => $fila['resultados']
    ];
    $dias = array_map('intval', explode(',', $fila['dia_del_mes']));
    $nuevos_dias = array_merge($nuevos_dias, $dias);
    $codigos_ficha[] = $fila['codigo_ficha'];
}
$mes_actual = obtenerMesActual();
$tipo_matriz = obtenerTipoMatriz();
try {
    // Validar que el Codigo de ficha no se repita en el mismo evento
    for ($i = 0; $i < count($codigos_ficha); $i++) {
        $consulta = "SELECT CASE WHEN COUNT(*) > 0 THEN 'Existe' ELSE 'No existe' END AS resultado FROM matrices_de_horas 
        INNER JOIN detalle_matriz ON matrices_de_horas.id = detalle_matriz.id_matriz WHERE detalle_matriz.codigo_ficha =:codigo_ficha AND matrices_de_horas.tipo_matriz =:tipo";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':codigo_ficha', $codigos_ficha[$i], PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo_matriz, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado[0]['resultado'] == 'Existe') {
            echo json_encode([
                'icon' => 'error',
                'message' => 'Esta ficha ya fue ingresada en este evento: ' . $codigos_ficha[$i],
                'time' => 2700,
            ]);
            exit;
        }
    }

    $consulta = "SELECT tipo as Tipo FROM usuarios WHERE id = :id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $tipo_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Valido si se repiten los dias con otro instructor
    for ($i = 0; $i < count($registros); $i++) {
        list($hora_inicio, $hora_fin) = descomponerRango($registros[$i]['rango_horas']);
        $clausula_find = construirClausulaFind($registros[$i]['dia_del_mes'], 'dias_mes');
        $consulta = "SELECT
         CASE
            -- Si el usuario actual es Tecnico, valida cruce con Tecnicos
            WHEN :tipo_usuario = 'Instructor Tecnico' AND EXISTS (
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
                AND usuarios.tipo = 'Instructor Tecnico'
            ) THEN 'Hay cruce con un Instructor Tecnico'

            -- Si el usuario actual es Transversal, valida cruce con Transversales
            WHEN :tipo_usuario = 'Instructor Transversal' AND EXISTS (
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
                AND usuarios.tipo = 'Instructor Transversal'
            ) THEN 'Hay cruce de Horario'

            ELSE 'No hay cruce'
        END AS resultado";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
        $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario[0]['Tipo'], PDO::PARAM_STR);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($respuesta[0]['resultado'] == 'Hay cruce con un Instructor Tecnico' or  $respuesta[0]['resultado'] == 'Hay cruce de Horario') {
            echo json_encode([
                'icon' => 'error',
                'message' => $respuesta[0]['resultado'],
                'time' => 2700,
            ]);
            exit;
        }
    }

    // Valido si se repiten los dias con el mismo usuario
    for ($i = 0; $i < count($registros); $i++) {
        list($hora_inicio, $hora_fin) = descomponerRango($registros[$i]['rango_horas']);
        $clausula_find = construirClausulaFind($registros[$i]['dia_del_mes'], 'dias_mes');
        $consulta = "SELECT
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM detalle_matriz
                    INNER JOIN matrices_de_horas ON id_matriz = matrices_de_horas.id
                    WHERE matrices_de_horas.id_usuario = :id_usuario
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
                ) THEN 1
                ELSE 0
            END AS resultado";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
        $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($respuesta[0]['resultado'] == 1) {
            echo json_encode([
                'icon' => 'error',
                'message' => 'Hay cruce con otro registro',
                'time' => 2700,
            ]);
            exit;
        }
    }

    // Valido si la suma de todas las matrices registradas es igual 160
    $consulta = "SELECT CASE WHEN SUM(total_horas) = 160 THEN 'Cantidad Maxima de Horas' END as 'Resultado' FROM matrices_de_horas WHERE id_usuario=:id";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $validacion_total_horas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($validacion_total_horas[0]['Resultado'] == 'Cantidad Maxima de Horas') {
        echo json_encode([
            'icon' => 'error',
            'message' => 'Solo puedes registrar 160 Horas por Mes',
            'time' => 2700,
        ]);
        exit;
    }

    $consulta = "SELECT CASE WHEN COUNT(*) > 0 THEN 'Existe' ELSE 'No existe' END AS resultado FROM matrices_de_horas WHERE id_usuario=:id AND mes=:mes AND tipo_matriz=:tipo";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':mes', $mes_actual, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo_matriz, PDO::PARAM_STR);
    $stmt->execute();
    $existencia_matriz = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($existencia_matriz[0]['resultado'] == "Existe") {

        $consulta = "SELECT id AS id_matriz, tipo_matriz FROM matrices_de_horas WHERE id_usuario=:id AND mes=:mes AND tipo_matriz=:tipo";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':mes', $mes_actual, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo_matriz, PDO::PARAM_STR);
        $stmt->execute();

        $info_matriz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $id_matriz = $info_matriz[0]['id_matriz'];
        $tipo_matriz_existente = $info_matriz[0]['tipo_matriz'];

        // Traigo los registros que esten registrados a esta matriz 
        $consulta = "SELECT GROUP_CONCAT(DISTINCT detalle_matriz.dias_mes ORDER BY detalle_matriz.dias_mes ASC SEPARATOR ',') AS Dias, matrices_de_horas.tipo_matriz AS Tipo, matrices_de_horas.total_horas AS 'Total Horas', COUNT(*) AS 'Cantidad Registros' FROM detalle_matriz INNER JOIN matrices_de_horas ON detalle_matriz.id_matriz = matrices_de_horas.id WHERE detalle_matriz.id_matriz =:id_matriz";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_matriz', $id_matriz, PDO::PARAM_INT);
        $stmt->execute();
        $detalle_matriz = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Valido si hay registros, capturo el total de horas ya registradas y le sumo las nuevas horas ingresadas
        if ($detalle_matriz[0]['Cantidad Registros'] != 0) {
            $dias_registrados = obtenerDias($detalle_matriz);
            $cantidad_horas_registradas = obtenerTotalHoras($detalle_matriz[0]['Tipo'], $dias_registrados);
        } else {
            $cantidad_horas_registradas = 0;
        }
        $nuevo_total_horas = obtenerTotalHoras($tipo_matriz, $nuevos_dias);
        $nuevo_total_horas = $nuevo_total_horas + $cantidad_horas_registradas;
        $consulta = "UPDATE matrices_de_horas SET total_horas=:total_horas WHERE id_usuario=:id_usuario and id=:id_matriz";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':total_horas', $nuevo_total_horas, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_matriz', $id_matriz, PDO::PARAM_INT);
        $stmt->execute();
        for ($i = 0; $i < count($registros); $i++) {
            $registros[$i]['dia_del_mes'] = rtrim($registros[$i]['dia_del_mes'], ",");
            $dias_array = array_filter(explode(',', $registros[$i]['dia_del_mes']), function ($dia) {
                return trim($dia) !== '';
            });
            $registros[$i]['dia_del_mes'] = implode(',', $dias_array);
            $consulta = "INSERT INTO detalle_matriz (id_matriz, codigo_ficha, nombre_programa, dias_mes, rango_horas, actividad_aprendizaje, resultados_aprendizaje) 
            VALUES (:id, :codigo, :nombre, :dias, :rango, :actividad, :resultados)";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':id', $id_matriz, PDO::PARAM_INT);
            $stmt->bindParam(':codigo', $registros[$i]['codigo_ficha'], PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $registros[$i]['nombre_programa'], PDO::PARAM_STR);
            $stmt->bindParam(':dias', $registros[$i]['dia_del_mes'], PDO::PARAM_STR);
            $stmt->bindParam(':rango', $registros[$i]['rango_horas'], PDO::PARAM_STR);
            $stmt->bindParam(':actividad', $registros[$i]['actividad'], PDO::PARAM_STR);
            $stmt->bindParam(':resultados', $registros[$i]['resultados'], PDO::PARAM_STR);
            $stmt->execute();
        }
        echo json_encode([
            'icon' => 'success',
            'message' => 'Registros agregados exitosamente',
            'time' => 2700,
        ]);
        exit;
    }

    $total_horas = obtenerTotalHoras($tipo_matriz, $nuevos_dias);
    $consulta = "INSERT INTO matrices_de_horas (id_usuario, mes, tipo_matriz, total_horas) VALUES (:id_usuario, :mes, :tipo_matriz, :total_horas)";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':mes', $mes_actual, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_matriz', $tipo_matriz, PDO::PARAM_STR);
    $stmt->bindParam(':total_horas', $total_horas, PDO::PARAM_INT);
    $stmt->execute();

    $consulta = "SELECT id AS Id FROM matrices_de_horas ORDER BY id DESC LIMIT 1";
    $stmt = $conexion->prepare($consulta);
    $stmt->execute();

    $id_matriz = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($registros); $i++) {
        $registros[$i]['dia_del_mes'] = rtrim($registros[$i]['dia_del_mes'], ",");
        $dias_array = array_filter(explode(',', $registros[$i]['dia_del_mes']), function ($dia) {
            return trim($dia) !== '';
        });
        $registros[$i]['dia_del_mes'] = implode(',', $dias_array);
        $consulta = "INSERT INTO detalle_matriz (id_matriz, codigo_ficha, nombre_programa, dias_mes, rango_horas, actividad_aprendizaje, resultados_aprendizaje) VALUES (:id, :codigo, :nombre, :dias, :rango, :actividad, :resultados)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id_matriz[0]['Id'], PDO::PARAM_INT);
        $stmt->bindParam(':codigo', $registros[$i]['codigo_ficha'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $registros[$i]['nombre_programa'], PDO::PARAM_STR);
        $stmt->bindParam(':dias', $registros[$i]['dia_del_mes'], PDO::PARAM_STR);
        $stmt->bindParam(':rango', $registros[$i]['rango_horas'], PDO::PARAM_STR);
        $stmt->bindParam(':actividad', $registros[$i]['actividad'], PDO::PARAM_STR);
        $stmt->bindParam(':resultados', $registros[$i]['resultados'], PDO::PARAM_STR);
        $stmt->execute();
    }
    echo json_encode([
        'icon' => 'success',
        'message' => 'Matriz creada exitosamente',
        'time' => 2700,
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

function obtenerTipoMatriz()
{
    $evento_matriz = $_POST['tipo_matriz'];
    switch ($evento_matriz) {
        case 1:
            $tipo = "Evento de 1 Hora";
            break;
        case 2:
            $tipo = "Evento de 2 Horas";
            break;
        case 3:
            $tipo = "Evento de 6 Horas";
            break;
    }
    return $tipo;
}

function obtenerTotalHoras($tipo_matriz, $nuevo_dias)
{
    $acumulador = 0;
    $nuevo_dias = array_filter($nuevo_dias);
    switch ($tipo_matriz) {
        case "Evento de 1 Hora":
            for ($i = 0; $i < count($nuevo_dias); $i++) {
                $acumulador += 1;
            }
            break;
        case "Evento de 2 Horas":
            for ($i = 0; $i < count($nuevo_dias); $i++) {
                $acumulador += 2;
            }
            break;
        case "Evento de 6 Horas":
            for ($i = 0; $i < count($nuevo_dias); $i++) {
                $acumulador += 6;
            }
            break;
    }
    return $acumulador;
}

function obtenerDias($detalle_matriz)
{
    $dias_registrados = [];
    foreach ($detalle_matriz as $registro) {
        $dias = array_map('trim', explode(',', $registro['Dias']));
        $dias_registrados = array_merge($dias_registrados, $dias);
    }
    sort($dias_registrados, SORT_NUMERIC);
    return $dias_registrados;
}
