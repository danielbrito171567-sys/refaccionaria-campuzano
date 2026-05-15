<?php

$host = "localhost";
$usuario = "root";
$password = "";
$bd = "refaccionaria_campuzano";

$conexion = mysqli_connect(
    $host,
    $usuario,
    $password,
    $bd
);

if(!$conexion){

    die("Error de conexión");

}

?>