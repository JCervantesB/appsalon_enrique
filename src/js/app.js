let paso = 1;
const pasoInicio = 1;
const pasoFin = 3;
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: [],
}

document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
})

function iniciarApp() {
    mostrarSeccion()// muestra y oculta las secciones
    tabs();// cambia la seccion cuando se presione los tabs
    botonesPaginador(); // agrega o quita los botones 
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();//consulta la api en el backend de php

    idCliente()
    nombreCliente();//añade el nombre del cliente al objeto de cita
    seleccionarFecha();//añade la fecha de la cita en el objeto
    seleccionarHora();//añade la hora de la cita en el objeto

    mostrarResumen();//muestra el resumen de la cita
}
function mostrarSeccion() {
    // ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector(`.mostrar`);
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    // seleccionar la sección con el paso ...
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // quitar los colroes actual
    const tabsAnterior = document.querySelector(`.actual`);
    if (tabsAnterior) {
        tabsAnterior.classList.remove('actual');
    }

    // colorear tabs 
    const tabs = document.querySelector(`[data-paso="${paso}"]`);
    tabs.classList.add('actual');

}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        })
    })
}

function botonesPaginador() {
    const pgAnterior = document.querySelector(`#anterior`);
    const pgSiguiente = document.querySelector(`#siguiente`);

    if (paso === 1) {
        pgSiguiente.classList.remove('ocultar');
        pgAnterior.classList.add('ocultar');
    } else if (paso === 3) {
        pgSiguiente.classList.add('ocultar');
        pgAnterior.classList.remove('ocultar');
        mostrarResumen();
    } else {
        pgSiguiente.classList.remove('ocultar');
        pgAnterior.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaSiguiente() {
    const pgSiguiente = document.querySelector('#siguiente');
    pgSiguiente.addEventListener('click', function () {
        if (paso >= pasoFin) return;
        paso++;

        botonesPaginador();
    })
}
function paginaAnterior() {
    const pgAnterior = document.querySelector('#anterior');
    pgAnterior.addEventListener('click', function () {
        if (paso <= pasoInicio) return;
        paso--;

        botonesPaginador();
    })

}

async function consultarAPI() {

    try {
        const url = `${location.origin}/api/servicios`;
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDIV = document.createElement('DIV');
        servicioDIV.classList.add('servicio');
        servicioDIV.dataset.idServicio = id;
        servicioDIV.onclick = function () {
            seleccionarServicio(servicio);
        };

        servicioDIV.appendChild(nombreServicio);
        servicioDIV.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDIV);

    });
}
function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //comprobar si un servicio ya fue agregado
    if (servicios.some(agregado => agregado.id === id)) {
        //eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        //agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

function idCliente() {
    cita.id = document.querySelector('#id').value;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {
        const dia = new Date(e.target.value).getUTCDay();

        if ([6, 0].includes(dia)) {
            inputFecha.value = '';
            cita.fecha = '';
            mostrarAlerta('Fines de semana no aceptamos citas', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    const alertaPrevia = document.querySelector('.alerta');

    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    document.querySelector(elemento).appendChild(alerta);

    if (desaparece) {
        setTimeout(() => {
            alerta.remove()
        }, 3000);
    }
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function (e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0];

        if (hora < 10 || hora > 18) {
            e.target.value = '';
            cita.hora = e.target.value;
            mostrarAlerta('La hora no es valido', 'error', '.formulario')
        } else {
            cita.hora = e.target.value;
        }

    })
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if (Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Hacen falta datos o servicios', 'error', '.contenido-resumen', false);
        return;
    }

    // formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-ES', opciones);

    const fechaCliente = document.createElement('P');
    fechaCliente.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCliente = document.createElement('P');
    horaCliente.innerHTML = `<span>Hora:</span> ${hora} Horas`;

    ///heading para servicios en resumen

    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    //iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textServicio = document.createElement('P');
        textServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    })


    ///heading para servicios en resumen

    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    resumen.appendChild(botonReservar);

}
async function reservarCita() {

    const { id, nombre, fecha, hora, servicios } = cita;

    const idServicio = servicios.map(servicio => servicio.id);

    const datos = new FormData();
    datos.append('usuario_id', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicio);

    try {
        // petición hacia la api
        const url = `${location.origin}/api/citas`;

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();
        if (resultado.resultado) {
            Swal.fire({
                icon: "success",
                position: "top-end",
                title: "Se cita registrada exitosamente",
                showConfirmButton: false,
                timer: 2500
            }).then(() => {
                window.location.reload();
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error 404",
            text: "Hubo un error al guardar la cita",
            footer: '<a href="#">Why do I have this issue?</a>'
        });
    }

    // console.log([...datos]) 
}