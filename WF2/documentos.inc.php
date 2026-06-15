<?php
$datosProceso = $datosProceso ?? array();
?>
<h2>Preparar documentos</h2>

<label>Carnet de identidad:</label>
<input type="text" name="carnet" value="<?php echo valorCampo($datosProceso, 'carnet'); ?>">

<br><br>

<label>Observación de documentos:</label>
<input type="text" name="observacion_documentos" value="<?php echo valorCampo($datosProceso, 'observacion_documentos'); ?>">