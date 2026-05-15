<?php
// Incluimos la autenticación y la conexión
include("includes/auth.php");
include("includes/conexion.php");

if(isset($_POST['guardar'])){

    // Recogemos los datos del formulario
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];

    // Usamos sentencias preparadas para evitar errores de conexión y SQL Injection
    $stmt = $conexion->prepare("INSERT INTO productos (nombre, categoria, precio, stock, codigo, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
    
    // "ssdiss" indica los tipos: string, string, double (decimal), integer, string, string
    $stmt->bind_param("ssdiss", $nombre, $categoria, $precio, $stock, $codigo, $descripcion);

    if($stmt->execute()){
        $stmt->close();
        // Redirigir a inventario.php (asegúrate que el archivo se llame así en minúsculas)
        header("Location: inventario.php");
        exit(); 
    } else {
        $error_msg = "Error al guardar el producto: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<div class="d-flex">

    <div class="sidebar text-white p-3" style="min-width: 250px; background: #212529; min-height: 100vh;">
        <h4 class="mb-4 text-center">
            Refaccionaria Campuzano
        </h4>
        <hr>
        <nav class="nav flex-column">
            <a href="dashboard.php" class="nav-link text-white">Inicio</a>
            <a href="inventario.php" class="nav-link text-white">Inventario</a>
            <a href="logout.php" class="nav-link text-danger mt-5">Cerrar Sesión</a>
        </nav>
    </div>

    <div class="container-fluid p-4">

        <?php if(isset($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h2 class="mb-4">Agregar Nuevo Producto</h2>

                <form action="agregar-producto.php" method="POST">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej. Amortiguador Delantero" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría</label>
                            <input type="text" name="categoria" class="form-control" placeholder="Ej. Suspensión" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Precio (Venta)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock inicial</label>
                            <input type="number" name="stock" class="form-control" placeholder="Cant. disponible" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Código de Parte</label>
                            <input type="text" name="codigo" class="form-control" placeholder="Ej. REF-001" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Descripción Detallada</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Especificaciones técnicas..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="guardar" class="btn btn-success px-4">
                            Guardar Producto
                        </button>
                        <a href="inventario.php" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>

</body>
</html>