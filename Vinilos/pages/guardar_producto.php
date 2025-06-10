<?php
include("../includes/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $especificaciones = $_POST["especificaciones"];

    // Manejo de imagen
    $directorio = "C:/xampp/htdocs/TiendaVinilos/assets/media/img/";
    $archivo = $directorio . basename($_FILES["imagen"]["name"]);
    $rutaImagen = "../assets/media/img/" . basename($_FILES["imagen"]["name"]);

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $archivo)) {
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, stock, especificaciones, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiss", $nombre, $precio, $stock, $especificaciones, $rutaImagen);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Producto agregado correctamente'); window.location.href='../pages/admin.php';</script>";
    } else {
        echo "<script>alert('Error al subir la imagen'); window.location.href='../pages/admin.php';</script>";
    }
}

$conexion->close();
?>
