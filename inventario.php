<?php
include("includes/auth.php");
include("includes/conexion.php");

/* BUSCADOR SEGURO */
$busqueda = "";
if (isset($_GET['buscar']) && !empty($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    // Usamos consultas preparadas para el buscador en Azure
    $termino = "%$busqueda%";
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR categoria LIKE ? OR codigo LIKE ?");
    $stmt->bind_param("sss", $termino, $termino, $termino);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    // Si no hay búsqueda, traemos todo
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
        <h4 class="mb-4 text-center">Refaccionaria Campuzano</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Menu</a></li>
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

        <div class="card shadow border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="inventario.php">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="busqueda" 
                            class="form-control" 
                            placeholder="Buscar por nombre, categoría o código..." 
                            value="<?php echo htmlspecialchars($busqueda); ?>"
                        >
                        <button type="submit" name="buscar" class="btn btn-dark px-4">Buscar</button>
                        <?php if($busqueda != ""): ?>
                            <a href="inventario.php" class="btn btn-outline-secondary">Limpiar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Código</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(mysqli_num_rows($resultado) > 0): ?>
                            <?php while($fila = mysqli_fetch_assoc($resultado)){ ?>
                                <tr>
                                    <td class="ps-3 text-muted"><?php echo $fila['id']; ?></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($fila['categoria']); ?></span></td>
                                    <td class="text-success fw-bold">$<?php echo number_format($fila['precio'], 2); ?></td>
                                    <td>
                                        <?php 
                                            $clase_stock = ($fila['stock'] <= 5) ? 'text-danger fw-bold' : '';
                                            echo "<span class='$clase_stock'>{$fila['stock']}</span>";
                                        ?>
                                    </td>
                                    <td><code><?php echo htmlspecialchars($fila['codigo']); ?></code></td>
                                    <td class="text-center">
                                        <div class="btn-group gap-1">
                                            <a href="editar-producto.php?id=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <a href="eliminar-producto.php?id=<?php echo $fila['id']; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.');">
                                               Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No se encontraron productos.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>