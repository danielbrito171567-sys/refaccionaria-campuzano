<?php
include("includes/auth.php");
include("includes/conexion.php");

$busqueda = "";
if (isset($_GET['buscar']) && !empty($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $termino = "%$busqueda%";
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR categoria LIKE ? OR codigo LIKE ?");
    $stmt->bind_param("sss", $termino, $termino, $termino);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = mysqli_query($conexion, "SELECT * FROM productos ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="d-flex">
    <div class="sidebar text-white p-3" style="min-width: 250px; background: #212529; min-height: 100vh;">
        <h4 class="mb-4 text-center">Refaccionaria</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
            <li class="nav-item mb-2"><a href="inventario.php" class="nav-link text-white active">Inventario</a></li>
            <li class="nav-item mb-2"><a href="ventas.php" class="nav-link text-white">Ventas</a></li>
            <li class="nav-item mb-2"><a href="reportes.php" class="nav-link text-white">Reportes</a></li>
            <li class="nav-item mt-4"><a href="logout.php" class="nav-link text-danger">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Inventario de Productos</h2>
            <a href="agregar-producto.php" class="btn btn-primary shadow-sm">+ Agregar Producto</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
              a  <thead class="table-dark">
                    <tr><th>Producto</th><th>Precio</th><th>Stock</th><th class="text-center">Acciones</th></tr>
                </thead>
                <tbody>
                    <?php while($fila = mysqli_fetch_assoc($resultado)){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                            <td>$<?php echo number_format($fila['precio'], 2); ?></td>
                            <td><?php echo $fila['stock']; ?></td>
                            <td class="text-center">
                                <a href="editar-producto.php?id=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar-producto.php?id=<?php echo $fila['id']; ?>" class="btn btn-danger btn-sm">Borrar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>