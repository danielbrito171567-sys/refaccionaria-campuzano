<?php
// 1. Conexión y Seguridad
include("includes/auth.php");
include("includes/conexion.php");

// Consulta a la base de datos
$resultado = mysqli_query($conexion, "SELECT id, usuario, rol FROM usuarios ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-custom { background-color: #212529; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">🔧 CAMPUZANO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="ventas.php">Ventas</a></li>
                    <li class="nav-item"><a class="nav-link active" href="usuarios.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0 fw-bold">Gestión de Usuarios</h2>
            <span class="badge bg-dark rounded-pill px-3">Personal ITSX</span>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 80px;">ID</th>
                                <th>Usuario</th>
                                <th>Rol / Puesto</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($resultado && mysqli_num_rows($resultado) > 0): ?>
                                <?php while($u = mysqli_fetch_assoc($resultado)): ?>
                                <tr>
                                    <td class="ps-3 text-muted">#<?php echo $u['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <?php echo strtoupper(substr($u['usuario'], 0, 1)); ?>
                                            </div>
                                            <span class="fw-bold"><?php echo htmlspecialchars($u['usuario']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3">
                                            <?php echo strtoupper($u['rol']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No hay usuarios registrados o error en la tabla.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-white rounded shadow-sm">
            <p class="small text-muted mb-0">
                <i class="bi bi-info-circle me-2"></i> 
                Por seguridad, la creación de nuevos usuarios está restringida al Administrador de la base de datos.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>