<?php
session_start();
include_once("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
$usuarioActual = buscarUsuario($usuarios, $_SESSION["usuario"]);

if ($usuarioActual == null) {
    header("Location: login.php");
    exit();
}
$rolUsuario = rolSistema($usuarioActual["rol"]);

if ($rolUsuario != "universitario") {
    header("Location: bandeja.php");
    exit();
}
$flujo = "F1";
$proceso = "P1";
$procesoInicial = buscarProceso($flujo_procesos, $flujo, $proceso);

if ($procesoInicial == null) {
    echo "No existe el proceso inicial.";
    exit();
}
$id_tramite = nuevoIdTramite($flujo_seguimiento);
$flujo_seguimiento[] = array(
    "id_tramite" => $id_tramite,
    "flujo" => $flujo,
    "proceso" => $procesoInicial["proceso"],
    "proceso_siguiente" => $procesoInicial["proceso_siguiente"],
    "fecha_ini" => date("d/m/Y H:i:s"),
    "fecha_fin" => "-",
    "usuario" => $_SESSION["usuario"],
    "datos" => array()
);
guardarJSON("flujo_seguimiento.json", $flujo_seguimiento);
header("Location: bandeja.php");
exit();
?>