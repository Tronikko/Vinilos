<?php include("../includes/header.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Vinyl Records</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #1e3c72, #2a5298); /* azul a morado */
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 1200px; /* más ancho */
            width: 95%;        /* ocupa casi toda la pantalla */
            min-height: 600px; /* más alto */
        }

        .image-section {
            flex: 1.2; /* imagen un poco más ancha */
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-section {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #1c1c1c;
        }

        .login-section h2 {
            font-size: 36px;
            margin-bottom: 35px;
            color: #ffffff;
            text-align: center;
        }

        .login-section form {
            display: flex;
            flex-direction: column;
        }

        .login-section input {
            padding: 16px;
            margin-bottom: 25px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
        }

        .login-section button {
            padding: 16px;
            background-color: #1976d2;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-section button:hover {
            background-color: #145ca8;
        }

        .register-link {
            margin-top: 25px;
            text-align: center;
            color: #90caf9;
            text-decoration: none;
            font-size: 16px;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                min-height: auto;
            }

            .image-section {
                height: 250px;
            }

            .login-section {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="image-section">
        <img src="./../assets/media/img/viny.jpg" alt="Imagen Vinilo">
    </div>
    <div class="login-section">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        <a href="registro.php" class="register-link">Registrarse</a>
    </div>
</div>

</body>
</html>

<?php include("../includes/footer.php"); ?>
