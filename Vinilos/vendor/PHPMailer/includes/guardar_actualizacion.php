<?php
include("../includes/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $conexion->prepare("UPDATE usuarios SET usuario = ?, password = ?, rol = ? WHERE id = ?");
    $stmt->bind_param("sssi", $usuario, $password, $rol, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario actualizado correctamente'); window.location.href='../pages/admin.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar usuario'); window.location.href='../pages/admin.php';</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
