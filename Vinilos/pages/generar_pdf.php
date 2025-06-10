<?php
require('../fpdf186/fpdf.php');
include("../includes/config.php");

$fechaInicio = $_POST['fecha_inicio'] ?? '';
$fechaFin = $_POST['fecha_fin'] ?? '';
$grafica = $_POST['grafica'] ?? '';

// Consultar datos de ventas
$stmt = $conexion->prepare("SELECT nombre, SUM(cantidad) AS total_vendido FROM venta WHERE fecha BETWEEN ? AND ? GROUP BY nombre");
$stmt->bind_param("ss", $fechaInicio, $fechaFin);
$stmt->execute();
$resultado = $stmt->get_result();

$ventas = [];
while ($row = $resultado->fetch_assoc()) {
    $ventas[$row["nombre"]] = $row["total_vendido"];
}
$stmt->close();
$conexion->close();

$pdf = new FPDF();
$pdf->AddPage();

// **Título y fechas**
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Desde: $fechaInicio Hasta: $fechaFin", 0, 1, 'C');
$pdf->Ln(10);

// **Insertar imagen de la gráfica**
if (!empty($grafica)) {
    $grafica = str_replace('data:image/png;base64,', '', $grafica);
    $grafica = base64_decode($grafica);
    file_put_contents('grafica.png', $grafica);

    $pdf->Image('grafica.png', 10, 50, 190);
}

$pdf->Output();
?>
