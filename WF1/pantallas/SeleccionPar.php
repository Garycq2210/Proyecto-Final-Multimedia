<h2>Seleccionar paralelo</h2>
<p>Esta pantalla queda disponible si se decide usar el flujo paso a paso P9 → P10.</p>
<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?= $seguimiento['seguim'] ?? '' ?>">
    <label>Paralelo</label>
    <select name="paralelo">
        <option>A</option>
        <option>B</option>
    </select>
    <button type="submit">Continuar</button>
</form>
