<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    // Cerramos sesión por seguridad antes de redirigir
    session_write_close();
    header("Location: index.php");
    exit();
}
?>