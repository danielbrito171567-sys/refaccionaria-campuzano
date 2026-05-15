<?php
// Protección de sesión y conexión
include("includes/auth.php");
include("includes/conexion.php");

// Verificamos que el ID exista en la URL y sea un número
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    
    $id = $_GET['id'];

    // Usamos una consulta preparada para mayor seguridad
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        $stmt->close();
        // Redirigimos al inventario tras borrar con éxito
        header("Location: inventario.php");
        exit();
    } else {
        echo "Error al eliminar el producto: " . $conexion->error;
    }

} else {
    // Si no hay ID o no es válido, regresamos al inventario
    header("Location: inventario.php");
    exit();
}
?>