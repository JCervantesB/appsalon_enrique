<?php

namespace Model;

class Usuario extends ActiveRecord
{

    //base de datos 
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //Mensaje de validación para la creación de una cuenta

    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es Obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es Obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = 'El telefono es Obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'El password es Obligatorio';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 letras';
        }
        return self::$alertas;
    }

    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'Ingrese el email';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'Ingrese el password';
        }
        return self::$alertas;
    }

    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'Ingrese el email';
        }
        return self::$alertas;
    }

    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'Ingrese el nueva password';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 letras';
        }
        return self::$alertas;
    }
    
    //revisa si el usuario existe
    public function existeUsuario()
    {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);
        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }

    public function comprobarPV($password)
    {
        $resultado = password_verify($password, $this->password);
        if(!$resultado ||!$this->confirmado){
            self::$alertas['error'][] = 'Password incorrecto o cuenta no confirmada ';
        }else{
            return true;
        }
    }
}
