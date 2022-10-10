<?php

class AdminController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Admin');
    }

    public function index($errors = [], $data = [])
    {
        $data = [
            'titulo' => 'administracion',
            'menu' => false,
            'data' => $data,
            'errors' => $errors,
        ];

    $this->view('admin/index', $data);

    }

    public function verifyUser()
    {
        $errors = [];
        $dataForm = [];

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->index();
            return;
        }

        $user = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';

        $dataForm = [
            'user' => $user,
            'password' => $password,
        ];

        if( empty($user)) {
            $errors[] =  'El usuario es requerido';
        }

        if( empty($password)) {
            $errors[] =  'La contraseña es requerida';
        }

        if(! $errors) {

            // errores con la base de datos comprobando los usuarios
            $errors = $this->model->verifyUser($dataForm);

            if(! $errors){

                $session = new Session();
                $session->login($dataForm);

                header('location:' . ROOT . 'AdminShop');

            }
        }

         $this->index($errors, $dataForm);

    }

}