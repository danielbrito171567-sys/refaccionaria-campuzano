<?php
include("includes/auth.php");
include("includes/conexion.php");

// Lógica de inserción
if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conexion->prepare("INSERT INTO productos (nombre, categoria, precio, stock, codigo, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $nombre, $categoria, $precio, $stock, $codigo, $descripcion);

    if($stmt->execute()){
        header("Location: inventario.php?msg=success");
        exit();
    } else {
        $error_msg = "Error al guardar: " . $conexion->error;
    }
}

include("includes/header.php"); 
?>

<div class="py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="inventario.php" class="btn btn-outline-secondary btn-sm me-3 rounded-circle">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="h4 mb-0 fw-bold">Nuevo Producto</h2>
    </div>

    <?php if(isset($error_msg)): ?>
        <div class="alert alert-danger shadow-sm border-0"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form method="POST">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control form-control-lg bg-light border-0" placeholder="Ej. Amortiguador Delantero" required>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Categoría</label>
                        <input type="text" name="categoria" class="form-control form-control-lg bg-light border-0" placeholder="Ej. Suspensión" required>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Código / SKU</label>
                        <input type="text" name="codigo" class="form-control form-control-lg bg-light border-0" placeholder="Código de barras" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Precio Venta</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light">$</span>
                            <input type="number" step="0.01" name="precio" class="form-control form-control-lg bg-light border-0" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Stock Inicial</label>
                        <input type="number" name="stock" class="form-control form-control-lg bg-light border-0" placeholder="0" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Descripción (Opcional)</label>
                    <textarea name="descripcion" class="form-control bg-light border-0" rows="3" placeholder="Detalles técnicos..."></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="guardar" class="btn btn-primary btn-lg py-3 rounded-pill fw-bold shadow">
                        <i class="bi bi-check-lg me-2"></i>Guardar Producto
                    </button>
                    <a href="inventario.php" class="btn btn-light btn-lg py-3 rounded-pill text-muted">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>