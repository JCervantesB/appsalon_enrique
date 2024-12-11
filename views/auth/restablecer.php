<h1 class="nombre-pagina">Restablecer Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password</p>

<?php
include_once __DIR__ . "/../template/alertas.php";
?>

<?php if ($error) return  ?>
<form class="formulario" method="post" >
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Tu nuevo Password" name="password">
    </div>
    <div class="campo-boton">
        <input type="submit" class="boton" value="Iniciar Session">
    </div>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Session.</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Obtener una. </a>
</div>