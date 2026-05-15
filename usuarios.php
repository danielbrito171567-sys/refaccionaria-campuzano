<?php
include("includes/auth.php");
include("includes/conexion.php");

/* AGREGAR USUARIO */
if(isset($_POST['guardar'])){
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario, $password);
    
    if($stmt->execute()){
        header("Location: usuarios.php?msg=guardado");
        exit();
    }
}

/* ELIMINAR USUARIO */
if(isset($_GET['eliminar'])){
    $id = $_GET['eliminar'];
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()){
        header("Location: usuarios.php?msg=eliminado");
        exit();
    }
}

/* EDITAR USUARIO (ACTUALIZAR) */
if(isset($_POST['actualizar'])){
    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    
    // Si la contraseña no está vacía, la actualizamos; si no, dejamos la anterior
    if(!empty($_POST['password'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("UPDATE usuarios SET usuario = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $usuario, $password, $id);
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET usuario = ? WHERE id = ?");
        $stmt->bind_param("si", $usuario, $id);
    }

    if($stmt->execute()){
        header("Location: usuarios.php?msg=actualizado");
        exit();
    }
}

/* OBTENER USUARIO A EDITAR */
$editar = null;
if(isset($_GET['editar'])){
    $id = $_GET['editar'];
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editar = $stmt->get_result()->fetch_assoc();
}

/* MOSTRAR USUARIOS */
$usuarios = mysqli_query($conexion, "SELECT id, usuario FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Refaccionaria Campuzano</title>
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
            <li class="nav-item mb-2"><a href="reportes.php" class="nav-link text-white">Reportes</a></li>
            <li class="nav-item mb-2"><a href="usuarios.php" class="nav-link text-white active bg-primary">Usuarios</a></li>
            <li class="nav-item mt-5"><a href="logout.php" class="nav-link text-danger">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="container-fluid p-4">
        <h2 class="mb-4">Administración de Usuarios</h2>

        <div class="card shadow border-0 mb-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-3"><?php echo $editar ? 'Editar Usuario' : 'Nuevo Usuario'; ?></h5>
                <form method="POST" action="usuarios.php">
                    <input type="hidden" name="id" value="<?php echo $editar['id'] ?? ''; ?>">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nombre de Usuario</label>
                            <input type="text" name="usuario" class="form-control" required value="<?php echo $editar['usuario'] ?? ''; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Contraseña <?php echo $editar ? '(dejar en blanco para no cambiar)' : ''; ?></label>
                            <input type="password" name="password" class="form-control" <?php echo $editar ? '' : 'required'; ?>>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button type="submit" name="<?php echo $editar ? 'actualizar' : 'guardar'; ?>" class="btn <?php echo $editar ? 'btn-warning' : 'btn-primary'; ?> w-100">
                                <?php echo $editar ? 'Actualizar Cambios' : 'Registrar Usuario'; ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Usuario</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($fila = mysqli_fetch_assoc($usuarios)){ ?>
                            <tr>
                                <td class="ps-3"><?php echo $fila['id']; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($fila['usuario']); ?></td>
                                <td class="text-center">
                                    <a href="usuarios.php?editar=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="usuarios.php?eliminar=<?php echo $fila['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar a este usuario?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>