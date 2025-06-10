<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🛒 Tu Carrito de Vinilos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a237e, #7e57c2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .container {
            margin-top: 60px;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 15px;
            color: #ffffff;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .table {
            color: #ffffff;
        }

        .table th {
            background-color: #2e2e4d;
            color: #ffffff;
        }

        .table td {
            color: #dddddd;
            vertical-align: middle;
        }

        .btn-danger, .btn-success {
            margin-right: 10px;
        }

        .carrito-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .acciones form {
            display: flex;
            justify-content: center;
        }

        .text-center.last {
            margin-top: 40px;
        }

        .btn-volver {
            background-color: #2196f3;
            border: none;
            color: white;
        }

        .btn-volver:hover {
            background-color: #1976d2;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }

        .table thead th {
            border-bottom: 2px solid #555;
        }

        .table td, .table th {
            border-color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛒 Tu Carrito de Vinilos</h2>

        <?php if (!empty($_SESSION['carrito'])): ?>
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Especificaciones</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $granTotal = 0;
                    foreach ($_SESSION['carrito'] as $index => $producto):
                        $total = $producto['precio'] * $producto['cantidad'];
                        $granTotal += $total;
                    ?>
                    <tr>
                        <td><img src="<?= $producto['imagen'] ?>" alt="producto" class="carrito-img"></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['especificaciones']) ?></td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td><?= $producto['cantidad'] ?></td>
                        <td>$<?= number_format($total, 2) ?></td>
                        <td class="acciones">
                            <form method="post" action="quitar_carrito.php" onsubmit="return confirm('¿Estás seguro de quitar este producto del carrito?');">
                                <input type="hidden" name="indice" value="<?= $index ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Quitar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h4 class="text-end mt-4">Total a pagar: <strong>$<?= number_format($granTotal, 2) ?></strong></h4>

            <div class="text-center last">
                <a href="tienda.php" class="btn btn-volver">Seguir comprando</a>
                <a href="metodopago.php" class="btn btn-success">Proceder al pago</a>
            </div>
        <?php else: ?>
            <p class="text-center">Tu carrito está vacío. 😢</p>
            <div class="text-center mt-4">
                <a href="tienda.php" class="btn btn-volver">Ir a la tienda</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
