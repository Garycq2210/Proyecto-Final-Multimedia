<h1>Selección de materias</h1>

<p>
    Seleccione las materias que desea inscribir.
</p>

<form>
    <label>
        <input type="checkbox" checked>
        Programación 1
    </label>
    <br>

    <label>
        <input type="checkbox" checked>
        Programación Web
    </label>
</form>

<p>
    Las materias Base de Datos y Cálculo tienen cupos llenos, por eso no aparecen disponibles para selección.
</p>

<form action="motor.php" method="POST" onsubmit="return confirm('¿Está seguro de finalizar su inscripción?');">
    <input type="hidden" name="accion" value="finalizar_inscripcion">
    <input type="hidden" name="seguim" value="<?php echo htmlspecialchars($seguim['seguim']); ?>">

    <button class="btn-success" type="submit">
        Finalizar inscripción
    </button>
</form>

<br>

<a class="btn btn-secondary" href="bandeja.php">Volver a bandeja</a>