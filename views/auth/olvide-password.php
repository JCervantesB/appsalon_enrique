<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Restablecer tu password escribir tu email a continue</p>

<?php 
    include_once __DIR__ . "/../template/alertas.php";
?>
<form method="post" class="formulario" action="/olvide">

    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" placeholder="Tu apellido" name="email">
    </div>
    <div class="campo-boton">
        <input type="submit" class="boton" value="Enviar Instrucciones">
    </div>
</form>


<div class="acciones">
    <a href="/" >¿Ya tienes una cuenta? Inicia Session</a>
    <a href="/crear-cuenta" >¿Aun no tienes una cuenta?</a>
</div>