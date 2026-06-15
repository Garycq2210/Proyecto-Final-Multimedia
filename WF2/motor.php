<?php
session_start();
include_once("conexion.php");
$id_tramite = $_GET["id_tramite"] ?? "";
$flujo = $_GET["flujo"] ?? "";
$proceso = $_GET["proceso"] ?? "";
$decision = $_GET["decision"] ?? "";
$accion = $_GET["accion"] ?? "Siguiente";
if ($accion == "Bandeja") {
    $accion = "Siguiente";
}

if ($id_tramite == "" || $flujo == "" || $proceso == "") {
    header("Location: bandeja.php");
    exit();
}
$usuarioActual = buscarUsuario($usuarios, $_SESSION["usuario"]);

if ($usuarioActual == null) {
    header("Location: login.php");
    exit();
}

$rolUsuario = rolSistema($usuarioActual["rol"]);
$procesoActual = buscarProceso($flujo_procesos, $flujo, $proceso);

if ($procesoActual == null) {
    header("Location: bandeja.php");
    exit();
}

$rolProcesoActual = rolSistema($procesoActual["rol"]);
if ($rolUsuario != $rolProcesoActual) {
    header("Location: bandeja.php");
    exit();
}
if ($accion == "Anterior") {

    // Guardar lo escrito en la pantalla actual como borrador
    // OJO: No cierra el proceso, solo guarda los datos
    $datosPantalla = $_GET;

    unset($datosPantalla["id_tramite"]);
    unset($datosPantalla["flujo"]);
    unset($datosPantalla["proceso"]);
    unset($datosPantalla["accion"]);

    // No borramos "decision" para que también se recuerde el select
    // unset($datosPantalla["decision"]);

    for ($i = 0; $i < count($flujo_seguimiento); $i++) {
        if (
            isset($flujo_seguimiento[$i]["id_tramite"]) &&
            $flujo_seguimiento[$i]["id_tramite"] == $id_tramite &&
            $flujo_seguimiento[$i]["flujo"] == $flujo &&
            $flujo_seguimiento[$i]["proceso"] == $proceso &&
            estaPendiente($flujo_seguimiento[$i]["fecha_fin"])
        ) {
            $flujo_seguimiento[$i]["datos"] = $datosPantalla;
            break;
        }
    }

    guardarJSON("flujo_seguimiento.json", $flujo_seguimiento);


    // Buscar proceso anterior
    $anterior = buscarAnterior($flujo_procesos, $flujo_condicionante, $flujo, $proceso);

    if ($anterior == "" || $anterior == "-" || strtolower($anterior) == "null") {
        header("Location: bandeja.php");
        exit();
    }

    $procesoAnterior = buscarProceso($flujo_procesos, $flujo, $anterior);

    if ($procesoAnterior == null) {
        header("Location: bandeja.php");
        exit();
    }

    if (rolSistema($procesoAnterior["rol"]) != $rolUsuario) {
        header("Location: bandeja.php");
        exit();
    }

    header("Location: index.php?id_tramite=$id_tramite&flujo=$flujo&proceso=$anterior");
    exit();
}
$siguiente = buscarSiguiente($procesoActual,$flujo_condicionante,$flujo,$proceso,$decision);

$siguiente = trim((string)$siguiente);
$datosPantalla = $_GET;

unset($datosPantalla["id_tramite"]);
unset($datosPantalla["flujo"]);
unset($datosPantalla["proceso"]);
unset($datosPantalla["decision"]);
unset($datosPantalla["accion"]);

$usuarioFlujo = obtenerUsuarioFlujo( $flujo_seguimiento,$id_tramite,$flujo,$proceso, $_SESSION["usuario"]);

for ($i = 0; $i < count($flujo_seguimiento); $i++) {

    if (
        isset($flujo_seguimiento[$i]["id_tramite"]) &&
        $flujo_seguimiento[$i]["id_tramite"] == $id_tramite &&
        $flujo_seguimiento[$i]["flujo"] == $flujo &&
        $flujo_seguimiento[$i]["proceso"] == $proceso &&
        estaPendiente($flujo_seguimiento[$i]["fecha_fin"])
    ) {
        $flujo_seguimiento[$i]["fecha_fin"] = date("d/m/Y H:i:s");
        $flujo_seguimiento[$i]["datos"] = $datosPantalla;
        break;
    }
}
if (strtoupper(trim($procesoActual["tipo"])) == "F" || $siguiente == "" || $siguiente == "-" || strtolower($siguiente) == "null") {
    guardarJSON("flujo_seguimiento.json", $flujo_seguimiento);
    header("Location: bandeja.php");
    exit();
}
$procesoSiguiente = buscarProceso($flujo_procesos, $flujo, $siguiente);

if ($procesoSiguiente == null) {
    guardarJSON("flujo_seguimiento.json", $flujo_seguimiento);
    header("Location: bandeja.php");
    exit();
}

if (!existePendiente($flujo_seguimiento, $id_tramite, $flujo, $siguiente, $usuarioFlujo)) {

    $flujo_seguimiento[] = array(
        "id_tramite" => $id_tramite,
        "flujo" => $flujo,
        "proceso" => $procesoSiguiente["proceso"],
        "proceso_siguiente" => $procesoSiguiente["proceso_siguiente"],
        "fecha_ini" => date("d/m/Y H:i:s"),
        "fecha_fin" => "-",
        "usuario" => $usuarioFlujo,
        "datos" => array()
    );
}
guardarJSON("flujo_seguimiento.json", $flujo_seguimiento);
$rolProcesoSiguiente = rolSistema($procesoSiguiente["rol"]);
if ($rolProcesoSiguiente == $rolUsuario) {
    header("Location: index.php?id_tramite=$id_tramite&flujo=$flujo&proceso=$siguiente");
    exit();
}
header("Location: bandeja.php");
exit();

?>