<h2>Asignar cupos</h2>
<p>Ventana conceptual para asignar cupos a materias y paralelos.</p>
<p>Al finalizar este paso, el flujo inicial de Kardex queda cerrado.</p>
<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= $seguimiento['seguim'] ?>">
    <button type="submit">Finalizar configuración</button>
</form>
