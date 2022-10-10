<?php


/*
* La clase application maneja la url y lanza los
* procesos
*/
class Application 
{

    // nombre controller
    private $urlController = null;
    //la accion
    private $urlAction = null;
    //parametros
    private $urlParams = [];

    public function __construct()
    {

    //es como crear el objeto desde el construct
   // $db = Mysqldb::getInstance()->getDatabase(); esto aqui ya no lo necesitamos estara por tanto en el modelo

    //llama al metodo que separara la url y se la asignara a esta variable
    $this->separarUrl();

    // ver que es lo que contiene $url con => var_dump($url);

        // si  urlcontroller sea nulo (no exista, es decir la uri este vacia)
        // entra en el if
     if(! $this->urlController) {

         require_once '../app/controllers/LoginController.php';

         $page = new LoginController();
         $page->index();

         // ucfirst primera mayuscula
         // pregunto si existe ese controller file_exist()
     } elseif (file_exists('../app/controllers/' . ucfirst($this->urlController) . 'Controller.php')) {

         //En caso de que el url controller si tiene algo,
         //es decir mi uri original si trae algo

        // nombre del controller
         $controller = ucfirst($this->urlController) . 'Controller';

         require_once '../app/controllers/' . $controller . '.php';

         //hara un new del nombre de la clase.
         // y tendra el objeto de dicho controller
        $this->urlController = new $controller;


         // comprueba si el metodo existe en la clase y si se puede llamar con is callable (es decir que si es publico, pero se pasa el parametro en array)
         if( method_exists($this->urlController, $this->urlAction) &&
            is_callable(array($this->urlController, $this->urlAction))){

             // ahora queremos saber si el metodo necesita algun parametro si urlparams no esta vacio hay que llamar al metodo con parametros
             if(! empty($this->urlParams) ){

                 call_user_func_array(array($this->urlController, $this->urlAction), $this->urlParams);
             } else {

                 //se escribe asi para volver a llamar $this y puedo llamarlos porq la variable paso de ser un string (cadena uri) a ser un objeto
                 // asi se llama a un controller y dentro a un metodo con () vacios porque no recibe parametros
                 $this->urlController->{$this->urlAction}();
             }

         } else {

             //para comprobar si entra en cada if pone prints comprobando lo que llevan las variables y va imprimiendo y viendo si entra en las estructuras de control

             // En caso de que el metodo de la clase no exista o se haya escrito mal con un espacio en blanco por ejemplo
             //strlen longitud de la cadena y al no tener caracteres es vacio
             // strlen($this->urlAction) == 0 es igual a  !(strlen($this->urlAction))
             if(strlen($this->urlAction) == 0){

                 //llamara  al indice del controlador.
                 $this->urlController->index();
             } else {
                 //tratamos el error producido cuando creemos el controlador de error.
                 header('HTTP:/1.0 404 NOT FOUND');

             }

         }

         // En caso de que el controlador no existe pero la uri este llena
     } else {

         require_once '../app/controllers/LoginController.php';

         $page = new LoginController();
         $page->index();
     }

    }

    public function separarUrl(): void
    {
        if($_SERVER['REQUEST_URI'] != '/')
        {
            //Coge por ambos lados y elimina los caracteres seleccionados (principio y final)
            $url = trim($_SERVER['REQUEST_URI'], '/');

            //recbe 2 parametros y filtra una variable $url, filter_sanitize_url
            // elimina caracteres no permitidos en una url
            $url = filter_var($url, FILTER_SANITIZE_URL);

            //troceamos la URI ya que puede tener barras entre medias
            //es similar al split ya que nos devuelve un array
            $url = explode('/', $url);

            // Esto puede generar problemas por eso sacamos operador ternario
            // por eso usamos el meto isset() si existe.
            // isset($url[0]) ? $url[0] : null; ===  $url[0] ?? null;
            $this->urlController = $url[0] ?? null;
            $this->urlAction = $url[1] ?? '';

            //elimina en memoria la variable si la variable existe.
            unset($url[0], $url[1]);

            // con array values cogemos los valores y los asignamos al otro
            //para que asi no perdamos posiciones ya que el 0 y el 1 los borramos
            // y se asignan desde el principio.
            $this->urlParams = array_values($url);
        }
    }

}