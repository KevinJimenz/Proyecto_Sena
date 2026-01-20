<?php
// Cargar las dependencias necesarias de PhpSpreadsheet
include('../../../../Libs/Excel/vendor/autoload.php');

// Incluir el archivo de conexión a la base de datos
include('../../../Models/conexion.php');

// Crear una nueva instancia de conexión
$conexion = new Conexion();
$conexion->conectar(); // Conectarse a la base de datos

$id_matriz = $_GET['id_matriz'];

// Importar las clases necesarias del paquete PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Consulta para obtener los datos desde la base de datos
$consulta = "SELECT usuarios.nombre as 'Nombre del Instructor', 
    matrices_de_horas.mes as 'Mes',
    matrices_de_horas.tipo_matriz as 'Tipo de Matriz',
    matrices_de_horas.total_horas as 'Cantidad de Horas', 
    detalle_matriz.codigo_ficha as 'Codigo de Ficha', 
    detalle_matriz.nombre_programa as 'Nombre del Programa', 
    detalle_matriz.dias_mes as 'Dias del Mes', 
    detalle_matriz.rango_horas as 'Rango de Horas', 
    detalle_matriz.actividad_aprendizaje as 'Actividad de Aprendizaje', 
    detalle_matriz.resultados_aprendizaje as 'Resultado de Aprendizaje'
    FROM matrices_de_horas 
    INNER JOIN usuarios ON matrices_de_horas.id_usuario = usuarios.id 
    INNER JOIN detalle_matriz ON matrices_de_horas.id = detalle_matriz.id_matriz
    WHERE matrices_de_horas.id =:id_matriz
    ORDER BY matrices_de_horas.tipo_matriz";
$stmt = $conexion->prepare($consulta); // Preparar la consulta SQL
$stmt ->bindParam(':id_matriz', $id_matriz, PDO::PARAM_INT);
$stmt->execute(); // Ejecutar la consulta

// Obtener todos los resultados como un arreglo asociativo
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Definir el nombre del archivo Excel a descargar
$nombre_archivo = 'Instructor_' . $data[0]['Nombre del Instructor'] . '.xlsx';

// Definir estilos para la cabecera (negrita + fondo verde)
$styleHead = [
    'font' => ['bold' => true], // Texto en negrita
    'fill' => [ // Relleno del fondo
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => '90f790'] // Color de fondo verde claro
    ],
];

// Crear una nueva hoja de cálculo
$excel = new Spreadsheet();
$excel->getProperties()
    ->setCreator("Coordinador Wilfred")
    ->setDescription("Este archivo contiene el reporte completo del instructor: " . $data[0]['Nombre del Instructor'] );
$hojaActiva = $excel->getActiveSheet(); // Obtener la hoja activa
$hojaActiva->setTitle("Reporte Instructor " . $data[0]['Nombre del Instructor'] ); // Título de la hoja

// Definir los títulos de las columnas en la fila 1
$hojaActiva->setCellValue('A1', 'Nº');
$hojaActiva->setCellValue('B1', 'Nombre del Instructor');
$hojaActiva->setCellValue('C1', 'Mes');
$hojaActiva->setCellValue('D1', 'Tipo de Matriz');
$hojaActiva->setCellValue('E1', 'Cantidad de Horas');
$hojaActiva->setCellValue('F1', 'Codigo de Ficha');
$hojaActiva->setCellValue('G1', 'Nombre del Programa');
$hojaActiva->setCellValue('H1', 'Dias del Mes');
$hojaActiva->setCellValue('I1', 'Rango de Horas');
$hojaActiva->setCellValue('J1', 'Actividad de Aprendizaje');
$hojaActiva->setCellValue('K1', 'Resultados de Aprendizaje');

// Aplicar estilo a la cabecera de la tabla (fila 1)
$hojaActiva->getStyle('A1:K1')->applyFromArray($styleHead);

// Centrar horizontalmente el texto en la cabecera
$hojaActiva->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Establecer ancho personalizado para las columnas B y C
$hojaActiva->getColumnDimension('A')->setWidth(10);
$hojaActiva->getColumnDimension('B')->setWidth(25);
$hojaActiva->getColumnDimension('C')->setWidth(15);
$hojaActiva->getColumnDimension('D')->setWidth(25);
$hojaActiva->getColumnDimension('E')->setWidth(25);
$hojaActiva->getColumnDimension('F')->setWidth(25);
$hojaActiva->getColumnDimension('G')->setWidth(25);
$hojaActiva->getColumnDimension('H')->setWidth(25);
$hojaActiva->getColumnDimension('I')->setWidth(25);
$hojaActiva->getColumnDimension('J')->setWidth(25);
$hojaActiva->getColumnDimension('K')->setWidth(25);

// Variable para saber en qué fila escribir (comienza en la 2)
$fila = 2;
$id = 1; // Contador para enumerar las filas

// Recorrer los datos obtenidos de la base de datos
foreach ($data as $row) {
    // Centrar horizontalmente el contenido de las celdas de la fila actual
    $hojaActiva->getStyle('A' . $fila . ':' . 'K' . $fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Colocar el número de fila (contador)
    $hojaActiva->setCellValue('A' . $fila, $id);

    // Colocar el nombre del usuario
    $hojaActiva->setCellValue('B' . $fila, $row['Nombre del Instructor']);
    $hojaActiva->setCellValue('C' . $fila, $row['Mes']);
    $hojaActiva->setCellValue('D' . $fila, $row['Tipo de Matriz']);
    $hojaActiva->setCellValue('E' . $fila, $row['Cantidad de Horas']);
    $hojaActiva->setCellValue('F' . $fila, $row['Codigo de Ficha']);
    $hojaActiva->setCellValue('G' . $fila, $row['Nombre del Programa']);
    $hojaActiva->setCellValue('H' . $fila, $row['Dias del Mes']);
    $hojaActiva->setCellValue('I' . $fila, $row['Rango de Horas']);
    $hojaActiva->setCellValue('J' . $fila, $row['Actividad de Aprendizaje']);
    $hojaActiva->setCellValue('K' . $fila, $row['Resultado de Aprendizaje']);

    $id++; // Incrementar el contador
    $fila++; // Pasar a la siguiente fila
}

// Asegúrate de que no hay salida previa
if (ob_get_length()) ob_end_clean();

header('Content-Encoding: UTF-8');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"{$nombre_archivo}\"");
header('Cache-Control: max-age=0');

// Crear el archivo Excel y enviarlo como descarga
$write = IOFactory::createWriter($excel, 'Xlsx');
$write->save('php://output'); // Salida directa al navegador
exit;