<?php
include_once("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarioSesion = $_SESSION['usuario'];
$usuarioActual = buscarUsuario($usuarios, $usuarioSesion);

if ($usuarioActual == null) {
    echo "Usuario no encontrado";
    exit();
}

$rolUsuario = rolSistema($usuarioActual["rol"]);

$procesosPendientes = array();
$procesosTerminados = array();

foreach ($flujo_seguimiento as $seguimiento) {

    $flujo = $seguimiento["flujo"];
    $proceso = $seguimiento["proceso"];

    $procesoInfo = buscarProceso($flujo_procesos, $flujo, $proceso);

    if ($procesoInfo != null) {

        $rolProceso = rolSistema($procesoInfo["rol"]);

        if ($rolProceso == $rolUsuario) {

            // Si es universitario, solo ve sus propios trámites
            if ($rolUsuario == "universitario" && $seguimiento["usuario"] != $usuarioSesion) {
                continue;
            }

            $fila = array(
                "id_tramite" => $seguimiento["id_tramite"] ?? "",
                "flujo" => $procesoInfo["flujo"],
                "proceso" => $procesoInfo["proceso"],
                "proceso_siguiente" => $procesoInfo["proceso_siguiente"],
                "tipo" => $procesoInfo["tipo"],
                "rol" => $procesoInfo["rol"],
                "fecha_ini" => $seguimiento["fecha_ini"],
                "fecha_fin" => $seguimiento["fecha_fin"],
                "usuario" => $seguimiento["usuario"],
                "datos" => $seguimiento["datos"] ?? array()
            );

            if (estaPendiente($seguimiento["fecha_fin"])) {
                $procesosPendientes[] = $fila;
            } else {
                $procesosTerminados[] = $fila;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bandeja</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="bandeja-body">

<div class="bandeja-container">

    <div class="bandeja-header">
        <h1>Bandeja de Procesos</h1>
        <p>Sistema de Solicitud de Record Académico</p>
    </div>

    <div class="usuario-card">
        <div>
            <strong>Usuario:</strong>
            <?php echo $usuarioActual["nombre"] . " " . $usuarioActual["paterno"] . " " . $usuarioActual["materno"]; ?>
        </div>

        <div>
            <strong>Rol:</strong>
            <?php echo $usuarioActual["rol"]; ?>
        </div>
    </div>

    <?php if ($rolUsuario == "universitario") { ?>
        <div class="acciones-superiores">
            <a class="btn-nuevo" href="iniciar_tramite.php">
                + Nuevo trámite de Record Académico
            </a>
        </div>
    <?php } ?>

    <div class="tabla-card">
        <h2>Procesos Pendientes</h2>

        <?php if (count($procesosPendientes) > 0) { ?>

            <div class="tabla-responsive">
                <table class="tabla-procesos">
                    <tr>
                        <th>Trámite</th>
                        <th>Flujo</th>
                        <th>Proceso</th>
                        <th>Tipo</th>
                        <th>Rol</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Acción</th>
                    </tr>

                    <?php foreach ($procesosPendientes as $p) { ?>
                        <tr>
                            <td><?php echo $p["id_tramite"]; ?></td>
                            <td><?php echo $p["flujo"]; ?></td>
                            <td><?php echo $p["proceso"]; ?></td>
                            <td><?php echo $p["tipo"]; ?></td>
                            <td><?php echo $p["rol"]; ?></td>
                            <td><?php echo $p["fecha_ini"]; ?></td>
                            <td><?php echo $p["fecha_fin"]; ?></td>
                            <td>
                                <a class="btn-atender" href="index.php?id_tramite=<?php echo $p["id_tramite"]; ?>&flujo=<?php echo $p["flujo"]; ?>&proceso=<?php echo $p["proceso"]; ?>">
                                    Atender
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

        <?php } else { ?>

            <div class="mensaje-vacio">
                No hay procesos pendientes para este usuario.
            </div>

        <?php } ?>
    </div>

    <div class="tabla-card">
        <h2>Procesos Terminados</h2>

        <?php if (count($procesosTerminados) > 0) { ?>

            <div class="tabla-responsive">
                <table class="tabla-procesos tabla-terminados">
                    <tr>
                        <th>Trámite</th>
                        <th>Flujo</th>
                        <th>Proceso</th>
                        <th>Tipo</th>
                        <th>Rol</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                    </tr>

                    <?php foreach ($procesosTerminados as $p) { ?>
                        <tr>
                            <td><?php echo $p["id_tramite"]; ?></td>
                            <td><?php echo $p["flujo"]; ?></td>
                            <td><?php echo $p["proceso"]; ?></td>
                            <td><?php echo $p["tipo"]; ?></td>
                            <td><?php echo $p["rol"]; ?></td>
                            <td><?php echo $p["fecha_ini"]; ?></td>
                            <td><?php echo $p["fecha_fin"]; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

        <?php } else { ?>

            <div class="mensaje-vacio">
                No hay procesos terminados para este usuario.
            </div>

        <?php } ?>
    </div>

    <div class="cerrar-sesion">
        <button onclick="window.location.href='login.php'">
            Cerrar Sesión
        </button>
    </div>

</div>

</body>
</html>