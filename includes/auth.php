<?php
session_start();

// Si no existe la sesión, mandamos al login
if(!isset($_SESSION['usuario'])){
    session_write_close(); 
    header("Location: index.php"); // Asegúrate de que tu login se llame index.php
    exit();
}
?>