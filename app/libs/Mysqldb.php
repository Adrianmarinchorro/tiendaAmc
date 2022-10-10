<?php

/*
 * Manejo de la base de datos de Mysql
 */

class Mysqldb
{

    //datos de la conexion
    private $host = 'mysql';
    private $user = 'default';
    private $pass = 'secret';
    private $dbName = 'proyecto12';

    //Atributos
    private static $instancia = null;
    private $db = null;


    //construct aqui se establece lo necesario para hacer
    //conexion con la base de datos
    private function __construct()
    {
        $options = [
            // trae la informacion de la base de datos en forma de objetos
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            // cuando se produzca un error se haga de tipo warning
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        ];

        try{

            $this->db = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbName,
                $this->user,
                $this->pass,
                $options
            );

        } catch(PDOException $error){

            exit('La base de datos no esta accesible');

        }




    }

    // metodo estatico para poder ser llamado desde fuera de la clase y asi
    //poder acceder al construct
    public static function getInstance(): Mysqldb
    {

        // llamar a una variable estatica de la clase
        if (is_null(self::$instancia))
        {
            // objeto de la propia clase llamandose a si misma con self
            self::$instancia = new Mysqldb();


        }
            //Devuelve el objeto
            return self::$instancia;


    }

    // devuelve la conexion a la base de datos
    public function getDatabase(): PDO
    {

        return $this->db;

    }

}