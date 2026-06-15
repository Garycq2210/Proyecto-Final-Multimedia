<?php
$datosProceso = $datosProceso ?? array();
?>

<h2>Verificación de documentos</h2>

<p>¿Los documentos son válidos?</p>

<select name="decision" required>
    <option value="">Seleccione</option>
    <option value="si" <?php echo seleccionarCampo($datosProceso, 'decision', 'si'); ?>>Sí</option>
    <option value="no" <?php echo seleccionarCampo($datosProceso, 'decision', 'no'); ?>>No</option>
</select>

<br><br>

<label>Detalle de verificación:</label>
<input type="text" name="detalle_verificacion" value="<?php echo valorCampo($datosProceso, 'detalle_verificacion'); ?>">