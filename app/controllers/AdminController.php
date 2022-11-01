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

        $user = $_POST['admin'] ?? '';
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

           if(!$this->model->findByEmail($dataForm['user'])) {
               $errors[] = 'El usuario no existe';
               $this->index($errors, $dataForm);
               return;
            }

            $admins = $this->model->findByEmail($dataForm['user']);

            //TODO refactor y añadir comprobacion si usuario esta desactivado y si el usuario no existe en los registros
            //TODO antes estaba esta comprobacion en el model pero al refactorizar los metodos debe estar aqui

            $errors = $this->model->verifyAdminPass($dataForm, $admins);

            if(! $errors){

                $session = new SessionAdmin();
                $session->login($dataForm);

                header('location:' . ROOT . 'AdminShop');

            }
        }

         $this->index($errors, $dataForm);

    }

}