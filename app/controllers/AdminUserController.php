<?php

class AdminUserController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('AdminUser');
    }

    public function index()
    {

        $session = new Session();

        if( $session->getLogin()){

            $users = $this->model->getUser();

            $data =[
                'titulo' => 'Administracion de Usuarios',
                'menu' => false,
                'admin' => true,
                'users' => $users,
            ];

            $this->view('admin/users/index', $data);

        } else {

            header('location:' . ROOT . 'Admin');
        }



    }

    public function create()
    {

        if($_SERVER['REQUEST_METHOD'] != 'POST'){




        }


        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $errors = [];

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password1 = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';


            $dataForm = [
                'name' => $name,
                'email' => $email,
                'password' => $password1,
            ];

            if(empty($name)) {
                $errors[] = 'El nombre de usuario es requerido';

            }
            if(empty($email)) {
                $errors[] =  'El email es requerido';

            }
            if(empty($password1)) {
                $errors[] =  'La contraseña es requerido';

            }
            if(empty($password2)) {
                $errors[] =  'La verificacion de contraseña es requerida';

            }

            if($password1 != $password2) {
                $errors[] =  'Las claves no coinciden';

            }

            if(! $errors ){

               if( $this->model->createAdminUser($dataForm)){

                   header('location:' . ROOT . 'adminUser');

                } else {

                   $data = [
                       'titulo' => 'Error en la creacion de un usuario administrador',
                       'menu' => false,
                       'errors' => [],
                       'subtitle' => 'Error al crear un nuevo usuario administrador',
                       'text' => 'Se ha producido un error durante el proceso de creacion de un usuario administrador',
                       'color' => 'alert-danger',
                       'url' => 'adminUser',
                       'colorButton' => 'btn-danger',
                       'textButton' => 'Volver',
                   ];

                   $this->view('mensaje', $data);

               }

            } else {

                $data = [
                    'titulo' => 'Administracion de usuarios - Alta',
                    'menu' => false,
                    'admin' => true,
                    'errors' => $errors,
                    'data' => $dataForm,
                ];

                $this->view('admin/users/create', $data);

            }


        } else {


            $data = [
                'titulo' => 'Administracion de usuarios - Alta',
                'menu' => false,
                'admin' => true,
                'data' => [],
            ];

            $this->view('admin/users/create', $data);
        }
    }

    public function update($id)
    {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {




        } else {

            $user = $this->model->getUserById($id);
            $status = $this->model->getConfig('adminStatus');

            $data = [
                'titulo' => 'Administracion de usuarios - Editar',
                'menu' => false,
                'admin' => true,
                'status' => $status,
                'data' => $user,
            ];

            $this->view('admin/users/update', $data);



        }


    }

    public function delete()
    {
        print 'Eliminacion de usuarios';
    }
}