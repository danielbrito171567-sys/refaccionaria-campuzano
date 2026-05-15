<?php
include("includes/auth.php");
include("includes/conexion.php");

/* TOTAL PRODUCTOS */
$productos_query = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM productos");
$total_productos = mysqli_fetch_assoc($productos_query);

/* TOTAL VENTAS */
$ventas_query = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM ventas");
$total_ventas = mysqli_fetch_assoc($ventas_query);

/* INGRESOS */
$ingresos_query = mysqli_query($conexion, "SELECT IFNULL(SUM(total_general), 0) AS total FROM ventas");
$total_ingresos = mysqli_fetch_assoc($ingresos_query);

/* STOCK BAJO */
$stock_bajo_query = mysqli_query($conexion, "SELECT * FROM productos WHERE stock <= 5");
$num_stock_bajo = mysqli_num_rows($stock_bajo_query);

/* LISTA VENTAS */
$lista_ventas = mysqli_query($conexion, "SELECT * FROM ventas ORDER BY id DESC LIMIT 20");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="d-flex">
    <div class="sidebar text-white p-3" style="min-width: 250px; background: #212529; min-height: 100vh;">
        <h4 class="mb-4 text-center">Refaccionaria Campuzano</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
            <li class="nav-item mb-2"><a href="inventario.php" class="nav-link text-white">Inventario</a></li>
            <li class="nav-item mb-2"><a href="ventas.php" class="nav-link text-white">Ventas</a></li>
            <li class="nav-item mb-2"><a href="reportes.php" class="nav-link text-white active">Reportes</a></li>
            <li class="nav-item mt-4"><a href="logout.php" class="nav-link text-danger">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <h2 class="mb-4">Reportes Generales</h2>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white shadow border-0 h-100">
                    <div class="card-body text-center">
                        <h6>Total Productos</h6>
                        <h3><?php echo $total_productos['total']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white shadow border-0 h-100">
                    <div class="card-body text-center">
                        <h6>Total Ventas</h6>
                        <h3><?php echo $total_ventas['total']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark shadow border-0 h-100">
                    <div class="card-body text-center">
                        <h6>Ingresos Totales</h6>
                        <h3>$<?php echo number_format($total_ingresos['total'], 2); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white shadow border-0 h-100">
                    <div class="card-body text-center">
                        <h6>Stock Bajo</h6>
                        <h3><?php echo $num_stock_bajo; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-0 mb-5">
            <div class="card-body">
                <h3 class="mb-4 border-bottom pb-2">Historial Reciente de Ventas</h3>
                
                <?php while($venta = mysqli_fetch_assoc($lista_ventas)){ ?>
                    <div class="card mb-3 border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Venta #<?php echo $venta['id']; ?></span>
                            <span class="badge bg-secondary"><?php echo $venta['fecha']; ?></span>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">Total Pagado: <strong class="text-success">$<?php echo number_format($venta['total_general'], 2); ?></strong></p>
                            
                            <table class="table table-sm table-bordered mb-0 small">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id_venta = $venta['id'];
                                    $detalles = mysqli_query($conexion, "SELECT * FROM detalle_ventas WHERE venta_id='$id_venta'");
                                    while($detalle = mysqli_fetch_assoc($detalles)){
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($detalle['producto']); ?></td>
                                            <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
                                            <td class="text-end">$<?php echo number_format($detalle['subtotal'], 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-body text-danger">
                <h3 class="mb-4 border-bottom pb-2">Productos que requieren Resurtido</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-danger">
                            <tr>
                                <th>ID</th>
                                <th>Descripción del Producto</th>
                                <th class="text-center">Existencia Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reiniciamos el puntero para volver a usar los datos del stock bajo
                            mysqli_data_seek($stock_bajo_query, 0);
                            while($fila = mysqli_fetch_assoc($stock_bajo_query)){ 
                            ?>
                                <tr>
                                    <td><?php echo $fila['id']; ?></td>
                                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                    <td class="text-center fw-bold"><?php echo $fila['stock']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if($num_stock_bajo == 0) echo "<tr><td colspan='3' class='text-center text-success'>Todo el inventario está en niveles óptimos.</td></tr>"; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>