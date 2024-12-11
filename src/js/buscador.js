document.addEventListener('DOMContentLoaded',function(){
    iniciarApp();
})

function iniciarApp(){
    buscarFecha();
}

function buscarFecha(){
    const inputFecha=document.querySelector('#fecha');
    inputFecha.addEventListener('input',function(e){
        const fecha = e.target.value;
        window.location=`?fecha=${fecha}`;
    })
}
