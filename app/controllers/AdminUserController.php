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

        $session = new SessionAdmin();

        $session->redirectIfNotLogin(ROOT . 'Admin');

        $users = $this->model->getUser();

        $data = [
            'titulo' => 'Administracion de Usuarios',
            'menu' => false,
            'admin' => true,
            'users' => $users,
        ];

        $this->view('admin/users/index', $data);

    }

    public function create()
    {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {

            $this->showCreateForm();
            return;
        }

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

        if (empty($name)) {
            $errors[] = 'El nombre de usuario es requerido';

        }
        if (empty($email)) {
            $errors[] = 'El email es requerido';

        }
        if (empty($password1)) {
            $errors[] = 'La contraseña es requerido';

        }
        if (empty($password2)) {
            $errors[] = 'La verificacion de contraseña es requerida';

        }

        if ($password1 != $password2) {
            $errors[] = 'Las claves no coinciden';

        }

        if (!$errors) {

            if ($this->model->createAdminUser($dataForm)) {

                header('location:' . ROOT . 'adminUser');
                return;

            }

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

            return;


        }

        $data = [
            'titulo' => 'Administracion de usuarios - Alta',
            'menu' => false,
            'admin' => true,
            'errors' => $errors,
            'data' => $dataForm,
        ];

        $this->view('admin/users/create', $data);


    }

    public function showCreateForm()
    {

        $data = [
            'titulo' => 'Administracion de usuarios - Alta',
            'menu' => false,
            'admin' => true,
            'data' => [],
        ];

        $this->view('admin/users/create', $data);


    }

    public function update($id)
    {
        $errors = [];

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->updateView($id);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password1 = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $status = $_POST['status'] ?? '';

        if ($name == '') {
            $errors[] = 'El nombre del usuario es requerido';
        }
        if ($email == '') {
            $errors[] = 'El email es requerido';
        }
        if ($status == '') {
            $errors[] = 'Selecciona un estado para el usuario';
        }
        if (!empty($password1) || !empty($password2)) {
            if ($password1 != $password2) {
                $errors[] = 'Las contraseñas no coinciden';
            }
        }

        if (!$errors) {
            $data = [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'password' => $password1,
                'status' => $status,
            ];
            $errors = $this->model->setUser($data);
            if (!$errors) {
                header("location:" . ROOT . 'adminUser');
            }
        }

        $this->updateView($id, $errors);

    }

    public function updateView($id, $errors = [])
    {
        $user = $this->model->getUserById($id);
        $status = $this->model->getConfig('adminStatus');


        $data = [
            'titulo' => 'Administracion de usuarios - Editar',
            'menu' => false,
            'admin' => true,
            'status' => $status,
            'data' => $user,
            'errors' => $errors,
        ];

        $this->view('admin/users/update', $data);
    }

    public function delete($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->deleteView($id);
            return;
        }

        $errors = $this->model->delete($id);

        if (!$errors) {
            header('location:' . ROOT . 'adminUser');
        }

        $this->deleteView($id, $errors);
    }

    public function deleteView($id, $errors = [])
    {
        $user = $this->model->getUserById($id);
        $status = $this->model->getConfig('adminStatus');

        $data = [
            'titulo' => 'Administración de Usuarios - Eliminación',
            'menu' => false,
            'admin' => true,
            'data' => $user,
            'status' => $status,
            'errors' => $errors,
        ];

        $this->view('admin/users/delete', $data);

    }
}