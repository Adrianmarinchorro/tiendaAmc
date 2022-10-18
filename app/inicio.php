<?php

//constantes iniciales
define('ROOT', DIRECTORY_SEPARATOR);
//defino la localizacion de la carpeta app
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
define('URL', '/var/www/proyecto13');
define('VIEWS', URL . APP . 'views/');
define('ENCRYPTKEY', 'elperrodesanroque');

ini_set('display_errors', 1);

//carga las clases iniciales (inlcudes o requires)
//hay que cargar antes mysql ya que app llama a la clase
require_once('libs/Mysqldb.php');
// al cargarse en el inicio podemos extender en el resto de controllers
require_once('libs/Controller.php');
require_once('libs/Application.php');
require_once ('libs/Session.php');
require_once('libs/Validate.php');
//require_once ('domain/Book.php');