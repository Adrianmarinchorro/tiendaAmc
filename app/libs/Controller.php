<?php

class Controller
{

    // recibe  el nombre del modelo, vamos a asociar un controlador con un modelo
    public function model($model)
    {
        //lo primero que tenemos que hacer es cargar el modelo para poder crear un objeto de ese modelo.
        require_once '../app/models/' . $model . '.php';

        // ya podemos devolver una instancia del objeto
        return new $model();
    }

    // recibe el nombre de la vista que quiero mostrar (puede recibir 1 o mas de 1), puede ser llamado por 1 solo parametro (nombre de la vista)
    // o 2 (nombre de la vista y parametros).
    public function view($view, $data = [])
    {
        // si existe la vista requerimos.
        if(file_exists('../app/views/' . $view . '.php')){

            require_once('../app/views/' . $view . '.php');
        } else {
            //Cuando termine el desarrollo esto debe borrarse porque es el controlador el que debe elegir la vista a ver y este error se controla con el controller
            die('La vista no existe');
        }
    }
}