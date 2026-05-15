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
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center mb-4">🔧 CAMPUZANO</h3>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php">Inicio</a>
            <a class="nav-link active" href="inventario.php">Inventario</a>
            <a class="nav-link" href="ventas.php">Ventas</a>
            <a class="nav-link" href="usuarios.php">Usuarios</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between mb-4">
            <h2>Gestión de Inventario</h2>
            <a href="agregar-producto.php" class="btn btn-primary">+ Nuevo Producto</a>
        </div>
        <table class="table table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th><th>Precio</th><th>Stock</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($f = mysqli_fetch_assoc($resultado)){ ?>
                <tr>
                    <td><?php echo $f['nombre']; ?></td>
                    <td>$<?php echo number_format($f['precio'], 2); ?></td>
                    <td><?php echo $f['stock']; ?></td>
                    <td>
                        <a href="editar-producto.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="eliminar-producto.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>