<?php
require('../fpdf186/fpdf.php');
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';
require '../vendor/PHPMailer/src/Exception.php';
include("../includes/config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$usuario = $_POST['usuario'] ?? '';
$emailAdmin = $_POST['email'] ?? '';
$fechaInicio = $_POST['fecha_inicio'] ?? '';
$fechaFin = $_POST['fecha_fin'] ?? '';
$graficaBase64 = $_POST['grafica'] ?? '';

// **Convertir y guardar la gráfica**
if (!empty($graficaBase64)) {
    $graficaBase64 = str_replace('data:image/png;base64,', '', $graficaBase64);
    $graficaDecoded = base64_decode($graficaBase64);
    file_put_contents('grafica.png', $graficaDecoded);
}

// **Consultar datos de ventas según el rango de fechas**
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

// **Generar el reporte PDF con la gráfica y las fechas seleccionadas**
ob_start();
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Desde: $fechaInicio Hasta: $fechaFin", 0, 1, 'C');
$pdf->Ln(10);

// **Incluir la gráfica en el PDF**
if (file_exists('grafica.png')) {
    $pdf->Image('grafica.png', 10, 50, 190);
}

// **Tabla de productos vendidos**
$pdf->Ln(80);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Producto', 1);
$pdf->Cell(40, 10, 'Total Vendido', 1, 1);

$pdf->SetFont('Arial', '', 10);
foreach ($ventas as $producto => $cantidad) {
    $pdf->Cell(100, 10, utf8_decode($producto), 1);
    $pdf->Cell(40, 10, $cantidad, 1, 1);
}

$pdfPath = "reporte_ventas.pdf";
$pdf->Output("F", $pdfPath);
ob_end_clean();

// **Enviar el reporte al correo del administrador**
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'chipicrist@gmail.com'; 
    $mail->Password = 'lpnb khdb zpmv hhdz';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('chipicrist@gmail.com', 'Vinyl Records');
    $mail->addAddress($emailAdmin);
    $mail->Subject = "Reporte de Ventas - Vinyl Records";
    $mail->Body = "Hola $usuario,\nAdjunto encontraras el reporte de ventas en formato PDF.";

    $mail->addAttachment($pdfPath); // Adjuntar el PDF generado

    $mail->send();
    echo "<script>alert('✅ Reporte enviado por correo a $emailAdmin'); window.location.href='admin.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('❌ Error al enviar el reporte: {$mail->ErrorInfo}'); window.location.href='admin.php';</script>";
}
?>
