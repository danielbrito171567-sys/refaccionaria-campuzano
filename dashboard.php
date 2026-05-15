<?php
include("includes/auth.php");
include("includes/conexion.php");

$prod = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM productos"));
$vent = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM ventas"));
$ingr = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT IFNULL(SUM(total_general), 0) as t FROM ventas"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Campuzano</title>
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
        <h2 class="mb-4">Panel Principal</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm border-0">
                    <div class="card-body p-4">
                        <h6>Productos en Stock</h6>
                        <h2 class="fw-bold"><?php echo $prod['t']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm border-0">
                    <div class="card-body p-4">
                        <h6>Ventas Realizadas</h6>
                        <h2 class="fw-bold"><?php echo $vent['t']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow-sm border-0">
                    <div class="card-body p-4">
                        <h6>Ingresos Totales</h6>
                        <h2 class="fw-bold">$<?php echo number_format($ingr['t'], 2); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>