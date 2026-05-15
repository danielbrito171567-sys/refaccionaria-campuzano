<?php
include("includes/auth.php");
include("includes/conexion.php");

// Verificamos que exista un ID en la URL
if(!isset($_GET['id'])){
    header("Location: inventario.php");
    exit();
}

$id = $_GET['id'];

// Obtener datos del producto con consulta preparada para evitar inyecciones SQL
$stmt_fetch = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$stmt_fetch->bind_param("i", $id);
$stmt_fetch->execute();
$resultado = $stmt_fetch->get_result();
$fila = $resultado->fetch_assoc();

if(!$fila){
    header("Location: inventario.php");
    exit();
}

// Lógica de actualización
if(isset($_POST['actualizar'])){
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];

    // Actualización con consulta preparada
    $stmt_update = $conexion->prepare("UPDATE productos SET nombre=?, categoria=?, precio=?, stock=?, codigo=?, descripcion=? WHERE id=?");
    $stmt_update->bind_param("ssdissi", $nombre, $categoria, $precio, $stock, $codigo, $descripcion, $id);

    if($stmt_update->execute()){
        $stmt_update->close();
        header("Location: inventario.php");
        exit();
    } else {
        $error_msg = "Error al actualizar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="d-flex">
    <div class="sidebar text-white p-3" style="min-width: 250px; background: #212529; min-height: 100vh;">
        <h4 class="mb-4 text-center">Refaccionaria Campuzano</h4>
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
                <h2 class="mb-4">Editar Producto: <?php echo htmlspecialchars($fila['nombre']); ?></h2>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($fila['nombre']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría</label>
                            <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($fila['categoria']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo htmlspecialchars($fila['precio']); ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($fila['stock']); ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($fila['codigo']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($fila['descripcion']); ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="actualizar" class="btn btn-success">
                            Actualizar Producto
                        </button>
                        <a href="inventario.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>