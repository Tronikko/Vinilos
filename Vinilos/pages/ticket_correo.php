<?php
require('../fpdf186/fpdf.php');
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';
require '../vendor/PHPMailer/src/Exception.php';
include("../includes/config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
$emailUsuario = $_POST['email'] ?? '';

if (empty($emailUsuario)) {
    echo "<script>alert('❌ No se encontró un correo válido.'); window.location.href='carrito.php';</script>";
    exit;
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "Ticket de Compra", 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Fecha: " . date("d/m/Y") . " | Hora: " . date("H:i:s"), 0, 1);
$pdf->Ln(5);

// **Encabezado de la tabla**
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(30, 144, 255);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(30, 10, 'Imagen', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Producto', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Precio', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0,0,0);
$total = 0;

// **Agregar cada producto con imagen**
foreach ($_SESSION["carrito"] as $producto) {
    $stmt = $conexion->prepare("SELECT imagen FROM productos WHERE nombre = ?");
    $stmt->bind_param("s", $producto["nombre"]);
    $stmt->execute();
    $stmt->bind_result($imagenPath);
    $stmt->fetch();
    $stmt->close();

    if (!file_exists($imagenPath)) {
        $imagenPath = "../assets/media/img/default.png";
    }

    $nombreProd = utf8_decode($producto["nombre"]);
    $precio = number_format($producto["precio"], 2);
    $cantidad = $producto["cantidad"];
    $subtotal = number_format($producto["precio"] * $cantidad, 2);
    $total += $producto["precio"] * $cantidad;

    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->Cell(30, 25, '', 1, 0, 'C');
    $pdf->Image($imagenPath, $x + 2, $y + 2, 26, 20);
    $pdf->Cell(60, 25, $nombreProd, 1, 0, 'C');
    $pdf->Cell(30, 25, $cantidad, 1, 0, 'C');
    $pdf->Cell(30, 25, "$" . $precio, 1, 0, 'C');
    $pdf->Cell(40, 25, "$" . $subtotal, 1, 1, 'C');

    $fechaCompra = date("Y-m-d H:i:s");
    $stmtVenta = $conexion->prepare("INSERT INTO venta (nombre, cantidad, precio_total, fecha) VALUES (?, ?, ?, ?)");
    $precio_total = $producto["precio"] * $producto["cantidad"];
    $stmtVenta->bind_param("sids", $producto["nombre"], $producto["cantidad"], $precio_total, $fechaCompra);
    $stmtVenta->execute();
    $stmtVenta->close();
}

// **Total final**
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(220, 53, 69);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(150, 10, 'Total:', 1, 0, 'R', true);
$pdf->Cell(40, 10, '$' . number_format($total, 2), 1, 1, 'C', true);

// Limpiar buffer antes de generar PDF
ob_end_clean();

$pdfPath = "ticket.pdf";
$pdf->Output("F", $pdfPath);

// **Vaciar el carrito después de la compra**
$_SESSION["carrito"] = [];

// **Enviar el ticket por correo**
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
    $mail->addAddress($emailUsuario);
    $mail->Subject = "Tu Ticket de Compra en Vinyl Records";
    $mail->Body = "Hola, gracias por tu compra en Vinyl Records.\nAdjunto encontraras el archivo PDF con los detalles de tu compra.";
    $mail->addAttachment($pdfPath);

    $mail->send();
    echo "<script>alert('✅ Ticket enviado por correo a $emailUsuario'); window.location.href='carrito.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('❌ Error al enviar el correo: {$mail->ErrorInfo}'); window.location.href='carrito.php';</script>";
}
?>
