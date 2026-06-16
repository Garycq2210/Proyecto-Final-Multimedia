<?php
session_start();

date_default_timezone_set('America/La_Paz');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

define('DATA_FILE', __DIR__ . '/flujo.json');

function buscar_usuario_por_id($id)
{
    $datos = leer_json();

    if (!isset($datos['usuarios'])) {
        return null;
    }

    foreach ($datos['usuarios'] as $usuario) {
        if ($usuario['id'] === $id) {
            return $usuario;
        }
    }

    return null;
}

function leer_json()
{
    if (!file_exists(DATA_FILE)) {
        return [];
    }

    $contenido = file_get_contents(DATA_FILE);
    return json_decode($contenido, true) ?? [];
}

function buscar_proceso($flujo, $proceso)
{
    $datos = leer_json();

    if (!isset($datos['flujos'])) {
        return null;
    }

    foreach ($datos['flujos'] as $p) {
        if ($p['flujo'] === $flujo && $p['proceso'] === $proceso) {
            return $p;
        }
    }

    return null;
}

function buscar_seguimiento($seguim_id)
{
    $datos = leer_json();

    if (!isset($datos['seguimiento'])) {
        return null;
    }

    foreach ($datos['seguimiento'] as $seg) {
        if ($seg['seguim'] === $seguim_id) {
            return $seg;
        }
    }

    return null;
}

function tiene_permiso($seguim, $proceso)
{
    $usuario_id = $_SESSION['id'];
    $rol = $_SESSION['rol'];

    if ($rol === 'Estudiante') {
        return $seguim['usuario'] === $usuario_id && $proceso['rol'] === 'Estudiante';
    }

    if ($rol === 'Kardex') {
        return $proceso['rol'] === 'Kardex';
    }

    return false;
}

$seguim_id = $_GET['seguim'] ?? '';

if ($seguim_id === '') {
    header("Location: bandeja.php?error=seguimiento_no_existe");
    exit;
}

$seguim = buscar_seguimiento($seguim_id);

if ($seguim === null) {
    header("Location: bandeja.php?error=seguimiento_no_existe");
    exit;
}

if ($seguim['fecha_fin'] !== null) {
    header("Location: bandeja.php?error=seguimiento_cerrado");
    exit;
}

$proceso = buscar_proceso($seguim['flujo'], $seguim['proceso']);

if ($proceso === null) {
    header("Location: bandeja.php?error=proceso_no_existe");
    exit;
}

if (!tiene_permiso($seguim, $proceso)) {
    header("Location: bandeja.php?error=sin_permiso");
    exit;
}

$pantalla = $proceso['pantalla'];
$archivoPantalla = __DIR__ . '/pantallas/' . $pantalla . '.php';

if (!file_exists($archivoPantalla)) {
    header("Location: bandeja.php?error=pantalla_no_existe");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pantalla); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <main class="card">

        <div class="header-bandeja">
            <div>
                <h1><?php echo htmlspecialchars($pantalla); ?></h1>
                <p>
                    Flujo: <strong><?php echo htmlspecialchars($seguim['flujo']); ?></strong> |
                    Proceso: <strong><?php echo htmlspecialchars($seguim['proceso']); ?></strong>
                </p>
            </div>

            <div>
                <a class="btn btn-secondary" href="bandeja.php">Volver a bandeja</a>
            </div>
        </div>

        <hr>
        <?php

        $duenio = buscar_usuario_por_id($seguim['usuario']);
        $seguimiento = $seguim;
        include $archivoPantalla; ?>

    </main>

</body>

</html>