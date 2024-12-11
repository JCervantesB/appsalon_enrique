<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicio sesión con tus datos</p>

<?php 
    include_once __DIR__ . "/../template/alertas.php";
?>
<form class="formulario" method="post" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input value="<?php echo $auth->email ?>" type="email" id="email" placeholder="Tu email" name="email">

    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Tu Password" name="password">
    </div>
    <div class="campo-boton">
        <input type="submit" class="boton" value="Iniciar Session">
    </div>
</form>

<div class="acciones">
    <a href="/crear-cuenta" >¿Aun no tienes una cuenta? </a>
    <a href="/olvide" >¿Olvidaste tu Password?</a>
</div>