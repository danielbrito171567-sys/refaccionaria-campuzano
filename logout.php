<?php
// Iniciamos la sesión para poder destruirla
session_start();

// Limpiamos todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, también hay que borrar la cookie de sesión.
// Nota: Esto asegura que el navegador no guarde rastro de la sesión anterior.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruimos la sesión en el servidor
session_destroy();

// Redirigimos al login (asegúrate de que el archivo sea login.php en minúsculas)
header("Location: login.php");
exit();
?>