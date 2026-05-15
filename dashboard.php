<?php
include("includes/auth.php");
include("includes/conexion.php");

// Consultas para las tarjetas
$prod = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM productos"));
$vent = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM ventas"));
$ingr = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT IFNULL(SUM(total_general), 0) as t FROM ventas"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">🔧 CAMPUZANO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="ventas.php">Ventas</a></li>
                    <li class="nav-item"><a class="nav-link" href="reportes.php">Reportes</a></li>
                    <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li> <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="h4 fw-bold mb-4">Panel de Control</h2>
        
        <div class="row g-3 mb-4 text-center">
            <div class="col-6 col-md-4">
                <div class="card bg-primary text-white border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="small mb-1 opacity-75">Productos</p>
                        <h3 class="fw-bold mb-0"><?php echo $prod['t']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card bg-success text-white border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="small mb-1 opacity-75">Ventas</p>
                        <h3 class="fw-bold mb-0"><?php echo $vent['t']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card bg-warning text-dark border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="small mb-1 opacity-75">Ingresos Totales</p>
                        <h3 class="fw-bold mb-0">$<?php echo number_format($ingr['t'], 0); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4 mb-5">
            <h5 class="fw-bold mb-3 text-muted small text-uppercase">Rendimiento</h5>
            <div style="height: 300px;">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
                datasets: [{
                    label: 'Ventas Diarias',
                    data: [450, 800, 300, 900, 1200],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>