<?php
include("../includes/config.php");// Ruta actualizada para config.php dentro de includes

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario eliminado correctamente'); window.location.href='../pages/admin.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar usuario'); window.location.href='../pages/admin.php';</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
