<?php
$server = "loggin.mysql.database.azure.comt";
$userdb = "paco";
$passworddb = "199627Fggv27";
$namedb = "tienda_vinilos";

$conexion = new mysqli($server, $userdb, $passworddb, $namedb);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
