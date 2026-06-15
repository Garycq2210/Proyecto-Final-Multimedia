<?php
$datosProceso = $datosProceso ?? array();
?>
<h2>Devolver documentos</h2>

<label>Observación de entrega:</label>
<input type="text" name="observacion_entrega" value="<?php echo valorCampo($datosProceso, 'observacion_entrega'); ?>">