<?php
session_start();
include("../includes/config.php"); // conexión con la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $especificaciones = $_POST['especificaciones'];
    $cantidad = (int) $_POST['cantidad'];

    // Buscar stock actual
    $stmt = $conexion->prepare("SELECT id, stock FROM productos WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo "producto no encontrado";
        exit;
    }

    $producto = $resultado->fetch_assoc();
    $idProducto = $producto['id'];
    $stockActual = $producto['stock'];

    // Validar stock disponible
    if ($cantidad > $stockActual) {
        echo "stock insuficiente";
        exit;
    }

    // Restar stock
    $nuevoStock = $stockActual - $cantidad;
    $update = $conexion->prepare("UPDATE productos SET stock = ? WHERE id = ?");
    $update->bind_param("ii", $nuevoStock, $idProducto);
    $update->execute();

    // Agregar al carrito en sesión
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    $productoCarrito = [
        'id' => $idProducto,
        'nombre' => $nombre,
        'precio' => $precio,
        'imagen' => $imagen,
        'especificaciones' => $especificaciones,
        'cantidad' => $cantidad
    ];

    $encontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] === $idProducto) {
            $item['cantidad'] += $cantidad;
            $encontrado = true;
            break;
        }
    }
    unset($item);

    if (!$encontrado) {
        $_SESSION['carrito'][] = $productoCarrito;
    }

    echo "ok";
} else {
    echo "error";
}
