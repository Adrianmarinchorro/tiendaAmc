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

        $session->redirectIfNotLogin(ROOT);

        $mostSold = $this->model->getMostSold();
        $news = $this->model->getNews();

        //modificacion de carlos para mostrar los mas vendidos
        $data = [

            'titulo' => 'Bienvenid@ a RobaEneba',
            'menu' => true,
            'subtitle' => 'Articulos mas vendidos',
            'user' => $session->getUser(),
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

        $session->redirectIfNotLogin(ROOT);

        $product = $this->model->getProductById($id);

        $data = [
            'titulo' => 'Detalle del producto',
            'menu' => true,
            'subtitle' => $product->name,
            'back' => $back,
            'errors' => [],
            'data' => $product,
            'user_id' => $session->getUserId(),
        ];

        $this->view('shop/show', $data);
    }

    public function whoami()
    {
        $session = new Session();

        $session->redirectIfNotLogin(ROOT);

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
            $errors[] = 'El correo electrónico no es válido';
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
                'titulo' => 'Error en el envío del correo',
                'menu' => true,
                'errors' => [],
                'subtitle' => 'Error en el envío del correo',
                'text' => 'Existió un problema al enviar el correo electrónico. Pruebe por favor más tarde o comuníquese con nuestro servicio de soporte técnico.',
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
            'text' => 'En breve recibirá noticias nuestras.',
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

        $session->redirectIfNotLogin(ROOT);

        $data = [
            'titulo' => 'Contacta con nosotros',
            'menu' => true,
            'active' => 'contact',
        ];

        $this->view('shop/contact', $data);
    }
}