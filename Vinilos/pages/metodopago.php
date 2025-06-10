<?php
session_start();

// Si el carrito está vacío, redirige
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Método de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a237e, #7e57c2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-pagar {
            background-color: #4caf50;
            border: none;
        }

        .btn-pagar:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>💳 Método de Pago</h2>
    <form action="ticket.php" method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="tarjeta" class="form-label">Número de Tarjeta:</label>
            <input type="text" class="form-control" id="tarjeta" name="tarjeta" maxlength="16" pattern="\d{16}" placeholder="1234123412341234" required>
        </div>

        <div class="text-center">
            <!-- Botón para descargar el PDF -->
            <button type="submit" class="btn btn-info">Pagar - PDF</button>
        </div>
    </form>

    <!-- Formulario independiente para enviar el ticket por correo -->
    <form action="ticket_correo.php" method="post" class="text-center" onsubmit="return validarCorreo();">
        <input type="hidden" id="correoUsuario" name="email">
        <button type="submit" class="btn btn-warning">Pagar - Enviar al correo</button>
    </form>
</div>

<script>
function validarCorreo() {
    let email = document.getElementById('email').value;

    if (!email) {
        alert("❌ Debes ingresar un correo electrónico.");
        return false;
    }

    document.getElementById('correoUsuario').value = email;
    return true;
}
</script>

</body>
</html>
