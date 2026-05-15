<?php
include("includes/auth.php");
include("includes/conexion.php");

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

/* ELIMINAR PRODUCTO DEL CARRITO */
if(isset($_GET['eliminar'])){
    $index = $_GET['eliminar'];
    if(isset($_SESSION['carrito'][$index])){
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
    header("Location: ventas.php");
    exit();
}

/* AGREGAR AL CARRITO */
if(isset($_POST['agregar'])){
    $id_producto = $_POST['id_producto'];
    $cantidad = intval($_POST['cantidad']);

    // Consulta preparada para obtener datos del producto
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();

    if($producto){
        if($cantidad > $producto['stock'] || $cantidad <= 0){
            $error = "Cantidad no válida o stock insuficiente (Disponible: {$producto['stock']}).";
        } else {
            $subtotal = $producto['precio'] * $cantidad;
            $_SESSION['carrito'][] = [
                'id' => $producto['id'],
                'producto' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            ];
        }
    }
}

/* FINALIZAR VENTA */
if(isset($_POST['finalizar'])){
    if(empty($_SESSION['carrito'])){
        $error = "El carrito está vacío.";
    } else {
        $total_general = 0;
        foreach($_SESSION['carrito'] as $item) {
            $total_general += $item['subtotal'];
        }

        // Iniciamos transacción para asegurar que todo se guarde o nada
        mysqli_begin_transaction($conexion);

        try {
            // 1. Insertar Venta
            $stmt_v = $conexion->prepare("INSERT INTO ventas (total_general, fecha) VALUES (?, NOW())");
            $stmt_v->bind_param("d", $total_general);
            $stmt_v->execute();
            $venta_id = $conexion->insert_id;

            // 2. Insertar Detalles y Actualizar Stock
            $stmt_d = $conexion->prepare("INSERT INTO detalle_ventas (venta_id, producto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
            $stmt_s = $conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");

            foreach($_SESSION['carrito'] as $item){
                // Detalle
                $stmt_d->bind_param("isid", $venta_id, $item['producto'], $item['cantidad'], $item['subtotal']);
                $stmt_d->execute();
                
                // Descontar Stock
                $stmt_s->bind_param("ii", $item['cantidad'], $item['id']);
                $stmt_s->execute();
            }

            mysqli_commit($conexion);
            $_SESSION['carrito'] = [];
            header("Location: ticket.php?id=$venta_id");
            exit();

        } catch (Exception $e) {
            mysqli_rollback($conexion);
            $error = "Error crítico al procesar la venta.";
        }
    }
}

// Obtener productos para el select
$productos_lista = mysqli_query($conexion, "SELECT * FROM productos WHERE stock > 0 ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="d-flex">
    <div class="sidebar text-white p-3" style="min-width: 250px; background: #212529; min-height: 100vh;">
        <h4 class="mb-4 text-center">Refaccionaria</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Menu</a></li>
            <li class="nav-item mb-2"><a href="inventario.php" class="nav-link text-white">Inventario</a></li>
            <li class="nav-item mb-2"><a href="ventas.php" class="nav-link text-white active bg-primary">Nueva Venta</a></li>
            <li class="nav-item mb-2"><a href="reportes.php" class="nav-link text-white">Reportes</a></li>
            <li class="nav-item mt-5"><a href="logout.php" class="nav-link text-danger">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <h2 class="mb-4">Módulo de Ventas</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow border-0 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Seleccionar Producto</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Refacción</label>
                                <select name="id_producto" class="form-select" required>
                                    <option value="">Buscar refacción...</option>
                                    <?php while($f = mysqli_fetch_assoc($productos_lista)): ?>
                                        <option value="<?php echo $f['id']; ?>">
                                            <?php echo htmlspecialchars($f['nombre']) . " ($" . $f['precio'] . ")"; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Cantidad</label>
                                <input type="number" name="cantidad" class="form-control" value="1" min="1" required>
                            </div>
                            <button type="submit" name="agregar" class="btn btn-primary w-100">Añadir al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h5 class="mb-3">Resumen de Venta Actual</h5>
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Refacción</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $total_acumulado = 0;
                            if(!empty($_SESSION['carrito'])):
                                foreach($_SESSION['carrito'] as $idx => $item): 
                                    $total_acumulado += $item['subtotal'];
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['producto']); ?></td>
                                    <td class="text-center"><?php echo $item['cantidad']; ?></td>
                                    <td class="text-end">$<?php echo number_format($item['subtotal'], 2); ?></td>
                                    <td class="text-center">
                                        <a href="ventas.php?eliminar=<?php echo $idx; ?>" class="btn btn-sm btn-outline-danger">×</a>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center text-muted py-4">El carrito está vacío</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <?php if(!empty($_SESSION['carrito'])): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded">
                                <h4 class="mb-0">TOTAL:</h4>
                                <h3 class="mb-0 text-primary fw-bold">$<?php echo number_format($total_acumulado, 2); ?></h3>
                            </div>
                            <form method="POST" class="mt-3">
                                <button type="submit" name="finalizar" class="btn btn-success btn-lg w-100 shadow">
                                    Confirmar y Generar Ticket
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>