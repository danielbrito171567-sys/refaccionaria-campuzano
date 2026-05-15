<?php
include("includes/auth.php");
include("includes/conexion.php");

$resultado = mysqli_query($conexion, "SELECT id, usuario, rol FROM usuarios");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">🔧 Campuzano</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php text-danger">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 fw-bold">Gestión de Usuarios</h2>
            <button class="btn btn-primary disabled small">+ Nuevo (Solo Admin)</button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Nombre de Usuario</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($u = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td class="ps-3 text-muted"><?php echo $u['id']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($u['usuario']); ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo $u['rol']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>