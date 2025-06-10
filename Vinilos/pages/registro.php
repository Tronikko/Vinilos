<?php include ("../includes/header.php"); ?>

<div class="container">
    <div class="login-section">
        <h2>Registro de Usuario</h2>
        <form action="guardar_usuario.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <label for="rol">Seleccionar Rol:</label>
            <select name="rol" id="rol" required>
                <option value="usuario">Usuario</option>
                <option value="administrador">Administrador</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
        <form action="index.php" method="GET">
            <button type="submit">Volver</button>
        </form>
    </div>
</div>

<?php include ("../includes/footer.php"); ?>
