<?php
session_start();

if (isset($_POST['indice'])) {
    $indice = $_POST['indice'];

    if (isset($_SESSION['carrito'][$indice])) {
        // Devolvemos el stock al eliminar (si estás manejando stock)
        include("../includes/config.php");

        $producto = $_SESSION['carrito'][$indice];
        $nombre = $producto['nombre'];
        $cantidad = $producto['cantidad'];

        $sql = "UPDATE productos SET stock = stock + ? WHERE nombre = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("is", $cantidad, $nombre);
        $stmt->execute();
        $stmt->close();
        $conexion->close();

        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar array
    }
}

header("Location: carrito.php");
exit;
