<?php
date_default_timezone_set("America/La_Paz");
function leerJSON($archivo)
{
    $ruta = __DIR__ . "/" . $archivo;

    if (!file_exists($ruta)) {
        return array();
    }

    $contenido = file_get_contents($ruta);
    $datos = json_decode($contenido, true);

    if ($datos == null) {
        return array();
    }

    return $datos;
}
function guardarJSON($archivo, $datos)
{
    $ruta = __DIR__ . "/" . $archivo;

    $json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($ruta, $json);
}
$flujo_procesos = leerJSON("flujo_procesos.json");
$flujo_condicionante = leerJSON("flujo_proceso_condicionante.json");
$flujo_seguimiento = leerJSON("flujo_seguimiento.json");
$usuarios = leerJSON("usuarios.json");
function rolSistema($rol)
{
    $rol = strtolower(trim($rol));

    if ($rol == "estudiante") {
        return "universitario";
    }

    return $rol;
}
function estaPendiente($fecha)
{
    $fecha = strtolower(trim((string)$fecha));

    return $fecha == "-" || $fecha == "" || $fecha == "null";
}
function buscarUsuario($usuarios, $id)
{
    foreach ($usuarios as $u) {
        if ($u["id"] == $id) {
            return $u;
        }
    }

    return null;
}
function buscarProceso($flujo_procesos, $flujo, $proceso)
{
    foreach ($flujo_procesos as $fp) {
        if ($fp["flujo"] == $flujo && $fp["proceso"] == $proceso) {
            return $fp;
        }
    }

    return null;
}
function buscarSiguiente($procesoActual, $flujo_condicionante, $flujo, $proceso, $decision)
{
    if ($procesoActual["tipo"] == "C") {

        if ($decision == "") {
            echo "Debe seleccionar una decisión.";
            exit();
        }

        foreach ($flujo_condicionante as $c) {
            if ($c["flujo"] == $flujo && $c["proceso"] == $proceso) {

                if ($decision == "si") {
                    return $c["proceso_si"];
                } else {
                    return $c["proceso_no"];
                }
            }
        }

        return "";
    }

    return $procesoActual["proceso_siguiente"];
}
function buscarAnterior($flujo_procesos, $flujo_condicionante, $flujo, $proceso)
{
    foreach ($flujo_procesos as $fp) {
        if ($fp["flujo"] == $flujo && $fp["proceso_siguiente"] == $proceso) {
            return $fp["proceso"];
        }
    }

    foreach ($flujo_condicionante as $c) {
        if (
            $c["flujo"] == $flujo &&
            ($c["proceso_si"] == $proceso || $c["proceso_no"] == $proceso)
        ) {
            return $c["proceso"];
        }
    }

    return "";
}
function existePendiente($flujo_seguimiento, $id_tramite, $flujo, $proceso, $usuario)
{
    foreach ($flujo_seguimiento as $seg) {
        if (
            isset($seg["id_tramite"]) &&
            $seg["id_tramite"] == $id_tramite &&
            $seg["flujo"] == $flujo &&
            $seg["proceso"] == $proceso &&
            isset($seg["usuario"]) &&
            $seg["usuario"] == $usuario &&
            estaPendiente($seg["fecha_fin"] ?? "")
        ) {
            return true;
        }
    }

    return false;
}
function obtenerUsuarioFlujo($flujo_seguimiento, $id_tramite, $flujo, $proceso, $usuarioSesion)
{
    foreach ($flujo_seguimiento as $seg) {
        if (
            isset($seg["id_tramite"]) &&
            $seg["id_tramite"] == $id_tramite &&
            $seg["flujo"] == $flujo &&
            $seg["proceso"] == $proceso &&
            isset($seg["usuario"])
        ) {
            return $seg["usuario"];
        }
    }

    return $usuarioSesion;
}
function nuevoIdTramite($flujo_seguimiento)
{
    $mayor = 0;

    foreach ($flujo_seguimiento as $seg) {

        if (isset($seg["id_tramite"])) {

            $numero = str_replace("T", "", $seg["id_tramite"]);
            $numero = intval($numero);

            if ($numero > $mayor) {
                $mayor = $numero;
            }
        }
    }

    return "T" . ($mayor + 1);
}
function obtenerDatosProceso($flujo_seguimiento, $id_tramite, $flujo, $proceso)
{
    foreach ($flujo_seguimiento as $seg) {
        if (
            isset($seg["id_tramite"]) &&
            $seg["id_tramite"] == $id_tramite &&
            $seg["flujo"] == $flujo &&
            $seg["proceso"] == $proceso
        ) {
            return $seg["datos"] ?? array();
        }
    }

    return array();
}

function valorCampo($datos, $campo)
{
    return htmlspecialchars($datos[$campo] ?? "", ENT_QUOTES, "UTF-8");
}

function seleccionarCampo($datos, $campo, $valor)
{
    if (isset($datos[$campo]) && $datos[$campo] == $valor) {
        return "selected";
    }

    return "";
}

?>