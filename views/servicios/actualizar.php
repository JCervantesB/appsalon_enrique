<h1 class="nombre-pagina">Actualizar Servicios</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php include_once __DIR__ . '/../template/barra.php' ?>
<?php include_once __DIR__ . "/../template/alertas.php";?>
<form method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php' ?>
    <input type="submit" class="boton" value="Actualizar Servicio">
</form>