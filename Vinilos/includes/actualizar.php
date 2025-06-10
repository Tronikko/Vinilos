<?php
include("../includes/config.php");

// Obtener el ID del usuario desde la URL
$id = $_GET['id'];

// Consultar los datos del usuario seleccionado
$stmt = $conexion->prepare("SELECT usuario, rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Usuario no encontrado'); window.location.href='../pages/admin.php';</script>";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #a6c1ee, #fbc2eb);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .update-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        .update-container h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .update-container label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
            font-weight: bold;
        }
        .update-container input, .update-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .update-container button {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .update-container button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <div class="update-container">
        <h1>Actualizar Usuario</h1>
        <form action="guardar_actualizacion.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="usuario">Nombre de Usuario</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo $row['usuario']; ?>" required>
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Ingresa una nueva contraseña" required>
            <label for="rol">Rol de Usuario</label>
            <select id="rol" name="rol" required>
                <option value="usuario" <?php echo $row['rol'] == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                <option value="administrador" <?php echo $row['rol'] == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
            </select>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
<?php
$conexion->close();
?>
