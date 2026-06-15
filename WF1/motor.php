<?php
session_start();

date_default_timezone_set('America/La_Paz');

define('DATA_FILE', __DIR__ . '/flujo.json');

function leer_json() {
    if (!file_exists(DATA_FILE)) {
        return [];
    }

    $contenido = file_get_contents(DATA_FILE);
    return json_decode($contenido, true) ?? [];
}

function guardar_json($datos) {
    $json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(DATA_FILE, $json);
}

function buscar_usuario($id, $clave) {
    $datos = leer_json();

    if (!isset($datos['usuarios'])) {
        return null;
    }

    $id = trim($id);
    $clave = trim($clave);

    foreach ($datos['usuarios'] as $usuario) {
        if (trim($usuario['id']) === $id && trim($usuario['clave']) === $clave) {
            return $usuario;
        }
    }

    return null;
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

function generar_id_seguimiento($datos) {
    if (!isset($datos['seguimiento']) || count($datos['seguimiento']) === 0) {
        return 'S1';
    }

    $max = 0;

    foreach ($datos['seguimiento'] as $seg) {
        $numero = intval(str_replace('S', '', $seg['seguim']));

        if ($numero > $max) {
            $max = $numero;
        }
    }

    return 'S' . ($max + 1);
}

function iniciar_flujo($usuario_id, $flujo, $proceso) {
    $datos = leer_json();

    if (!isset($datos['seguimiento'])) {
        $datos['seguimiento'] = [];
    }

    $nuevo = [
        "seguim" => generar_id_seguimiento($datos),
        "flujo" => $flujo,
        "proceso" => $proceso,
        "usuario" => $usuario_id,
        "fecha_ini" => date('Y-m-d H:i:s'),
        "fecha_fin" => null
    ];

    $datos['seguimiento'][] = $nuevo;

    guardar_json($datos);
}

function obtener_seguimiento_por_id($seguim_id) {
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

function cerrar_y_crear_siguiente($seguim_id, $siguiente_proceso) {
    $datos = leer_json();

    if (!isset($datos['seguimiento'])) {
        return false;
    }

    $seguimientoActual = null;

    foreach ($datos['seguimiento'] as $i => $seg) {
        if ($seg['seguim'] === $seguim_id) {
            $datos['seguimiento'][$i]['fecha_fin'] = date('Y-m-d H:i:s');
            $seguimientoActual = $datos['seguimiento'][$i];
            break;
        }
    }

    if ($seguimientoActual === null) {
        return false;
    }

    if ($siguiente_proceso !== null && $siguiente_proceso !== '') {
        $nuevo = [
            "seguim" => generar_id_seguimiento($datos),
            "flujo" => $seguimientoActual['flujo'],
            "proceso" => $siguiente_proceso,
            "usuario" => $seguimientoActual['usuario'],
            "fecha_ini" => date('Y-m-d H:i:s'),
            "fecha_fin" => null
        ];

        $datos['seguimiento'][] = $nuevo;
    }

    guardar_json($datos);

    return true;
}

function tiene_permiso_sobre_seguimiento($seg, $usuario_id, $rol) {
    $proceso = buscar_proceso($seg['flujo'], $seg['proceso']);

    if ($proceso === null) {
        return false;
    }

    if ($rol === 'Estudiante') {
        return $seg['usuario'] === $usuario_id && $proceso['rol'] === 'Estudiante';
    }

    if ($rol === 'Kardex') {
        return $proceso['rol'] === 'Kardex';
    }

    return false;
}

function determinar_siguiente_proceso($seg) {
    $procesoActual = buscar_proceso($seg['flujo'], $seg['proceso']);

    if ($procesoActual === null) {
        return null;
    }

    if ($seg['proceso'] === 'P6') {
        $usuario = buscar_usuario_por_id($seg['usuario']);

        if ($usuario === null || empty($usuario['Fecha_inscripcion'])) {
            return 'P7';
        }

        $hoy = date('Y-m-d');
        $fechaInscripcion = $usuario['Fecha_inscripcion'];

        if ($fechaInscripcion < $hoy) {
            return 'P7';
        }

        if ($fechaInscripcion > $hoy) {
            return 'P8';
        }

        return 'P9';
    }

    if (!isset($procesoActual['sigProceso'])) {
        return null;
    }

    if ($procesoActual['sigProceso'] === null || $procesoActual['sigProceso'] === '-') {
        return null;
    }

    return $procesoActual['sigProceso'];
}

$accion = $_REQUEST['accion'] ?? null;

if ($accion === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if ($accion === 'login') {
    $id = $_POST['id'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $usuario = buscar_usuario($id, $clave);

    if ($usuario === null) {
        header("Location: login.php?error=usuarionull");
        exit;
    }

    $_SESSION['usuario'] = $usuario;
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = $usuario['rol'];

    header("Location: bandeja.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| SEGURIDAD
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['id'];
$rol = $_SESSION['rol'];

/*
|--------------------------------------------------------------------------
| INICIAR FLUJO
|--------------------------------------------------------------------------
*/

if ($accion === 'iniciar_flujo') {

    if (usuario_ya_inicio_flujo($usuario_id)) {
        header("Location: bandeja.php?error=flujo_ya_iniciado");
        exit;
    }

    if ($rol === 'Estudiante' && !kardex_termino_p5()) {
        header("Location: bandeja.php?error=flujo_no_habilitado");
        exit;
    }

    if ($rol === 'Kardex') {
        iniciar_flujo($usuario_id, 'F1', 'P1');
        header("Location: bandeja.php");
        exit;
    }

    if ($rol === 'Estudiante') {
        iniciar_flujo($usuario_id, 'F1', 'P6');
        header("Location: bandeja.php");
        exit;
    }

    header("Location: bandeja.php?error=rol");
    exit;
}

/*
|--------------------------------------------------------------------------
| AVANZAR FLUJO
|--------------------------------------------------------------------------
*/

if ($accion === 'avanzar') {
    $seguim_id = $_POST['seguim'] ?? $_GET['seguim'] ?? '';

    $seg = obtener_seguimiento_por_id($seguim_id);

    if ($seg === null) {
        header("Location: bandeja.php?error=seguimiento_no_existe");
        exit;
    }

    if ($seg['fecha_fin'] !== null) {
        header("Location: bandeja.php?error=seguimiento_cerrado");
        exit;
    }

    if (!tiene_permiso_sobre_seguimiento($seg, $usuario_id, $rol)) {
        header("Location: bandeja.php?error=sin_permiso");
        exit;
    }

    $siguiente = determinar_siguiente_proceso($seg);

    cerrar_y_crear_siguiente($seguim_id, $siguiente);

    if ($siguiente === null) {
        header("Location: bandeja.php?msg=flujo_finalizado");
        exit;
    }

    header("Location: bandeja.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| FINALIZAR INSCRIPCIÓN
|--------------------------------------------------------------------------
*/

if ($accion === 'finalizar_inscripcion') {
    $seguim_id = $_POST['seguim'] ?? $_GET['seguim'] ?? '';

    $seg = obtener_seguimiento_por_id($seguim_id);

    if ($seg === null) {
        header("Location: bandeja.php?error=seguimiento_no_existe");
        exit;
    }

    if ($seg['fecha_fin'] !== null) {
        header("Location: bandeja.php?error=seguimiento_cerrado");
        exit;
    }

    if (!tiene_permiso_sobre_seguimiento($seg, $usuario_id, $rol)) {
        header("Location: bandeja.php?error=sin_permiso");
        exit;
    }

    cerrar_y_crear_siguiente($seguim_id, 'P12');

    header("Location: bandeja.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| RECEPCIONAR DOCUMENTOS
|--------------------------------------------------------------------------
*/

if ($accion === 'recepcionar_documentos') {
    $seguim_id = $_POST['seguim'] ?? $_GET['seguim'] ?? '';

    $seg = obtener_seguimiento_por_id($seguim_id);

    if ($seg === null) {
        header("Location: bandeja.php?error=seguimiento_no_existe");
        exit;
    }

    if ($seg['fecha_fin'] !== null) {
        header("Location: bandeja.php?error=seguimiento_cerrado");
        exit;
    }

    if ($seg['proceso'] !== 'P13') {
        header("Location: bandeja.php?error=proceso_incorrecto");
        exit;
    }

    if ($rol !== 'Kardex') {
        header("Location: bandeja.php?error=sin_permiso");
        exit;
    }

    cerrar_y_crear_siguiente($seguim_id, null);

    header("Location: bandeja.php?msg=documentos_recepcionados");
    exit;
}

header("Location: bandeja.php");
exit;
?>