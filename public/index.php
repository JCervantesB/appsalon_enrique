<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\APIcontroller;
use Controllers\CitaController;
use Controllers\LoginController;
use Controllers\ServicioController;
use MVC\Router;

$router = new Router();

// inicio sesión 
$router->get('/',[LoginController::class, 'login']);
$router->post('/',[LoginController::class, 'login']);
// cerrar sesión 
$router->get('/logout',[LoginController::class, 'logout']);

// recuperar password 
$router->get('/olvide',[LoginController::class, 'olvide']);
$router->post('/olvide',[LoginController::class, 'olvide']);
$router->get('/recuperar',[LoginController::class, 'recuperar']);
$router->post('/recuperar',[LoginController::class, 'recuperar']);

// crear cuenta 
$router->get('/crear-cuenta',[LoginController::class, 'crear']);
$router->post('/crear-cuenta',[LoginController::class, 'crear']);

// Confirmar cuenta 
$router->get('/confirmar-cuenta',[LoginController::class, 'confirmar']);
$router->get('/mensaje',[LoginController::class, 'mensaje']);

// area privada 
$router->get('/cita',[CitaController::class, 'index']);
$router->get('/admin',[AdminController::class, 'index']);

// API de citas 
$router->get('/api/servicios',[APIcontroller::class,'index']) ;
$router->post('/api/citas',[APIcontroller::class,'guardar']) ;
$router->post('/api/eliminar',[APIcontroller::class,'eliminar']) ;

// CRUD
$router->get('/servicios',[ServicioController::class,'index']) ;
$router->get('/servicios/crear',[ServicioController::class,'crear']) ;
$router->post('/servicios/crear',[ServicioController::class,'crear']) ;
$router->get('/servicios/actualizar',[ServicioController::class,'actualizar']) ;
$router->post('/servicios/actualizar',[ServicioController::class,'actualizar']) ;
$router->post('/servicios/eliminar',[ServicioController::class,'eliminar']) ;


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();