<h1>Ver materias disponibles</h1>

<p>
    Estas son las materias disponibles para inscripción:
</p>

<table>
    <tr>
        <th>Materia</th>
        <th>Estado</th>
    </tr>
    <tr>
        <td>Programación 1</td>
        <td><span class="estado estado-finalizado">Disponible</span></td>
    </tr>
    <tr>
        <td>Base de Datos</td>
        <td><span class="estado estado-pendiente">Cupos llenos</span></td>
    </tr>
    <tr>
        <td>Programación Web</td>
        <td><span class="estado estado-finalizado">Disponible</span></td>
    </tr>
    <tr>
        <td>Cálculo</td>
        <td><span class="estado estado-pendiente">Cupos llenos</span></td>
    </tr>
</table>

<p>
    El sistema verificará su fecha de inscripción antes de continuar.
</p>

<pre>
Seguimiento actual: <?php echo htmlspecialchars($seguim['seguim']); ?>
</pre>

<form action="motor.php" method="POST">
    <input type="hidden" name="accion" value="avanzar">
    <input type="hidden" name="seguim" value="<?php echo htmlspecialchars($seguim['seguim']); ?>">

    <button type="submit">
        Continuar
    </button>
</form>

<br>

<a class="btn btn-secondary" href="bandeja.php">Volver a bandeja</a>