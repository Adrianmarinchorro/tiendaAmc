<?php

class ShopController extends Controller
{

    private $model;

    public function __construct()
    {

        $this->model = $this->model('Shop');

    }

    public function index()
    {

        $session = new Session();

       // $session->redirectIfNotLogin(ROOT);

        $mostSold = $this->model->getMostSold();
        $news = $this->model->getNews();
        $user = null;

        //solved warning user not defined
        if($session->getLogin()){
            $user = $session->getUser();
        }


        //modificacion de carlos para mostrar los mas vendidos
        $data = [

            'titulo' => 'Bienvenid@ a RobaEneba',
            'menu' => true,
            'subtitle' => 'Articulos mas vendidos',
            'user' => $user,
            'data' => $mostSold,
            'subtitle2' => 'Articulos nuevos',
            'news' => $news,
        ];

        $this->view('shop/index', $data);
    }

    public function logout()
    {
        $session = new Session();
        $session->logout();
        header('location:' . ROOT);
    }

    // muestra el producto, individualmente
    public function show($id, $back = '')
    {
        $session = new Session();

        //$session->redirectIfNotLogin(ROOT);

        $product = $this->model->getProductById($id);

        $userId = null;

        if($session->getLogin()){
            $userId = $session->getUserId();
        }

        $data = [
            'titulo' => 'Detalle del producto',
            'menu' => true,
            'subtitle' => $product->name,
            'back' => $back,
            'errors' => [],
            'data' => $product,
            'user_id' => $userId,
        ];

        $this->view('shop/show', $data);
    }

    public function whoami()
    {
        $session = new Session();

        //$session->redirectIfNotLogin(ROOT);

        $data = [
            'titulo' => 'Quienes somos',
            'menu' => true,
            'active' => 'whoami',
        ];

        $this->view('shop/whoami', $data);

    }

    //TODO: try it and planning how can i improve it
    public function contact()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->contactView();
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        if ($name == '') {
            $errors[] = 'El nombre es requerido';
        }
        if ($email == '') {
            $errors[] = 'El email es requerido';
        }
        if ($message == '') {
            $errors[] = 'El mensaje es requerido';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electr??nico no es v??lido';
        }

        if (count($errors)) {
            $data = [
                'titulo' => 'Contacta con nosotros',
                'menu' => true,
                'errors' => $errors,
                'active' => 'contact',
            ];
            $this->view('shop/contact', $data);
            return;
        }

        if (!$this->model->sendEmail($name, $email, $message)) {

            $data = [
                'titulo' => 'Error en el env??o del correo',
                'menu' => true,
                'errors' => [],
                'subtitle' => 'Error en el env??o del correo',
                'text' => 'Existi?? un problema al enviar el correo electr??nico. Pruebe por favor m??s tarde o comun??quese con nuestro servicio de soporte t??cnico.',
                'color' => 'alert-danger',
                'url' => 'shop',
                'colorButton' => 'btn-danger',
                'textButton' => 'Regresar'
            ];

            $this->view('mensaje', $data);
            return;
        }

        $data = [
            'titulo' => 'Mensaje de usuario',
            'menu' => true,
            'errors' => $errors,
            'subtitle' => 'Gracias por su mensaje',
            'text' => 'En breve recibir?? noticias nuestras.',
            'color' => 'alert-success',
            'url' => 'shop',
            'colorButton' => 'btn-success',
            'textButton' => 'Regresar'
        ];
        $this->view('mensaje', $data);
    }

    public function contactView()
    {
        $session = new Session();

        //$session->redirectIfNotLogin(ROOT);

        $data = [
            'titulo' => 'Contacta con nosotros',
            'menu' => true,
            'active' => 'contact',
        ];

        $this->view('shop/contact', $data);
    }
}