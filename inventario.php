<?php
// 1. Lógica y Seguridad
include("includes/auth.php");
include("includes/conexion.php");

$busqueda = "";
if (isset($_GET['buscar']) && !empty($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $termino = "%$busqueda%";
    // Consulta preparada para evitar errores en Azure
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR categoria LIKE ? OR codigo LIKE ? ORDER BY id DESC");
    $stmt->bind_param("sss", $termino, $termino, $termino);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    // Consulta simple
    $resultado = mysqli_query($conexion, "SELECT * FROM productos ORDER BY id DESC");
}

// 2. Diseño
include("includes/header.php"); 
?>

<div class="py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <h2 class="h3 mb-0 fw-bold text-dark">Inventario</h2>
        <a href="agregar-producto.php" class="btn btn-primary shadow-sm btn-lg px-4 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i>Nuevo
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="inventario.php">
                <div class="input-group">
                    <input type="text" name="busqueda" class="form-control form-control-lg border-0 bg-light" placeholder="Buscar refacción..." value="<?php echo htmlspecialchars($busqueda); ?>">
                    <button type="submit" name="buscar" class="btn btn-dark px-4">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-uppercase">
                            <th class="ps-3">Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($resultado && mysqli_num_rows($resultado) > 0): ?>
                        <?php while($fila = mysqli_fetch_assoc($resultado)){ ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($fila['nombre']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($fila['categoria']); ?></small>
                                </td>
                                <td class="fw-bold text-success">$<?php echo number_format($fila['precio'], 0); ?></td>
                                <td>
                                    <?php 
                                        $color = ($fila['stock'] <= 5) ? 'bg-danger' : 'bg-success';
                                        echo "<span class='badge $color rounded-pill'>{$fila['stock']}</span>";
                                    ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="editar-producto.php?id=<?php echo $fila['id']; ?>" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="eliminar-producto.php?id=<?php echo $fila['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No hay productos.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
include("includes/footer.php"); 
?>