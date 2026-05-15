<?php
// 1. Seguridad y Conexión
include("includes/auth.php");
include("includes/conexion.php");

/* CONSULTAS DE ESTADÍSTICAS */
$productos = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM productos");
$total_productos = mysqli_fetch_assoc($productos);

$ventas = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM ventas");
$total_ventas = mysqli_fetch_assoc($ventas);

$ingresos = mysqli_query($conexion, "SELECT IFNULL(SUM(total_general), 0) AS total FROM ventas");
$total_ingresos = mysqli_fetch_assoc($ingresos);

$stock_bajo = mysqli_query($conexion, "SELECT * FROM productos WHERE stock <= 5");
$num_stock_bajo = mysqli_num_rows($stock_bajo);

/* DATOS PARA LA GRÁFICA (Últimas 7 ventas) */
$grafica_query = mysqli_query($conexion, "SELECT id, total_general FROM ventas ORDER BY id DESC LIMIT 7");
$ventas_labels = [];
$ventas_valores = [];

while($v = mysqli_fetch_assoc($grafica_query)){
    $ventas_labels[] = "#".$v['id'];
    $ventas_valores[] = $v['total_general'];
}
// Invertimos para que se vea de izquierda a derecha (antiguo a nuevo)
$ventas_labels = array_reverse($ventas_labels);
$ventas_valores = array_reverse($ventas_valores);

// 2. Cargamos el diseño
include("includes/header.php"); 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 fw-bold">Panel Principal</h2>
        <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
            <i class="bi bi-calendar3 me-2"></i><?php echo date('d/m/Y'); ?>
        </span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body text-center p-3">
                    <div class="small opacity-75 mb-1">Productos</div>
                    <div class="h3 fw-bold mb-0"><?php echo $total_productos['total']; ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body text-center p-3">
                    <div class="small opacity-75 mb-1">Ventas</div>
                    <div class="h3 fw-bold mb-0"><?php echo $total_ventas['total']; ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body text-center p-3">
                    <div class="small opacity-75 mb-1">Ingresos</div>
                    <div class="h4 fw-bold mb-0">$<?php echo number_format($total_ingresos['total'], 0); ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white h-100">
                <div class="card-body text-center p-3">
                    <div class="small opacity-75 mb-1">Stock Bajo</div>
                    <div class="h3 fw-bold mb-0"><?php echo $num_stock_bajo; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4 small text-uppercase text-muted">Rendimiento de Ventas ($)</h5>
            <div style="position: relative; height: 250px;">
                <canvas id="chartVentas"></canvas>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 mb-5">
        <a href="ventas.php" class="btn btn-dark btn-lg py-3 rounded-4 shadow-sm fw-bold">
            <i class="bi bi-plus-circle me-2"></i>Nueva Venta Rápida
        </a>
    </div>
</div>

<script>
const ctx = document.getElementById('chartVentas');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($ventas_labels); ?>,
        datasets: [{
            label: 'Total Venta',
            data: <?php echo json_encode($ventas_valores); ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<?php 
// 3. Cerramos el diseño
include("includes/footer.php"); 
?>