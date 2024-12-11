<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php 
    include_once __DIR__ . "/../template/alertas.php";
?>
<form method="post" class="formulario" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input value="<?php echo s($usuario->nombre) ?>" type="text" id="nombre" placeholder="Tu nombre" name="nombre">
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input value="<?php echo s($usuario->apellido) ?>" type="text" id="apellido" placeholder="Tu apellido" name="apellido">
    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input value="<?php echo s($usuario->telefono) ?>" type="tel" id="telefono" placeholder="Tu teléfono" name="telefono">
    </div>
    <div class="campo">
        <label for="email">E-mail</label>
        <input value="<?php echo s($usuario->email) ?>" type="email" id="email" placeholder="Tu apellido" name="email">
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Tu Password" name="password">
    </div>
    <div class="campo-boton">
        <input type="submit" class="boton" value="Crear Usuario">
    </div>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Session</a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>