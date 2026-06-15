<?php
$datosProceso = $datosProceso ?? array();
?>
<h2>Imprimir Record Académico</h2>

<label>Número de impresión:</label>
<input type="text" name="numero_impresion" value="<?php echo valorCampo($datosProceso, 'numero_impresion'); ?>">

<br><br>

<label>Responsable de impresión:</label>
<input type="text" name="responsable_impresion" value="<?php echo valorCampo($datosProceso, 'responsable_impresion'); ?>">