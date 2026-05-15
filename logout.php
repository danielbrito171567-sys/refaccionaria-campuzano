<?php
// 1. Iniciamos la sesión para poder identificarla y borrarla
session_start();

// 2. Limpiamos todas las variables de sesión del arreglo $_SESSION
$_SESSION = array();

// 3. Borramos la cookie de sesión del navegador (Muy importante para la seguridad en móviles)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruimos la sesión en el servidor de Azure
session_destroy();

// 5. Redirigimos al login
header("Location: login.php");
exit();
?>