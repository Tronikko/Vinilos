<?php
include("../includes/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    if (empty($usuario) || empty($password)) {
        echo "<script>alert('Por favor, completa todos los campos'); window.location.href='index.php';</script>";
        exit();
    }

    // Consultar contraseña y rol del usuario
    $stmt = $conexion->prepare("SELECT password, rol FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashedPassword, $rol);
    $stmt->fetch();

    $operacion = "fallido"; // Inicialmente "fallido" por defecto

    if ($stmt->num_rows > 0) {
        // Verificar la contraseña
        if (password_verify($password, $hashedPassword)) {
            $operacion = "exitoso"; // Cambia a "exitoso" si la autenticación es correcta
            if ($rol == 'administrador') {
                echo "<script>alert('Bienvenido Administrador'); window.location.href='admin.php';</script>";
            } else {
                echo "<script>alert('Bienvenido Usuario'); window.location.href='tienda.php';</script>";
            }
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href='index.php';</script>";
    }

    // Registrar el intento en la bitácora
    $stmtBitacora = $conexion->prepare("INSERT INTO bitacora (usuario, operacion) VALUES (?, ?)");
    $stmtBitacora->bind_param("ss", $usuario, $operacion);
    $stmtBitacora->execute();

    $stmtBitacora->close();
    $stmt->close();
    $conexion->close();
}
?>
