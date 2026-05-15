<?php
include("includes/auth.php");
include("includes/conexion.php");

$prod = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM productos"));
$vent = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM ventas"));
$ingr = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT SUM(total_general) as t FROM ventas"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="ventas.php">Ventas</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="card bg-primary text-white border-0 shadow-sm">
                    <div class="card-body">
                        <small>Productos</small>
                        <h3><?php echo $prod['t']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card bg-success text-white border-0 shadow-sm">
                    <div class="card-body">
                        <small>Ventas</small>
                        <h3><?php echo $vent['t']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card bg-warning text-dark border-0 shadow-sm">
                    <div class="card-body">
                        <small>Ingresos</small>
                        <h3>$<?php echo number_format($ingr['t'], 0); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <h5>Ventas Recientes</h5>
            <canvas id="myChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Venta 1', 'Venta 2', 'Venta 3', 'Venta 4', 'Venta 5'],
                datasets: [{
                    label: 'Ventas ($)',
                    data: [120, 190, 300, 500, 200],
                    borderColor: '#0d6efd',
                    tension: 0.3
                }]
            }
        });
    </script>
</body>
</html>