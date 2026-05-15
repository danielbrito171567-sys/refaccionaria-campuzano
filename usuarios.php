<?php
include("includes/auth.php");
include("includes/conexion.php");
$resultado = mysqli_query($conexion, "SELECT * FROM usuarios ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Campuzano</title>
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
            <a class="nav-link" href="dashboard.php">Inicio</a>
            <a class="nav-link" href="inventario.php">Inventario</a>
            <a class="nav-link" href="ventas.php">Nueva Venta</a>
            <a class="nav-link" href="reportes.php">Reportes</a>
            <a class="nav-link active" href="usuarios.php">Usuarios</a>
            <hr>
            <a class="nav-link text-danger" href="logout.php">Salir</a>
        </nav>
    </div>
    <div class="main-content">
        <h2>Usuarios registrados</h2>
        <table class="table table-hover shadow-sm bg-white mt-4">
            <thead class="table-dark">
                <tr><th>ID</th><th>Usuario</th><th>Rol</th></tr>
            </thead>
            <tbody>
                <?php while($u = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td>#<?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($u['rol']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>