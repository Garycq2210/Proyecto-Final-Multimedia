<?php
include_once("conexion.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["username"];
    $clave = $_POST["password"];
    foreach ($usuarios as $usuario) {
        if ($usuario["nombre"] == $nombre && $usuario["clave"] == $clave) {
            $_SESSION["usuario"] = $usuario["id"];
            header("Location: bandeja.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - Record Académico</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: rgba(56, 189, 248, 0.15);
            border: 1px solid rgba(56, 189, 248, 0.4);
            color: #38bdf8;
            text-decoration: none;
            font-weight: bold;
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .btn-volver:hover {
            background: #38bdf8;
            color: #0f172a;
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(56, 189, 248, 0.4);
        }

        .btn-volver:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-header">
                <h1>Record Académico</h1>
                <p>Sistema de Solicitud y Seguimiento</p>
            </div>
            <form action="login.php" method="post" class="login-form">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" placeholder="Ingrese su nombre" required>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                <input type="submit" value="Ingresar">
            </form>
            <a href="../WorkFlow.html" class="btn-volver">
                Volver al Dashboard
            </a>
            <h1>Datos de Inicio de Sesion</h1>
            <br>
            <p>Kardex - Usuario: Jose - Contraseña: 123</p>
            <br>
            <p>Estudiante - Usuario: Pedro - Contraseña: 123</p>
        </div>
    </div>
</body>

</html>