<?php
include("../includes/config.php");

// Procesar el formulario para agregar producto
if (isset($_POST['agregar_producto'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $especificaciones = $_POST['especificaciones'];
    
    // Procesar imagen
    $imagenNombre = $_FILES['imagen']['name'];
    $imagenTmp = $_FILES['imagen']['tmp_name'];
    $rutaDestino = "../assets/media/img/" . basename($imagenNombre);

    if (move_uploaded_file($imagenTmp, $rutaDestino)) {
        $rutaDB = "./../assets/media/img/" . basename($imagenNombre);

        $sqlInsert = "INSERT INTO productos (nombre, precio, stock, especificaciones, imagen) 
                      VALUES ('$nombre', '$precio', '$stock', '$especificaciones', '$rutaDB')";
        if ($conexion->query($sqlInsert)) {
            echo "<script>alert('Producto agregado correctamente');</script>";
        } else {
            echo "<script>alert('Error al agregar producto: " . $conexion->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error al subir imagen');</script>";
    }
}


// Consultar datos de usuarios
$sqlUsuarios = "SELECT id, usuario, password, rol FROM usuarios";
$resultUsuarios = $conexion->query($sqlUsuarios);

// Consultar datos de productos
$sqlProductos = "SELECT id, nombre, precio, stock, especificaciones, imagen FROM productos";
$resultProductos = $conexion->query($sqlProductos);


// Consultar datos de bitácora
$sqlBitacora = "SELECT id, usuario, fecha, operacion FROM bitacora";
$resultBitacora = $conexion->query($sqlBitacora);

// Consultar datos de ventas (agrupadas por fecha)
$sqlVentas = "SELECT DATE(fecha) AS fecha, SUM(precio_total) AS total FROM venta GROUP BY DATE(fecha)";
$resultVentas = $conexion->query($sqlVentas);
$ventas = [];

while ($row = $resultVentas->fetch_assoc()) {
    $ventas[$row["fecha"]] = $row["total"];
}

// Inicializamos las fechas
$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin = $_GET['fecha_fin'] ?? '';
$ventas = [];

if (!empty($fechaInicio) && !empty($fechaFin)) {
    $stmt = $conexion->prepare("SELECT nombre, SUM(cantidad) AS total_vendido FROM venta WHERE fecha BETWEEN ? AND ? GROUP BY nombre");
    $stmt->bind_param("ss", $fechaInicio, $fechaFin);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($row = $resultado->fetch_assoc()) {
        $ventas[$row["nombre"]] = $row["total_vendido"];
    }

    $stmt->close();
}




?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
         body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            display: flex;
            background: linear-gradient(to right, #e0f7fa, #b3e5fc);
        }

        .sidebar {
            width: 250px;
            background-color: #0288d1;
            color: white;
            height: 100%;
            padding: 20px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
            position: fixed;
            display: flex;
            flex-direction: column;
        }

        .menu {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .menu a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #0277bd;
        }

        .logout {
            background-color: #f44336;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        .logout:hover {
            background-color: #d32f2f;
        }

        .content {
            margin-left: 270px;
            padding: 30px;
            width: calc(100% - 270px);
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .table-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #0288d1;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .hidden {
            display: none;
        }

        /* Estilos del formulario para agregar productos */
        .formulario {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin-bottom: 20px;
        }

        .formulario input, .formulario button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .formulario button {
            background-color: #0288d1;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .formulario button:hover {
            background-color: #0277bd;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.table-container').forEach(section => section.classList.add('hidden'));
            document.getElementById(sectionId).classList.remove('hidden');
        }
    </script>
    <script>
    function confirmarEliminacion(url) {
        if (confirm("¿Está seguro de que desea eliminar este usuario?")) {
            window.location.href = url;
        }
    }
</script>
<script>
        function showSection(sectionId) {
            document.querySelectorAll('.table-container').forEach(section => section.classList.add('hidden'));
            document.getElementById(sectionId).classList.remove('hidden');
        }

        function confirmarEliminacion(url) {
            if (confirm("¿Está seguro de que desea eliminar este elemento?")) {
                window.location.href = url;
            }
        }

        function generarPDF() {
            const { jsPDF } = window.jspdf;
            html2canvas(document.getElementById('grafica')).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF();
                pdf.addImage(imgData, 'PNG', 10, 10, 190, 100);
                pdf.save('reporte_ventas.pdf');
            });
        }
</script>


</head>
<body>
    <div class="sidebar">
        <div class="menu">
            <h2>Menú</h2>
            <a href="#" onclick="showSection('usuarios')">Usuarios</a>
            <a href="#" onclick="showSection('productos')">Productos</a>
            <a href="#" onclick="showSection('bitacora')">Bitácora</a>
            <a href="#" onclick="showSection('reportes')">Reportes</a>
            <a href="../pages/index.php" class="logout">Cerrar Sesión</a>
        </div>
    </div>

    <div class="content">
    <div id="usuarios" class="table-container">
    <h1>Tabla de Usuarios</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th class="password-column">Contraseña</th>
                <th>Rol</th>
                <th class="action-buttons">Actualizar</th>
                <th class="action-buttons">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultUsuarios->num_rows > 0) {
                while ($row = $resultUsuarios->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['usuario'] . "</td>";
                    echo "<td class='password-column'>" . $row['password'] . "</td>";
                    echo "<td>" . $row['rol'] . "</td>";
                    
                    // Botón para actualizar usuario
                    echo "<td class='action-buttons'>
                            <a href='../includes/actualizar.php?id=" . $row['id'] . "' class='btn btn-edit'>Actualizar</a>
                          </td>";

                    // Botón para eliminar usuario con confirmación
                    echo "<td class='action-buttons'>
                            <button class='btn btn-delete' onclick='confirmarEliminacion(\"../includes/eliminar_usuario.php?id=" . $row['id'] . "\")'>Eliminar</button>
                          </td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay usuarios registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>


    <div id="productos" class="table-container hidden">
    <h1>Tabla de Productos</h1>

    <!-- Formulario para agregar productos -->
    <div id="formulario-producto" class="formulario">
        <h2>Agregar Producto</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nombre" placeholder="Nombre del producto" required><br>
            <input type="number" name="precio" placeholder="Precio" step="0.01" required><br>
            <input type="number" name="stock" placeholder="Stock" required><br>
            <input type="text" name="especificaciones" placeholder="Especificaciones" required><br>
            <input type="file" name="imagen" accept="image/*" required><br><br>
            <button type="submit" name="agregar_producto">Agregar</button>
        </form>
    </div> 

    <!-- Tabla de productos -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Especificaciones</th>
                <th>Imagen</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultProductos->num_rows > 0) {
                while ($row = $resultProductos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                    echo "<td>" . $row['stock'] . "</td>";
                    echo "<td>" . $row['especificaciones'] . "</td>";
                    echo "<td><img src='" . $row['imagen'] . "' alt='Producto' width='100'></td>";

                    // Botón para editar producto
                    echo "<td class='action-buttons'>
                            <a href='../includes/actualizar_producto.php?id=" . $row['id'] . "' class='btn btn-edit'>Editar</a>
                          </td>";

                    // Botón para eliminar producto con confirmación
                    echo "<td class='action-buttons'>
                            <button class='btn btn-delete' onclick='confirmarEliminacion(\"../includes/eliminar_producto.php?id=" . $row['id'] . "\")'>Eliminar</button>
                          </td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>

    <div id="bitacora" class="table-container hidden">
            <h1>Tabla de Bitácora</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Operación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultBitacora->num_rows > 0) {
                        while ($row = $resultBitacora->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['usuario'] . "</td>";
                            echo "<td>" . $row['fecha'] . "</td>";
                            echo "<td>" . $row['operacion'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No hay registros en la bitácora</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    <div id="reportes" class="table-container hidden">
    <h1>Reportes de Ventas</h1>

    <!-- Contenedor del formulario -->
    <div class="formContainer">
        <form method="GET" action="admin.php" class="formulario">
            <label for="fecha_inicio">Fecha Inicial:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fechaInicio; ?>" required>

            <label for="fecha_fin">Fecha Final:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $fechaFin; ?>" required>

            <button type="submit">Generar Reporte</button>
            <button type="button" onclick="generarPDF()">Generar PDF</button>
            <button type="button" onclick="window.location.href='reporte_correo.php'">Enviar por Correo</button>
        </form>
    </div>

    <!-- Contenedor de la gráfica -->
    <div class="graficaContainer">
        <canvas id="graficoVentas"></canvas>
    </div>

    <script>
    // Generar el PDF con la gráfica incluida
    function generarPDF() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;

        // Convertir la gráfica en imagen
        const canvas = document.getElementById('graficoVentas');
        const imgData = canvas.toDataURL('image/png');

        // Crear un formulario dinámico para enviar datos a generar_pdf.php
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'generar_pdf.php';

        const inputFechaInicio = document.createElement('input');
        inputFechaInicio.type = 'hidden';
        inputFechaInicio.name = 'fecha_inicio';
        inputFechaInicio.value = fechaInicio;
        form.appendChild(inputFechaInicio);

        const inputFechaFin = document.createElement('input');
        inputFechaFin.type = 'hidden';
        inputFechaFin.name = 'fecha_fin';
        inputFechaFin.value = fechaFin;
        form.appendChild(inputFechaFin);

        const inputImg = document.createElement('input');
        inputImg.type = 'hidden';
        inputImg.name = 'grafica';
        inputImg.value = imgData;
        form.appendChild(inputImg);

        document.body.appendChild(form);
        form.submit();
    }

    // Código para mostrar la gráfica de ventas
    const ctx = document.getElementById('graficoVentas').getContext('2d');
    const data = {
        labels: <?php echo json_encode(array_keys($ventas)); ?>,
        datasets: [{
            label: 'Unidades Vendidas',
            data: <?php echo json_encode(array_values($ventas)); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    const config = {
        type: "bar",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Cantidad Vendida' }
                },
                x: {
                    title: { display: true, text: 'Nombre del Producto' }
                }
            }
        }
    };
    new Chart(ctx, config);
</script>

</div>



</div>


</body>
</html>


<?php

?>
