<?php $fecha = $duenio['Fecha_inscripcion'] ?? null; ?>
<h2>Esperar fecha de inscripción</h2>
<p>Tu fecha asignada es <b><?= limpiar($fecha) ?></b>.</p>
<?php if (($_GET['msg'] ?? '') === 'esperar' || $fecha > hoy()): ?>
    <p class="aviso">Todavía no puedes avanzar. Vuelve cuando llegue tu fecha de inscripción.</p>
    <a class="btn sec" href="bandeja.php">Volver a bandeja</a>
<?php else: ?>
    <p class="ok">Ya puedes continuar con tu inscripción.</p>
    <form action="motor.php" method="POST">
        <input type="hidden" name="accion" value="avanzar">
        <input type="hidden" name="seguim" value="<?= limpiar($seguimiento['seguim']) ?>">
        <button type="submit">Continuar a selección de materias</button>
    </form>
<?php endif; ?>
