<?php

namespace Controllers;

use Model\Cita;
use Model\CitasServicio;
use Model\Servicio;
use MVC\Router;

class APIcontroller{
    
    public static function index(){

        $servicos=Servicio::all();

        echo json_encode($servicos);
    }

    public static function guardar(){
        
        //almacena la cita y devuelve el ID
        $cita=new Cita($_POST);
        $resultado=$cita->guardar();
        $id=$resultado['id'];
        
        //Almacena la cita y el servicio
        $idServicios=explode(',',$_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args=[
                'cita_id'=>$id,
                'servicio_id'=>$idServicio
            ];
            $citasServicio=new CitasServicio($args);
            $citasServicio->guardar();
        }

        echo json_encode(['resultado'=>$resultado]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $id=$_POST['id'];
            $cita=Cita::find($id);
            $cita->eliminar();
            header('Location:'.$_SERVER['HTTP_REFERER']);
        }
    }
}