<?php
include("../includes/config.php");

$sqlProductos = "SELECT nombre, precio, especificaciones, imagen, stock FROM productos";
$resultProductos = $conexion->query($sqlProductos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📀Vinyl Records - Tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a237e, #7e57c2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }

        .navbar {
            font-size: 22px;
            font-weight: bold;
            padding: 20px 40px;
            background-color: #0d47a1 !important;
        }

        .navbar-brand {
            font-family: 'Arial Black', Gadget, sans-serif;
            font-size: 28px;
        }

        .container {
            margin-top: 100px;
        }

        .card {
            height: 100%;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            text-align: center;
            overflow: hidden;
            background-color: #1c1c1c;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            color: #ccc;
            flex-grow: 1;
        }

        .card-text strong {
            color: #4caf50;
        }

        .card .btn {
            background-color: #2196f3;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .card .btn:hover {
            background-color: #1976d2;
        }

        .agotado {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .navbar {
                font-size: 18px;
                padding: 15px 20px;
            }

            .navbar-brand {
                font-size: 24px;
            }
        }

        /* Estilos personalizados para el modal */
        .modal-content {
            background-color: #1e1e2f;
            color: white;
            border-radius: 15px;
        }

        .modal-header {
            border-bottom: 1px solid #444;
        }

        .modal-body img {
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .modal-footer {
            border-top: 1px solid #444;
        }

        .modal-title {
            font-weight: bold;
            font-size: 24px;
        }

        #modalEspecificaciones {
            font-style: italic;
            color: #ccc;
        }

        #modalPrecio {
            font-weight: bold;
            color: #4caf50;
            font-size: 18px;
        }

        #agregarCarrito {
            background-color: #2196f3;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
        }

        #agregarCarrito:hover {
            background-color: #1976d2;
        }

        #cantidad {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <?php include "./../components/navbar.php"; ?>

    <div class="container">
        <h2 class="text-center mb-5 fw-bold">📎Catálogo de Vinilos</h2>

        <!-- Alerta de producto agregado -->
        <div id="alerta" class="alert alert-success d-none text-center" role="alert">
            Producto agregado al carrito.
        </div>

        <div class="row">
            <?php
            if ($resultProductos->num_rows > 0) {
                while ($row = $resultProductos->fetch_assoc()) {
                    echo "<div class='col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch'>";
                    echo "<div class='card w-100'>";
                    echo "<img src='" . $row['imagen'] . "' alt='" . $row['nombre'] . "'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . $row['nombre'] . "</h5>";
                    echo "<p class='card-text'>" . $row['especificaciones'] . "</p>";
                    echo "<p class='card-text'><strong>Precio:</strong> $" . number_format($row['precio'], 2) . "</p>";

                    if ($row['stock'] > 0) {
                        echo "<button class='btn btn-primary' onclick='verProducto(" . json_encode($row['nombre']) . ", " . json_encode($row['precio']) . ", " . json_encode($row['especificaciones']) . ", " . json_encode($row['imagen']) . ", " . $row['stock'] . ")'>Ver Producto</button>";
                    } else {
                        echo "<p class='text-danger'>Lo sentimos, las unidades están agotadas.</p>";
                    }

                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center'>No hay productos disponibles en la tienda.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Modal para Ver Producto -->
    <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productoModalLabel">💡Detalles del Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImagen" src="" class="img-fluid">
                    <h5 id="modalTitulo" class="mt-3"></h5>
                    <p id="modalEspecificaciones"></p>
                    <p id="modalPrecio"></p>
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" min="1" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="agregarCarrito">🛒Agregar al Carrito</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function verProducto(nombre, precio, especificaciones, imagen, stock) {
            document.getElementById("modalTitulo").innerText = nombre;
            document.getElementById("modalEspecificaciones").innerText = especificaciones;
            document.getElementById("modalPrecio").innerText = "Precio: $" + parseFloat(precio).toFixed(2);
            document.getElementById("modalImagen").src = imagen;

            const cantidadInput = document.getElementById("cantidad");
            cantidadInput.max = stock; // Establecer el máximo según el stock disponible

            document.getElementById("agregarCarrito").onclick = function () {
                const cantidad = parseInt(cantidadInput.value);
                if (cantidad > stock || cantidad < 1) {
                    alert("Por favor, selecciona una cantidad válida.");
                    return;
                }

                const formData = new FormData();
                formData.append("nombre", nombre);
                formData.append("precio", precio);
                formData.append("imagen", imagen);
                formData.append("especificaciones", especificaciones);
                formData.append("cantidad", cantidad);

                fetch("agregar_carrito.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.text())
                .then(respuesta => {
                    if (respuesta === "ok") {
                        const myModal = bootstrap.Modal.getInstance(document.getElementById("productoModal"));
                        myModal.hide();
                    } else {
                        alert("Error al agregar al carrito.");
                    }
                });
            };

            const myModal = new bootstrap.Modal(document.getElementById("productoModal"));
            myModal.show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conexion->close(); ?>
