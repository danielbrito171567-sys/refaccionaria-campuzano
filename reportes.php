<?php
include("includes/auth.php");
include("includes/conexion.php");

$query = "SELECT * FROM ventas ORDER BY fecha DESC";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">🔧 Campuzano</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="ventas.php">Ventas</a></li>
                    <li class="nav-item"><a class="nav-link active" href="reportes.php">Reportes</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="h3 mb-4 fw-bold">Historial de Ventas</h2>
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Folio</th>
                            <th>Fecha y Hora</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($f = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td class="ps-3 fw-bold">#<?php echo $f['id']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($f['fecha'])); ?></td>
                            <td class="text-end text-success fw-bold">$<?php echo number_format($f['total_general'], 2); ?></td>
                            <td class="text-center">
                                <a href="ticket.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-file-earmark-text"></i> Ver
                                </a>
                            </td>
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