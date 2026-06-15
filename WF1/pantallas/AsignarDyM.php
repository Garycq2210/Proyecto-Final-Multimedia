<h2>Asignar día y módulo</h2>
<p>Ventana conceptual para asignar el día y módulo de inscripción.</p>
<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= limpiar($seguimiento['seguim']) ?>">
    <button type="submit">Proceso finalizado, pasar al siguiente</button>
</form>
