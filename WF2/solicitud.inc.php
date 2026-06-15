<?php
$datosProceso = $datosProceso ?? array();
?>
<h2>Solicitud de Record Académico</h2>

<label>Motivo de la solicitud:</label>
<input type="text" name="motivo" value="<?php echo valorCampo($datosProceso, 'motivo'); ?>">

<br><br>