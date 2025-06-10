<?php
include("../includes/config.php");

$sqlProductos = "SELECT id, nombre, precio, stock, especificaciones, imagen FROM productos";
$resultProductos = $conexion->query($sqlProductos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #e0f7fa, #b3e5fc);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            overflow-x: auto;
        }
        h1 {
            text-align: center;
            color: #0288d1;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0288d1;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Productos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Especificaciones</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultProductos->num_rows > 0) {
                    while ($row = $resultProductos->fetch_assoc()) {
                        $rutaImagen = !empty($row['imagen']) ? "../assets/media/img/" . $row['imagen'] : "../assets/media/img/no-image.png";
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                        echo "<td>" . $row['stock'] . "</td>";
                        echo "<td>" . $row['especificaciones'] . "</td>";
                        echo "<td><img src='" . $rutaImagen . "' alt='Producto'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$conexion->close();
?>
