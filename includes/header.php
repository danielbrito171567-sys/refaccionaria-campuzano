<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refaccionaria Campuzano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { shadow-sm: 0 2px 4px rgba(0,0,0,.075); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">🔧 Campuzano</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="inventario.php">Inventario</a></li>
                <li class="nav-item"><a class="nav-link" href="ventas.php">Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="reportes.php">Reportes</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php text-danger">Salir</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">