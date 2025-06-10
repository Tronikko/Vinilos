<?php
$server = "localhost";
$userdb = "root";
$passworddb = "";
$namedb = "tienda_vinilos";

$conexion = new mysqli($server, $userdb, $passworddb, $namedb);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
