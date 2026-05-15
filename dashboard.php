<?php
include("includes/auth.php");
include("includes/conexion.php");

// Consultas para las tarjetas
$prod = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM productos"));
$vent = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM ventas"));
// Consulta específica para productos con bajo stock (menor a 5)
$bajo_stock = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM productos WHERE stock <= 5"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a class="nav-link active" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Inicio</a>
            <a class="nav-link" href="inventario.php"><i class="bi bi-box-seam me-2"></i> Inventario</a>
            <a class="nav-link" href="ventas.php"><i class="bi bi-cart-plus me-2"></i> Nueva Venta</a>
            <a class="nav-link" href="reportes.php"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reportes</a>
            <a class="nav-link" href="usuarios.php"><i class="bi bi-people me-2"></i> Usuarios</a>
            <hr>
            <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Salir</a>
        </nav>
    </div>

    <div class="main-content">
        <h2 class="mb-4">Resumen del Sistema</h2>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white border-0 shadow-sm">
                    <div class="card-body p-3">
                        <h6>Total Productos</h6>
                        <h2><?php echo $prod['t']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white border-0 shadow-sm">
                    <div class="card-body p-3">
                        <h6>Bajo Stock</h6>
                        <h2><?php echo $bajo_stock['t']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white border-0 shadow-sm">
                    <div class="card-body p-3">
                        <h6>Ventas Hoy</h6>
                        <h2><?php echo $vent['t']; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <h5>Rendimiento de Ventas</h5>
            <canvas id="canvasGrafica" height="100"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('canvasGrafica');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
                datasets: [{
                    label: 'Ventas Semanales',
                    data: [12, 19, 3, 5, 2],
                    borderColor: '#0d6efd',
                    tension: 0.3
                }]
            }
        });
    </script>
</body>
</html>