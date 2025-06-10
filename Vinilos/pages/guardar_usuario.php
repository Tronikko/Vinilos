<?php
include("../includes/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol']; // Obtener el rol seleccionado

    if (empty($usuario) || empty($password) || empty($rol)) {
        echo "<script>alert('Por favor, completa todos los campos'); window.location.href='registro.php';</script>";
        exit();
    }

    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $password, $rol);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado correctamente'); window.location.href='../pages/index.php';</script>";
    } else {
        echo "<script>alert('Error al registrar usuario'); window.location.href='registro.php';</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>
