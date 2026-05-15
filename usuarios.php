<?php
include("includes/auth.php");
include("includes/conexion.php");
$resultado = mysqli_query($conexion, "SELECT * FROM usuarios");
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
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center mb-4">🔧 CAMPUZANO</h3>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php">Inicio</a>
            <a class="nav-link" href="inventario.php">Inventario</a>
            <a class="nav-link active" href="usuarios.php">Usuarios</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between mb-4">
            <h2>Control de Usuarios</h2>
            <a href="agregar-usuario.php" class="btn btn-dark">+ Agregar Usuario</a>
        </div>
        <table class="table table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th><th>Rol</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($u = mysqli_fetch_assoc($resultado)){ ?>
                <tr>
                    <td><?php echo $u['usuario']; ?></td>
                    <td><?php echo $u['rol']; ?></td>
                    <td>
                        <a href="editar-usuario.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                        <a href="eliminar-usuario.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Borrar usuario?')"><i class="bi bi-person-x"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>