<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $auth = new Usuario();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->sincronizar($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                //Comprobar que existe el usuario
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario) {
                    //Verificar el password
                    if ($usuario->comprobarPV($auth->password)) {
                        //autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre." ".$usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                    $auth->email = null;
                }
            }
        }
        $alertas = Usuario::getAlertas();

        return $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth,
        ]);
    }
    public static function logout()
    {
        session_start();
        $_SESSION=[];

        header('location: /');
    }
    public static function olvide(Router $router)
    {
        $alertas=[];
        
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth=new Usuario($_POST);
            $alertas=$auth->validarEmail();
            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado==='1'){
                    //generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar el email
                    $email=new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();
                    
                    //Alerta de éxito
                    Usuario::setAlerta('exito','Revisar tu E-mail');
 

                }else{
                    Usuario::setAlerta('error', 'Email no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();


        $router->render('auth/olvide-password',[
            'alertas'=>$alertas,
        ]);
    }
    public static function recuperar(Router $router)
    {
        $alertas=[];
        $token=s($_GET['token']);
        $error=false;

        //buscar usuario por su token
        $usuario = Usuario::where('token',$token);
        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
            $error=true;
        }

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $newPassword=new Usuario($_POST);
            $alertas=$newPassword->validarPassword();
            if(empty($alertas)){
                $usuario->password=$newPassword->password;
                $usuario->hashPassword();
                $usuario->token=null;

                $resultado=$usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }
        // debuguear($usuario);
        $alertas = Usuario::getAlertas();

        $router->render('auth/restablecer',[
            'alertas'=>$alertas,
            'error'=>$error,
        ]);
    }
    public static function crear(Router $router)
    {
        $usuario = new Usuario();

        //alertas vacías
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //revisar que alerta este vació
            if (empty($alertas)) {
                //verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //hasear el password
                    $usuario->hashPassword();

                    //generar un token único
                    $usuario->crearToken();

                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        return $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }

    public static function mensaje(Router $router)
    {
        return $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            //modificar a usuario confirmado
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada correctamente');
        }
        //obtener alertas
        $alertas = Usuario::getAlertas();
        return $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas,
        ]);
    }
}
