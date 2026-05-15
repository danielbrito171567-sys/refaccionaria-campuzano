<?php
include("includes/auth.php");
include("includes/conexion.php");
$resultado = mysqli_query($conexion, "SELECT * FROM productos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { display: flex; min-height: 100vh; background-color: #f4f7f6; }
        .sidebar { width: 250px; background: #212529; color: white; padding: 20px; }
        .main-content { flex-grow: 1; padding: 30px; }
        .nav-link { color: rgba(255,255,255,.8); margin-bottom: 10px; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,.1); border-radius: 5px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center mb-4">🔧 CAMPUZANO</h3>
        <hr>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Inicio</a>
            <a class="nav-link active" href="inventario.php"><i class="bi bi-box-seam me-2"></i> Inventario</a>
            <a class="nav-link" href="ventas.php"><i class="bi bi-cart-plus me-2"></i> Nueva Venta</a>
            <a class="nav-link" href="reportes.php"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reportes</a>
            <a class="nav-link" href="usuarios.php"><i class="bi bi-people me-2"></i> Usuarios</a>
            <hr>
            <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Salir</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between mb-4">
            <h2>Inventario de Productos</h2>
            <a href="agregar-producto.php" class="btn btn-primary">+ Agregar Nuevo</a>
        </div>
        <table class="table table-striped shadow-sm bg-white">
            <thead class="table-dark">
                <tr><th>Producto</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php while($f = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $f['nombre']; ?></td>
                    <td>$<?php echo number_format($f['precio'], 2); ?></td>
                    <td><?php echo $f['stock']; ?></td>
                    <td>
                        <a href="editar-producto.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>