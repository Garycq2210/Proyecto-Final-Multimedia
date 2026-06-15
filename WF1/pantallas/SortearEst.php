<h2>Sortear estudiantes</h2>
<p>Ventana conceptual para sortear o establecer el orden de atención de los estudiantes.</p>
<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= limpiar($seguimiento['seguim']) ?>">
    <button type="submit">Proceso finalizado, pasar al siguiente</button>
</form>
