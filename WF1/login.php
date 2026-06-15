<?php
session_start();
if (isset($_SESSION['id'])) {
    header('Location: bandeja.php');
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card login">        
        <h1>Bienvenido al sistema</h1>
        <h2>Inicie sesión</h2>
        <?php if ($error): ?>
            <p class="error">Usuario o clave incorrectos.</p>
        <?php endif; ?>
        <form action="motor.php" method="POST">
            <input type="hidden" name="accion" value="login">
            <label>ID de usuario</label>
            <input type="text" name="id" placeholder="Ej: U1" required>
            <label>Clave</label>
            <input type="password" name="clave" placeholder="Ej: 123" required>
            <button type="submit">Ingresar</button>
        </form>
        <p class="ayuda">Usuarios de prueba: U1, U2, U4 estudiante. U3 Kardex. Clave: 123.</p>
        <a href="../WorkFlow.html">
            <button>Volver al Dashboard</button>
        </a>        
    </main>
</body>
</html>
