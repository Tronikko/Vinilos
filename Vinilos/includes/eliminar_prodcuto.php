<?php
include("../includes/config.php"); // Conectar a la base de datos

if (isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];

    // **Eliminar el producto de la base de datos**
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id_producto);

    if ($stmt->execute()) {
        echo "✅ Producto eliminado correctamente.";
    } else {
        echo "❌ Error al eliminar el producto.";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "❌ No se recibió el ID del producto.";
}
?>
