<?php
include("includes/auth.php");
include("includes/conexion.php");

/* TOTAL PRODUCTOS */
$productos = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM productos");
$total_productos = mysqli_fetch_assoc($productos);

/* TOTAL VENTAS */
$ventas = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM ventas");
$total_ventas = mysqli_fetch_assoc($ventas);

/* INGRESOS - Añadí un IFNULL para que no aparezca vacío si no hay ventas */
$ingresos = mysqli_query($conexion, "SELECT IFNULL(SUM(total_general), 0) AS total FROM ventas");
$total_ingresos = mysqli_fetch_assoc($ingresos);

/* STOCK BAJO */
$stock = mysqli_query($conexion, "SELECT * FROM productos WHERE stock <= 5");

/* DATOS PARA GRÁFICA */
$grafica = mysqli_query($conexion, "SELECT id, total_general FROM ventas ORDER BY id ASC LIMIT 10");

$ventas_ids = [];
$ventas_totales = [];

while($fila = mysqli_fetch_assoc($grafica)){
    $ventas_ids[] = "Venta ".$fila['id'];
    $ventas_totales[] = $fila['total_general'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="d-flex">
    <div class="sidebar text-white p-3" style="min-width: 250px; background: #212529; min-height: 100vh;">
        <h4 class="mb-4 text-center">Refaccionaria Campuzano</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white active">Menu</a></li>
            <li class="nav-item mb-2"><a href="inventario.php" class="nav-link text-white">Inventario</a></li>
            <li class="nav-item mb-2"><a href="ventas.php" class="nav-link text-white">Ventas</a></li>
            <li class="nav-item mb-2"><a href="reportes.php" class="nav-link text-white">Reportes</a></li>
            <li class="nav-item mb-2"><a href="usuarios.php" class="nav-link text-white">Usuarios</a></li>
            <li class="nav-item mt-4"><a href="logout.php" class="nav-link text-danger">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <h2 class="mb-4">Panel de Control</h2>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card shadow border-0 bg-primary text-white h-100">
                    <div class="card-body">
                        <h5>Total Productos</h5>
                        <h2><?php echo $total_productos['total']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow border-0 bg-success text-white h-100">
                    <div class="card-body">
                        <h5>Total Ventas</h5>
                        <h2><?php echo $total_ventas['total']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow border-0 bg-warning text-dark h-100">
                    <div class="card-body">
                        <h5>Ingresos</h5>
                        <h2>$<?php echo number_format($total_ingresos['total'], 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow border-0 bg-danger text-white h-100">
                    <div class="card-body">
                        <h5>Stock Bajo (<=5)</h5>
                        <h2><?php echo mysqli_num_rows($stock); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-0 mt-4">
            <div class="card-body text-center">
                <h4 class="mb-4">Historial de Ventas</h4>
                <div style="position: relative; height:40vh; width:100%">
                    <canvas id="graficaVentas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('graficaVentas');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($ventas_ids); ?>,
        datasets: [{
            label: 'Total por Venta ($)',
            data: <?php echo json_encode($ventas_totales); ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.5)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>