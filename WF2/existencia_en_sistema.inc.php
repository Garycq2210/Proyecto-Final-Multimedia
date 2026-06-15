<?php
$datosProceso = $datosProceso ?? array();
?>
<h2>Existencia en sistema</h2>

<p>¿El estudiante existe en el sistema?</p>

<select name="decision" required>
    <option value="">Seleccione</option>
    <option value="si" <?php echo seleccionarCampo($datosProceso, 'decision', 'si'); ?>>Sí existe</option>
    <option value="no" <?php echo seleccionarCampo($datosProceso, 'decision', 'no'); ?>>No existe</option>
</select>

<br><br>

<label>Observación:</label>
<input type="text" name="observacion_existencia" value="<?php echo valorCampo($datosProceso, 'observacion_existencia'); ?>">