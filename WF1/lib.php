<?php
date_default_timezone_set('America/La_Paz');
session_start();
define('DATA_FILE', __DIR__ . '/flujo.json');

function leer_datos() {
    if (!file_exists(DATA_FILE)) {
        return ['flujos' => [], 'seguimiento' => [], 'validaciones' => [], 'usuarios' => []];
    }
    $contenido = file_get_contents(DATA_FILE);
    return json_decode($contenido, true) ?: ['flujos' => [], 'seguimiento' => [], 'validaciones' => [], 'usuarios' => []];
}

function guardar_datos($datos) {
    file_put_contents(DATA_FILE, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function ahora() {
    return date('Y-m-d H:i:s');
}

function hoy() {
    return date('Y-m-d');
}

function buscar_usuario($id, $clave = null) {
    $datos = leer_datos();
    foreach ($datos['usuarios'] as $usuario) {
        if (trim($usuario['id']) === trim($id)) {
            if ($clave === null || trim($usuario['clave']) === trim($clave)) {
                return $usuario;
            }
        }
    }
    return null;
}

function obtener_usuario($id) {
    return buscar_usuario($id, null);
}

function buscar_proceso($flujo, $proceso) {
    $datos = leer_datos();
    foreach ($datos['flujos'] as $fila) {
        if ($fila['flujo'] === $flujo && $fila['proceso'] === $proceso) {
            return $fila;
        }
    }
    return null;
}

function siguiente_id_seguimiento($datos) {
    $max = 0;
    foreach ($datos['seguimiento'] as $s) {
        if (isset($s['seguim'])) {
            $n = intval(str_replace('S', '', $s['seguim']));
            if ($n > $max) $max = $n;
        }
    }
    return 'S' . ($max + 1);
}

function crear_seguimiento(&$datos, $flujo, $proceso, $usuario_id) {
    $nuevo = [
        'seguim' => siguiente_id_seguimiento($datos),
        'flujo' => $flujo,
        'proceso' => $proceso,
        'usuario' => $usuario_id,
        'fecha_ini' => ahora(),
        'fecha_fin' => null
    ];
    $datos['seguimiento'][] = $nuevo;
    return $nuevo;
}

function cerrar_seguimiento(&$datos, $seguim_id) {
    foreach ($datos['seguimiento'] as &$s) {
        if ($s['seguim'] === $seguim_id && $s['fecha_fin'] === null) {
            $s['fecha_fin'] = ahora();
            return $s;
        }
    }
    return null;
}

function seguimiento_activo_usuario($usuario_id) {
    $datos = leer_datos();
    foreach ($datos['seguimiento'] as $s) {
        if ($s['usuario'] === $usuario_id && $s['fecha_fin'] === null) {
            return $s;
        }
    }
    return null;
}

function seguimientos_visibles_bandeja($usuario) {
    $datos = leer_datos();
    $lista = [];
    foreach ($datos['seguimiento'] as $s) {
        if ($s['fecha_fin'] !== null) continue;
        $proceso = buscar_proceso($s['flujo'], $s['proceso']);
        if (!$proceso) continue;
        if ($usuario['rol'] === 'Kardex') {
            if ($s['usuario'] === $usuario['id'] || $proceso['rol'] === 'Kardex') {
                $lista[] = $s;
            }
        } else {
            if ($s['usuario'] === $usuario['id']) {
                $lista[] = $s;
            }
        }
    }
    return $lista;
}

function primer_proceso_por_rol($rol) {
    return $rol === 'Kardex' ? 'P1' : 'P6';
}

function obtener_seguimiento_activo($seguim_id) {
    $datos = leer_datos();
    foreach ($datos['seguimiento'] as $s) {
        if ($s['seguim'] === $seguim_id && $s['fecha_fin'] === null) {
            return $s;
        }
    }
    return null;
}

function requiere_login() {
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
        exit;
    }
}

function limpiar($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}   

function kardex_termino_p5() {
    $datos = leer_datos('flujo.json');

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
    $datos = leer_json('flujo.json');

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
?>
