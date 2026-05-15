<?php
session_start();
include("includes/conexion.php");

// Si ya tiene sesión, lo mandamos directo al dashboard
if(isset($_SESSION['usuario'])){
    header("Location: dashboard.php");
    exit();
}

if(isset($_POST['login'])){
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Usamos una consulta preparada para evitar inyecciones SQL
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($resultado->num_rows > 0){
        $datos = $resultado->fetch_assoc();

        // Verificamos la contraseña (asumiendo que usas password_hash)
        // Si tus contraseñas en la BD son texto plano, usa: if($password == $datos['password'])
        if(password_verify($password, $datos['password'])){
            $_SESSION['usuario'] = $usuario;
            
            // Cerramos la escritura de sesión para asegurar que Azure la guarde antes de redirigir
            session_write_close();
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center text-primary fw-bold mb-4">
                        Refaccionaria Campuzano
                    </h2>
                    <p class="text-center text-muted mb-4">Inicia sesión para continuar</p>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger py-2 small text-center">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Usuario</label>
                            <input 
                                type="text" 
                                name="usuario" 
                                class="form-control" 
                                placeholder="Ingresa tu usuario"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Contraseña</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                placeholder="••••••••"
                                required
                            >
                        </div>

                        <button 
                            type="submit" 
                            name="login" 
                            class="btn btn-primary btn-lg w-100 shadow-sm"
                        >
                            Entrar al Sistema
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="index.php" class="text-decoration-none small text-secondary">← Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>