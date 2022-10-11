<?php

class LoginController extends Controller
{
    //aqui la variable almacena un objeto de la clase del modelo
    private $model;

    public function __construct()
    {
        // cuando se llame al constructor se asignara la variable  con el metodo model del padre (Controller) que recibe el nombre
        $this->model = $this->model('Login');
    }

    public function index()
    {
        //si existe
        if( isset($_COOKIE['shoplogin'])) {

            $value = explode('|', $_COOKIE['shoplogin']);

            $dataForm = [

                'user' => $value[0],
                'password' => $value[1],
                'remember' => 'on',

            ];
        } else {

            $dataForm = null;
        }




        // los datos que se almacenara informacion de la vista como titulo si se muestra el menu, etc.
        $data = [

            //añado el titulo que se le quiere añadir que recibira luego
            'titulo' => 'Login',
            //falso si no quiero que en la vista se vea el menu.
            'menu' => false,
            'data' => $dataForm,

        ];


        //lamada a la vista
        $this->view('login', $data);
    }

    public function olvido()
    {

        $errors = [];

        if($_SERVER['REQUEST_METHOD'] != 'POST') {

            $this->showForgetForm();
            return;

        }

        $email = $_POST['email'] ?? '';

        if($email == ''){
            $errors[] = 'El email es requerido';
        }

        if( ! filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'El correo electronico no es valido';
        }

        if(count($errors) == 0){

            if( ! $this->model->existsEmail($email)) {
                $errors[] =  'El correo electronico no existe en la base de datos';

            } else {

                if($this->model->sendEmail($email)){

                    $data = [
                        'titulo' => 'Cambio de contraseña de acceso',
                        'menu' => false,
                        'errors' => [],
                        'subtitle' => 'Cambio de contraseña de acceso',
                        'text' => 'Se ha enviado un correo a <b>' . $email . '</b> para que pueda cambiar su clave de acceso.<br>No olvide revisar la carpeta de spam.<br>Cualquier duda que tenga puede comunicarse con nosotros.<br>',
                        'color' => 'alert-success',
                        'url' => 'login',
                        'colorButton' => 'btn-success',
                        'textButton' => 'Regresar',
                    ];

                    $this->view('mensaje', $data);

                } else {

                    $data = [
                        'titulo' => 'Error con el correo',
                        'menu' => false,
                        'errors' => [],
                        'subtitle' => 'Error en el envio del correo electronico',
                        'text' => 'Existio un problema al enviar el correo electronico.<br> Por favor, pruebe dentro de 5 minutos.<br>',
                        'color' => 'alert-danger',
                        'url' => 'login',
                        'colorButton' => 'btn-danger',
                        'textButton' => 'Regresar',
                    ];

                    $this->view('mensaje', $data);

                }
            }

        }

        if(count($errors) > 0){

            $data = [
                'titulo' => 'Olvido de la contraseña',
                'menu' => false,
                'errors' => $errors,
                'subtitle' => '¿Olvidaste la contraseña?',
                ];

            $this->view('olvido', $data);

        }

    }

    public function showForgetForm()
    {
        $data = [
            'titulo' => 'Olvido de la contraseña',
            'menu' => false,
            'errors' => [],
            'subtitle' => '¿Olvidaste la contraseña?',
        ];

        $this->view('olvido', $data);


    }

    public function registro()
    {

        $errors = [];
        $dataForm = [];

        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->showRegisterForm();
            return;
        }

        //Procesamos la informacion recibida del formulario
        $firstName = $_POST['firstName'] ?? '';
        $lastName1 = $_POST['lastName1'] ?? '';
        $lastName2 = $_POST['lastName2'] ?? '';
        $email = $_POST['email'] ?? '';
        $password1 = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $postcode = $_POST['postcode'] ?? '';
        $country = $_POST['country'] ?? '';

        $dataForm = [
            'firstName' => $firstName,
            'lastName1' => $lastName1,
            'lastName2' => $lastName2,
            'email' => $email,
            'password' => $password1,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'postcode' => $postcode,
            'country' => $country,
            ];

        if($firstName == ''){
            $errors[] = 'El nombre es requerido';
        }
        if($lastName1 == ''){
            $errors[] = 'El primer apellido es requerido';
        }
        if($lastName2 == ''){
            $errors[] = 'El segundo apellido es requerido';
        }
        if($email ==''){
            $errors[] =  'El email es requerido';
        }
        if($password1 == ''){
            $errors[] =  'La contraseña es requerido';
        }
        if($password2 == ''){
            $errors[] =  'La contraseña repetida es requerido';
        }
        if($address == ''){
            $errors[] = 'La direccion es requerido';
        }
        if($city == ''){
            $errors[] = 'La ciudad es requerido';
        }
        if($state == ''){
            $errors[] =  'La provincia es requerido';
        }
        if($postcode == ''){
            $errors[] =  'El codigo postal es requerido';
        }
        if($country == ''){
            $errors[] =  'El pais es requerido';
        }
        if($password1 != $password2) {
            $errors[] = 'Las contraseñas deben ser iguales';
        }

        if(count($errors) == 0) {

            // enviamos a la base de datos la informacion
            //aqui llamamos al modelo que interactua con la base de datos.
            if($this->model->createUser($dataForm)) {

                //en la url va el nombre del controller
                $data = [
                    'titulo' => 'Bienvenido',
                    'menu' => false,
                    'errors' => [],
                    'subtitle' => 'Bienvenido/a a nuestra tienda online',
                    'text' => 'Gracias por su registro',
                    'color' => 'alert-success',
                    'url' => 'menu',
                    'colorButton' => 'btn-success',
                    'textButton' => 'Acceder',
                ];

                $this->view('mensaje', $data);

            } else {

                $data = [
                    'titulo' => 'Error',
                    'menu' => false,
                    'errors' => [],
                    'subtitle' => 'Error en el proceso de registro',
                    'text' => 'Probablemente el correo ya exista',
                    'color' => 'alert-danger',
                    'url' => 'login',
                    'colorButton' => 'btn-danger',
                    'textButton' => 'Regresar',
                ];

                $this->view('mensaje', $data);
            }

        } else {
           // var_dump($_POST);
           $data = [
                'titulo' => 'Registro',
                'menu' => false,
                'errors' => $errors,
                'dataForm' => $dataForm,

            ];

         $this->view('register', $data);

        }

    }

    public function showRegisterForm()
    {

        $data = [

            'titulo' => 'Registro',
            'menu' => false,

        ];

        $this->view('register', $data);

    }

    public function  changePassword($id)
    {
        $errors = [];

        if($_SERVER['REQUEST_METHOD'] != 'POST') {

            $this->showChangePassForm($id);
            return;

        }

       $id = $_POST['id'] ?? '';
       $password1 = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if($id == ''){
            array_push($errors, 'El usuario no existe');
        }

        if($password1 == ''){
            array_push($errors, 'La contraseña es requerida');
        }
        if($password2 == ''){
            array_push($errors, 'Repetir contraseña es requerida');
        }

        if($password1 != $password2){
            array_push($errors, 'Ambas contraseñas deben ser iguales');
        }

        // es 0 = false, 1 = true
        if(count($errors)){

            $data = [
                'titulo' => 'Cambiar contraseña',
                'menu' => false,
                'errors' => $errors,
                'data' => $id,
                'subtitle' => 'Cambie su contraseña de acceso',
            ];

            $this->view('changePassword', $data);

        } else {

            if($this->model->changePassword($id, $password1)) {

                $data = [

                    'titulo' => 'Cambiar contraseña',
                    'menu' => false,
                    'errors' => [],
                    'subtitle' => 'Modificacion de la contraseña de acceso',
                    'text' => 'La contraseña ha sido cambiada correctamente.<br>Bienvenido de nuevo.',
                    'color' => 'alert-success',
                    'url' => 'login',
                    'colorButton' => 'btn-success',
                    'textButton' => 'Regresar',

                    ];

                $this->view( 'mensaje', $data);

            } else {

                $data = [

                    'titulo' => 'Error al cambiar contraseña',
                    'menu' => false,
                    'errors' => [],
                    'subtitle' => ' Error al Modificar la contraseña de acceso',
                    'text' => 'Existio un errror al modificar la clave de acceso.',
                    'color' => 'alert-danger',
                    'url' => 'login',
                    'colorButton' => 'btn-danger',
                    'textButton' => 'Regresar',
                ];

                $this->view( 'mensaje', $data);

            }
        }

    }


    public function showChangePassForm($id)
    {
        $data = [

            'titulo' => 'Cambiar contraseña',
            'menu' => false,
            'data' => $id,
            'subtitle' => 'Cambia su contraseña de acceso',
        ];

        $this->view('changePassword', $data);

    }

    public function  verifyUser()
    {

        $errors = [];

        if( $_SERVER['REQUEST_METHOD'] != 'POST'){

            $this->index();
            return;
        }


        $user = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';
        // si existe 'on', si no existe 'off'.
        $remember = isset($_POST['remember']) ? 'on' : 'off';

        if(!$this->model->getUserByEmail($user)){

            $errors[] = 'El usuario no existe';

            $dataForm = [
                'user' => $user,
                'password' => $password,
                'remember' => $remember,
            ];

            $data = [
                'titulo' => 'Login',
                'menu' => false,
                'errors' => $errors,
                'data' => $dataForm,
            ];

            $this->view('login', $data);

            return;

        }


        $client = $this->model->getUserByEmail($user);

        $errors = $this->model->verifyUser($client, $password);

        $value = $user . '|' . $password;

        if($remember == 'on'){

            $date = time() + (60 * 60 * 24 * 7);
        } else {

            // al poner un tiempo pasado, borra la coockie (la que hubiera).
            $date = time() - 1;
        }

        setcookie('shoplogin', $value, $date, dirname(__DIR__) . ROOT);

        $dataForm = [
            'user' => $user,
            'password' => $password,
            'remember' => $remember,
        ];

        if(! $errors ) {

            $data = $this->model->getUserByEmail($user);

            $session = new Session();

            //le pasamos toda la fila de la tabla de usuarios
            $session->login($data);

            //redireccion forzada
            header("location: " . ROOT . 'shop');

        } else {

            $data = [
                'titulo' => 'Login',
                'menu' => false,
                'errors' => $errors,
                'data' => $dataForm,
            ];

            $this->view('login', $data);

        }
    }
}