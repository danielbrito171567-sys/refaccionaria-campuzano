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

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($resultado->num_rows > 0){
        $datos = $resultado->fetch_assoc();

        if(password_verify($password, $datos['password'])){
            $_SESSION['usuario'] = $usuario;
            session_write_close();
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Credenciales incorrectas.";
        }
    } else {
        $error = "Credenciales incorrectas.";
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
    <style>
        body {
            background-color: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .card-login {
            border: none;
            border-radius: 1rem;
        }
        .form-control-lg {
            border-radius: 0.5rem;
        }
        /* Ajuste para que el teclado del cel no tape el botón */
        @media (max-height: 600px) {
            body { align-items: flex-start; padding-top: 20px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-5 col-lg-4">
            <div class="card card-login shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="display-4 text-primary mb-2">🔑</div>
                        <h2 class="fw-bold h3 text-dark">Ingreso</h2>
                        <p class="text-muted small">Refaccionaria Campuzano</p>
                    </div>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger py-2 small text-center border-0 shadow-sm mb-4">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Usuario</label>
                            <input 
                                type="text" 
                                name="usuario" 
                                class="form-control form-control-lg bg-light border-0" 
                                placeholder="Usuario"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Contraseña</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control form-control-lg bg-light border-0" 
                                placeholder="••••••••"
                                required
                            >
                        </div>

                        <button 
                            type="submit" 
                            name="login" 
                            class="btn btn-primary btn-lg w-100 shadow rounded-pill py-3 fw-bold"
                        >
                            Acceder
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="index.php" class="text-decoration-none small text-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>