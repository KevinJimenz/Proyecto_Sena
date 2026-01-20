<?php
include('../../Models/conexion.php');
$conexion = new Conexion();
$conexion->conectar();
session_start();
$id_usuario = $_SESSION['id_usuario'];
$id_registro = $_POST['id_registro'];
try {
    $consulta = "SELECT COUNT(*) AS Cantidad
    FROM detalle_matriz INNER JOIN matrices_de_horas ON detalle_matriz.id_matriz = matrices_de_horas.id 
    WHERE matrices_de_horas.id_usuario =:id_usuario";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    $cantidad_registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cantidad_registros[0]['Cantidad'] > 1) {
        // Traer el detalle del registro
        $restar_horas = 0;
        $consulta = "SELECT
        matrices_de_horas.id as Id,
        detalle_matriz.dias_mes as Dias, 
        matrices_de_horas.tipo_matriz as Tipo,
        matrices_de_horas.total_horas as 'Total Horas' FROM detalle_matriz INNER JOIN matrices_de_horas ON detalle_matriz.id_matriz = matrices_de_horas.id 
        WHERE detalle_matriz.id = :id_registro";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
        $stmt->execute();

        $detalle_registro = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $restar_horas = horasParaRestar($detalle_registro[0]['Dias'], $detalle_registro[0]['Tipo']);
        $nuevo_total = $detalle_registro[0]['Total Horas'] - $restar_horas;
        $consulta = "UPDATE matrices_de_horas SET total_horas =:total WHERE id =:id_matriz";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':total', $nuevo_total, PDO::PARAM_INT);
        $stmt->bindParam(':id_matriz', $detalle_registro[0]['Id'], PDO::PARAM_INT);
        $stmt->execute();

        $consulta = "DELETE FROM detalle_matriz WHERE id =:id_registro";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode([
            'icon' => 'success',
            'message' => 'Registro eliminado exitosamente',
            'time' => 2700
        ]);
        exit;
    }

    // Traer el ID de la matriz
    $consulta = "SELECT matrices_de_horas.id as Id FROM matrices_de_horas 
    INNER JOIN detalle_matriz ON matrices_de_horas.id = detalle_matriz.id_matriz WHERE detalle_matriz.id =:id_registro";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
    $stmt->execute();
    $id_matriz = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Elimino el ultimo registro
    $consulta = "DELETE FROM detalle_matriz WHERE id =:id_registro";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_registro', $id_registro, PDO::PARAM_INT);
    $stmt->execute();

    // Elimino la matriz
    $consulta = "DELETE FROM matrices_de_horas WHERE id=:id_matriz";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':id_matriz', $id_matriz[0]['Id'], PDO::PARAM_INT);
    $stmt->execute();
    
    echo json_encode([
        'icon' => 'success',
        'message' => 'Registro y matriz eliminados exitosamente',
        'time' => 2700
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conexion->desconectar();
}

function horasParaRestar($dias, $tipo)
{
    $horas = 0;
    if (is_string($dias)) {
        $dias = array_map('intval', explode(',', $dias));
    }

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
