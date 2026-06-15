<h2>Confirmar inscripción</h2>
<p>Confirma la inscripción antes de generar el comprobante.</p>
<form action="motor.php" method="POST" onsubmit="return confirm('¿Está seguro de finalizar la inscripción?');">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= limpiar($seguimiento['seguim']) ?>">
    <button type="submit">Confirmar y continuar a descarga</button>
</form>
