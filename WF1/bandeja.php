<?php
session_start();

date_default_timezone_set('America/La_Paz');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

define('DATA_FILE', __DIR__ . '/flujo.json');

function leer_json() {
    if (!file_exists(DATA_FILE)) {
        return [];
    }

    $contenido = file_get_contents(DATA_FILE);
    return json_decode($contenido, true) ?? [];
}

function buscar_usuario_por_id($id) {
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

function buscar_proceso($flujo, $proceso) {
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

function kardex_termino_p5() {
    $datos = leer_json();

    if (!isset($datos['seguimiento'])) {
        return false;
    }

    foreach ($datos['seguimiento'] as $seg) {
        if (
            $seg['flujo'] === 'F1' &&
            $seg['proceso'] === 'P5' &&
            !empty($seg['fecha_fin'])
        ) {
            return true;
        }
    }

    return false;
}

function usuario_ya_inicio_flujo($usuario_id) {
    $datos = leer_json();

    if (!isset($datos['seguimiento'])) {
        return false;
    }

    foreach ($datos['seguimiento'] as $seg) {
        if ($seg['usuario'] === $usuario_id) {
            return true;
        }
    }

    return false;
}

function estudiante_tiene_p13_pendiente($usuario_id) {
    $datos = leer_json();

    if (!isset($datos['seguimiento'])) {
        return false;
    }

    foreach ($datos['seguimiento'] as $seg) {
        if (
            $seg['usuario'] === $usuario_id &&
            $seg['proceso'] === 'P13' &&
            $seg['fecha_fin'] === null
        ) {
            return true;
        }
    }

    return false;
}

function estudiante_tiene_documentos_recepcionados($usuario_id) {
    $datos = leer_json();

    if (!isset($datos['seguimiento'])) {
        return false;
    }

    foreach ($datos['seguimiento'] as $seg) {
        if (
            $seg['usuario'] === $usuario_id &&
            $seg['proceso'] === 'P13' &&
            !empty($seg['fecha_fin'])
        ) {
            return true;
        }
    }

    return false;
}

function obtener_seguimientos_activos_para_usuario($usuario_id, $rol) {
    $datos = leer_json();
    $activos = [];

    if (!isset($datos['seguimiento'])) {
        return $activos;
    }

    foreach ($datos['seguimiento'] as $seg) {
        if (!array_key_exists('fecha_fin', $seg) || $seg['fecha_fin'] !== null) {
            continue;
        }

        $proceso = buscar_proceso($seg['flujo'], $seg['proceso']);

        if ($proceso === null) {
            continue;
        }

        if ($rol === 'Estudiante') {
            if ($seg['usuario'] === $usuario_id && $proceso['rol'] === 'Estudiante') {
                $activos[] = $seg;
            }
        }

        if ($rol === 'Kardex') {
            if ($proceso['rol'] === 'Kardex') {
                $activos[] = $seg;
            }
        }
    }

    return $activos;
}

$usuario_id = $_SESSION['id'];
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];

$seguimientosActivos = obtener_seguimientos_activos_para_usuario($usuario_id, $rol);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bandeja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="card">

    <div class="header-bandeja">
        <div>
            <h1>Bandeja de entrada</h1>
            <p>Sistema de inscripción universitaria</p>
        </div>

        <div>
            <a class="btn btn-danger" href="motor.php?accion=logout">Cerrar sesión</a>
        </div>
    </div>

    <div class="usuario-box">
        Usuario: <strong><?php echo htmlspecialchars($nombre); ?></strong><br>
        Rol: <strong><?php echo htmlspecialchars($rol); ?></strong>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'flujo_no_habilitado'): ?>
        <div class="alerta error">
            El flujo de inscripción todavía no fue habilitado por Kardex.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'flujo_ya_iniciado'): ?>
        <div class="alerta error">
            Este flujo ya fue iniciado. No puedes repetirlo.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'sin_permiso'): ?>
        <div class="alerta error">
            No tienes permiso para atender ese proceso.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'flujo_finalizado'): ?>
        <div class="alerta exito">
            El flujo fue finalizado correctamente.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'documentos_recepcionados'): ?>
        <div class="alerta exito">
            Documentos recepcionados correctamente.
        </div>
    <?php endif; ?>

    <?php if ($rol === 'Estudiante' && estudiante_tiene_p13_pendiente($usuario_id)): ?>
        <div class="alerta warning">
            Tus documentos están pendientes de recepción por Kardex.
        </div>
    <?php endif; ?>

    <?php if ($rol === 'Estudiante' && estudiante_tiene_documentos_recepcionados($usuario_id)): ?>
        <div class="alerta exito">
            Tus documentos fueron recepcionados por Kardex. El trámite de inscripción ha finalizado.
        </div>
    <?php endif; ?>

    <hr>

    <h2>Procesos pendientes</h2>

    <?php if (count($seguimientosActivos) > 0): ?>

        <table>
            <tr>
                <th>Seguimiento</th>
                <th>Flujo</th>
                <th>Proceso</th>
                <th>Pantalla</th>
                <th>Usuario trámite</th>
                <th>Fecha inicio</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>

            <?php foreach ($seguimientosActivos as $seg): ?>
                <?php
                    $proceso = buscar_proceso($seg['flujo'], $seg['proceso']);
                    $usuarioTramite = buscar_usuario_por_id($seg['usuario']);
                    $nombreTramite = $seg['usuario'];

                    if ($usuarioTramite !== null) {
                        $nombreTramite = $usuarioTramite['nombre'] . ' ' . $usuarioTramite['paterno'];
                    }
                ?>

                <tr>
                    <td><?php echo htmlspecialchars($seg['seguim']); ?></td>
                    <td><?php echo htmlspecialchars($seg['flujo']); ?></td>
                    <td><?php echo htmlspecialchars($seg['proceso']); ?></td>
                    <td><?php echo htmlspecialchars($proceso['pantalla']); ?></td>
                    <td><?php echo htmlspecialchars($nombreTramite); ?></td>
                    <td><?php echo htmlspecialchars($seg['fecha_ini']); ?></td>
                    <td>
                        <span class="estado estado-pendiente">Pendiente</span>
                    </td>
                    <td>
                        <a class="btn" href="index.php?seguim=<?php echo urlencode($seg['seguim']); ?>">
                            Abrir
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </table>

    <?php else: ?>

        <div class="alerta info">
            No tienes procesos pendientes.
        </div>

        <?php if ($rol === 'Kardex'): ?>

            <?php if (!usuario_ya_inicio_flujo($usuario_id)): ?>

                <form action="motor.php" method="POST">
                    <input type="hidden" name="accion" value="iniciar_flujo">
                    <button type="submit">Iniciar flujo Kardex</button>
                </form>

            <?php else: ?>

                <div class="alerta info">
                    El flujo de Kardex ya fue iniciado o finalizado.
                </div>

            <?php endif; ?>

        <?php elseif ($rol === 'Estudiante'): ?>

            <?php if (!kardex_termino_p5()): ?>

                <div class="alerta warning">
                    La inscripción todavía no está habilitada.
                    Primero Kardex debe completar la asignación de cupos.
                </div>

            <?php elseif (estudiante_tiene_documentos_recepcionados($usuario_id)): ?>

                <div class="alerta exito">
                    Tu inscripción ya fue finalizada y tus documentos fueron recepcionados.
                </div>

            <?php elseif (usuario_ya_inicio_flujo($usuario_id)): ?>

                <div class="alerta info">
                    Ya realizaste este flujo de inscripción. No puedes volver a iniciarlo.
                </div>

            <?php else: ?>

                <form action="motor.php" method="POST">
                    <input type="hidden" name="accion" value="iniciar_flujo">
                    <button type="submit">Iniciar flujo de inscripción</button>
                </form>

            <?php endif; ?>

        <?php endif; ?>

    <?php endif; ?>

</main>

</body>
</html>