<?php
require('../fpdf186/fpdf.php');
session_start();
include("../includes/config.php"); // Conectar a la base de datos

// Asegurar que la zona horaria sea la misma que la PC del usuario
date_default_timezone_set('America/Mexico_City');

// Evitar que haya salida antes del PDF
ob_start();

if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
    die("El carrito está vacío.");
}

// **Obtener datos del usuario**
$nombreUsuario = $_POST['nombre'] ?? 'Sin nombre';
$apellidoUsuario = $_POST['apellido'] ?? 'Sin apellido';
$emailUsuario = $_POST['email'] ?? 'Sin correo';

// Definir variable carrito correctamente
$carrito = $_SESSION["carrito"] ?? [];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// **Encabezado**
$pdf->Cell(0,10,"Ticket de Compra",0,1,'C');
$pdf->Ln(5);

// **Datos del usuario**
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"Cliente: $nombreUsuario $apellidoUsuario",0,1);
$pdf->Cell(0,10,"Correo: $emailUsuario",0,1);
$pdf->Cell(0,10,"Fecha: " . date("d/m/Y") . " | Hora: " . date("H:i:s"),0,1);
$pdf->Ln(10);

// **Tabla de productos**
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(30, 144, 255);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(30,10,'Imagen',1,0,'C',true);
$pdf->Cell(60,10,'Producto',1,0,'C',true);
$pdf->Cell(30,10,'Cantidad',1,0,'C',true);
$pdf->Cell(30,10,'Precio',1,0,'C',true);
$pdf->Cell(40,10,'Subtotal',1,1,'C',true);

$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0,0,0);
$total = 0;

foreach ($carrito as $producto) {
    // **Obtener la imagen desde la base de datos**
    $stmt = $conexion->prepare("SELECT imagen FROM productos WHERE nombre = ?");
    $stmt->bind_param("s", $producto["nombre"]);
    $stmt->execute();
    $stmt->bind_result($imagenPath);
    $stmt->fetch();
    $stmt->close();

    if (!file_exists($imagenPath)) {
        $imagenPath = "../assets/media/img/default.png"; // Imagen por defecto
    }

    $nombreProd = utf8_decode($producto["nombre"]);
    $precio = number_format($producto["precio"], 2);
    $cantidad = $producto["cantidad"];
    $subtotal = number_format($producto["precio"] * $cantidad, 2);
    $total += $producto["precio"] * $cantidad;

    // **Insertar imagen en el PDF**
    $y = $pdf->GetY();
    $x = $pdf->GetX();
    $pdf->Cell(30,25,'',1,0,'C');
    $pdf->Image($imagenPath, $x + 2, $y + 2, 26, 20);
    $pdf->Cell(60,25,$nombreProd,1,0,'C');
    $pdf->Cell(30,25,$cantidad,1,0,'C');
    $pdf->Cell(30,25,"$" . $precio,1,0,'C');
    $pdf->Cell(40,25,"$" . $subtotal,1,1,'C');

    // **Registrar la venta en la base de datos con fecha de compra**
    $fechaCompra = date("Y-m-d H:i:s"); // Obtener fecha y hora exacta
    $stmtVenta = $conexion->prepare("INSERT INTO venta (nombre, cantidad, precio_total, fecha) VALUES (?, ?, ?, ?)");
    $precio_total = $producto["precio"] * $producto["cantidad"];
    $stmtVenta->bind_param("sids", $producto["nombre"], $producto["cantidad"], $precio_total, $fechaCompra);
    $stmtVenta->execute();
    $stmtVenta->close();
}

// **Total final**
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(220, 53, 69);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(150,10,'Total:',1,0,'R',true);
$pdf->Cell(40,10,'$' . number_format($total, 2),1,1,'C',true);

// **Limpiar buffer antes de generar PDF**
ob_end_clean();

$pdf->Output('I', 'ticket.pdf');

// **Vaciar el carrito después de la compra**
$_SESSION["carrito"] = [];
?>
