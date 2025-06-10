<?php
include("../includes/config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del producto
    $stmt = $conexion->prepare("SELECT nombre, precio, stock, especificaciones, imagen FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nombre, $precio, $stock, $especificaciones, $imagen);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "<script>alert('ID de producto no encontrado'); window.location.href='../pages/admin.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $especificaciones = $_POST["especificaciones"];
    
    // Manejo de imagen
    if (!empty($_FILES["imagen"]["name"])) {
        $directorio = "C:/xampp/htdocs/TiendaVinilos/assets/media/img/";
        $archivo = $directorio . basename($_FILES["imagen"]["name"]);
        $rutaImagen = "../assets/media/img/" . basename($_FILES["imagen"]["name"]);

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $archivo)) {
            $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ?, especificaciones = ?, imagen = ? WHERE id = ?");
            $stmt->bind_param("sdissi", $nombre, $precio, $stock, $especificaciones, $rutaImagen, $id);
        } else {
            echo "<script>alert('Error al subir la imagen'); window.location.href='actualizar_producto.php?id=" . $id . "';</script>";
            exit();
        }
    } else {
        $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ?, especificaciones = ? WHERE id = ?");
        $stmt->bind_param("sdisi", $nombre, $precio, $stock, $especificaciones, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Producto actualizado correctamente'); window.location.href='../pages/admin.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar producto'); window.location.href='actualizar_producto.php?id=" . $id . "';</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #e0f7fa, #b3e5fc);
            text-align: center;
            padding: 20px;
        }

        .form-container {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            margin: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            color: white;
        }

        .btn-save {
            background-color: #4caf50;
        }

        .btn-save:hover {
            background-color: #388e3c;
        }

        .btn-back {
            background-color: #f44336;
        }

        .btn-back:hover {
            background-color: #d32f2f;
        }

        img {
            max-width: 100px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Actualizar Producto</h1>
        <form action="actualizar_producto.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>

            <label>Precio:</label>
            <input type="number" name="precio" step="0.01" value="<?php echo $precio; ?>" required>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?php echo $stock; ?>" required>

            <label>Especificaciones:</label>
            <textarea name="especificaciones" required><?php echo $especificaciones; ?></textarea>

            <label>Imagen Actual:</label>
            <br>
            <img src="<?php echo $imagen; ?>">
            <br>
            <label>Cambiar Imagen:</label>
            <input type="file" name="imagen" accept="image/*">

            <button type="submit" class="btn-save">Guardar Cambios</button>
            <a href="../pages/admin.php"><button type="button" class="btn-back">Regresar</button></a>
        </form>
    </div>
</body>
</html>
