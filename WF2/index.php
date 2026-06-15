<?php
session_start();
include_once("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$id_tramite = $_GET["id_tramite"] ?? "";
$flujo = $_GET["flujo"] ?? "";
$proceso = $_GET["proceso"] ?? "";

if ($id_tramite == "" || $flujo == "" || $proceso == "") {
    header("Location: bandeja.php");
    exit();
}

$procesoActual = null;

foreach ($flujo_procesos as $fp) {
    if ($fp["flujo"] == $flujo && $fp["proceso"] == $proceso) {
        $procesoActual = $fp;
        break;
    }
}

if ($procesoActual == null) {
    echo "Proceso no encontrado";
    exit();
}

$pantalla = trim($procesoActual["pantalla"]);
$datosProceso = obtenerDatosProceso($flujo_seguimiento, $id_tramite, $flujo, $proceso);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Record Académico</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="workflow-body">

    <div class="workflow-container">

        <div class="workflow-header">
            <h1>Sistema de Solicitud de Record Académico</h1>
            <p>Workflow académico para el seguimiento de trámites</p>
        </div>

        <div class="workflow-info">
            <div>
                <strong>Trámite:</strong> <?php echo $id_tramite; ?>
            </div>

            <div>
                <strong>Flujo:</strong> <?php echo $flujo; ?>
            </div>

            <div>
                <strong>Proceso:</strong> <?php echo $proceso; ?>
            </div>

            <div>
                <strong>Pantalla:</strong> <?php echo $pantalla; ?>
            </div>
        </div>

        <div class="workflow-card">

            <form method="GET" action="motor.php">

                <input type="hidden" name="id_tramite" value="<?php echo $id_tramite; ?>">
                <input type="hidden" name="flujo" value="<?php echo $flujo; ?>">
                <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

                <div class="pantalla-contenido">
                    <?php
                        include_once($pantalla . ".inc.php");
                    ?>
                </div>

                <div class="workflow-botones">
                    <input type="submit" name="accion" value="Anterior" class="btn-secundario">
                    <?php if ($proceso == "P2" || $proceso == "P6" || $proceso == "P7") { ?>
                    <input type="submit" name="accion" value="Bandeja" class="btn-principal">
                <?php } else { ?>
                    <input type="submit" name="accion" value="Siguiente" class="btn-principal">
                <?php } ?>
                </div>

            </form>

        </div>

        <div class="workflow-volver">
            <a href="bandeja.php">Volver a bandeja</a>
        </div>

    </div>

</body>
</html>