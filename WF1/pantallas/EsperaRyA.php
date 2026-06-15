<h2>Esperar retiro y adición</h2>
<p class="error">Tu fecha de inscripción ya pasó. El sistema cierra este flujo y debes esperar el periodo de retiro y adición.</p>
<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= limpiar($seguimiento['seguim']) ?>">
    <button type="submit">Finalizar flujo</button>
</form>
