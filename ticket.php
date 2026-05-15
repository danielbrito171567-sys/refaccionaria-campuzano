<?php
include("includes/auth.php");
include("includes/conexion.php");

// Validamos que el ID de venta exista y sea numérico
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: reportes.php");
    exit();
}

$id_venta = $_GET['id'];

// Consulta preparada para obtener los datos generales de la venta
$stmt_venta = $conexion->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt_venta->bind_param("i", $id_venta);
$stmt_venta->execute();
$resultado_venta = $stmt_venta->get_result();
$datos_venta = $resultado_venta->fetch_assoc();

if(!$datos_venta){
    header("Location: reportes.php");
    exit();
}

// Consulta preparada para los detalles (productos vendidos)
$stmt_detalles = $conexion->prepare("SELECT * FROM detalle_ventas WHERE venta_id = ?");
$stmt_detalles->bind_param("i", $id_venta);
$stmt_detalles->execute();
$detalles = $stmt_detalles->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Venta #<?php echo $id_venta; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos específicos para la impresión */
        @media print {
            .btn, .sidebar, footer {
                display: none !important;
            }
            .card {
                border: none !important;
                shadow: none !important;
            }
            body {
                background-color: white !important;
            }
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <h1 class="fw-bold text-primary">Refaccionaria Campuzano</h1>
                        <p class="text-muted">Xalapa, Veracruz | Tel: (228) 000-0000</p>
                        <h4 class="mt-3 text-dark border-bottom pb-2">COMPROBANTE DE VENTA</h4>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <p class="mb-1 text-muted">Folio de Venta:</p>
                            <h5 class="fw-bold">#<?php echo $datos_venta['id']; ?></h5>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-1 text-muted">Fecha de Emisión:</p>
                            <h5 class="fw-bold"><?php echo date("d/m/Y H:i", strtotime($datos_venta['fecha'])); ?></h5>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Descripción del Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($detalle = $detalles->fetch_assoc()){ ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detalle['producto']); ?></td>
                                    <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
                                    <td class="text-end">$<?php echo number_format($detalle['subtotal'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div class="row mt-4">
                        <div class="col-7">
                            <p class="small text-muted italic">Gracias por su compra. Conserve este comprobante para cualquier aclaración o garantía.</p>
                        </div>
                        <div class="col-5 text-end">
                            <p class="h5 text-muted mb-1">Total a Pagar:</p>
                            <h2 class="fw-bold text-success">$<?php echo number_format($datos_venta['total_general'], 2); ?></h2>
                        </div>
                    </div>

                    <div class="text-center mt-5 d-print-none d-flex gap-2 justify-content-center">
                        <button onclick="window.print()" class="btn btn-primary btn-lg px-4">
                            Imprimir Ticket
                        </button>
                        <a href="reportes.php" class="btn btn-outline-secondary btn-lg px-4">
                            Volver
                        </a>
                    </div>

                </div>
            </div>
            <p class="text-center mt-4 text-muted small d-print-none">Sistema Refaccionaria Campuzano v1.0</p>
        </div>
    </div>
</div>

</body>
</html>