<h2>Actualizar notas</h2>
<p>Ventana conceptual de Kardex para actualizar o verificar las notas de los estudiantes.</p>
<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= limpiar($seguimiento['seguim']) ?>">
    <button type="submit">Proceso finalizado, pasar al siguiente</button>
</form>
