<?php
$host = "refa-campuzano-final-2026.mysql.database.azure.com";
$user = "admin_refa";
$password = "Daniel508"; 
$database = "refaccionaria_campuzano";

// Aquí corregimos $pass por $password y $db por $database
$conexion = mysqli_connect($host, $user, $password, $database);

if (!$conexion) {
    die("Error de conexión a Azure: " . mysqli_connect_error());
}

// Para que los acentos y la Ñ funcionen bien
mysqli_set_charset($conexion, "utf8");
?>