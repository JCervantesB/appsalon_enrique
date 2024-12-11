<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '/../template/barra.php' ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>">

        </div>
    </form>
</div>

<?php if(count($citas)===0) echo '<h2 class="nombre-pagina alerta sin-citas">No hay Citas</h2>' ?>

<div id="citas-admin">
    <ul class="citas">
        <?php $citaID = '' ?>
        <?php foreach ($citas as $key => $cita):  ?>

            <?php if ($citaID !== $cita->id):  ?>
                <?php $total = 0 ?>
                <li>
                    <p>
                        Id: <span><?php echo $cita->id; ?></span>
                    </p>
                    <p>
                        Hora: <span><?php echo $cita->hora; ?></span>
                    </p>
                    <p>
                        Cliente: <span><?php echo $cita->cliente; ?></span>
                    </p>
                    <p>
                        Email: <span><?php echo $cita->email; ?></span>
                    </p>
                    <p>
                        Teléfono: <span><?php echo $cita->telefono; ?></span>
                    </p>
                    <?php $citaID = $cita->id  ?>
                    <h3>Servicios</h3>
                <?php endif;  ?>
                <p class="servicio">
                    <span><?php echo $cita->servicio . " -$" . $cita->precio; ?></span>
                </p>
                <?php $actual = $cita->id;  ?>
                <?php $proximo = $citas[$key + 1]->id ?? 0;  ?>
                <?php $total += $cita->precio; ?>
                <?php if (evaUltimo($actual, $proximo)): ?>
                    <p class="total">
                        Total: <span><?php echo $total; ?></span>
                    </p>
                    <form  action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
                <?php endif ?>

            <?php endforeach; ?>

    </ul>
</div>

<?php 
    $script = "<script src='build/js/buscador.js'></script>" ;
?>