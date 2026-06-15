<h2>Establecer fechas</h2>
<p>Ventana conceptual para definir fechas de inscripción por estudiante.</p>
<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= limpiar($seguimiento['seguim']) ?>">
    <button type="submit">Proceso finalizado, pasar al siguiente</button>
</form>
