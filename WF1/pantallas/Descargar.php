<h1>Descargar inscripción</h1>

<p>
    Su inscripción fue generada correctamente.
</p>

<p>
    En un sistema real, aquí se descargaría el comprobante de inscripción.
</p>

<p>
    Después de descargar el comprobante, debe entregar sus documentos en Kardex.
</p>

<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?php echo htmlspecialchars($seguim['seguim']); ?>">

    <button class="btn-success" type="submit">
        Entregar documentos a Kardex
    </button>
</form>

<br>

<a class="btn btn-secondary" href="bandeja.php">Volver a bandeja</a>