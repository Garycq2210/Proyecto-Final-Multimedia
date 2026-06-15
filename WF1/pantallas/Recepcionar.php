<h1>Recepcionar documentos</h1>

<p>
    Kardex debe revisar los documentos físicos entregados por el estudiante.
</p>

<p>
    Cuando los documentos estén correctos, presione el botón para marcar el trámite como recepcionado.
</p>

<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="recepcionar_documentos">
    <input type="hidden" name="seguim" value="<?php echo htmlspecialchars($seguim['seguim']); ?>">

    <button class="btn-success" type="submit">
        Documentos recepcionados
    </button>
</form>

<br>

<a class="btn btn-secondary" href="bandeja.php">Volver a bandeja</a>