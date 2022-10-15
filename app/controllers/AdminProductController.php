<?php

class AdminProductController extends Controller
{
    //TODO: Mirar si hay que refactorizar algo del codigo de carlos.
    private $model;

    public function __construct()
    {
        $this->model = $this->model('AdminProduct');
    }

    public function index()
    {
        $session = new Session();

        if ($session->getLogin()) {

            $products = $this->model->getProducts();
            $type = $this->model->getConfig('productType');

            $data = [
                'titulo' => 'Administración de Productos',
                'menu' => false,
                'admin' => true,
                'type' => $type,
                'products' => $products,
            ];

            $this->view('admin/products/index', $data);

        } else {
            header('location:' . ROOT . 'admin');
        }
    }

    public function create()
    {
        $errors = [];
        $dataForm = [];
        $type = $this->model->getConfig('productType');
        $status = $this->model->getConfig('productStatus');
        $catalogue = $this->model->getCatalogue();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $type = $_POST['type'] ?? '';
            $name = addslashes(htmlentities($_POST['name'] ?? ''));
            $description = addslashes(htmlentities($_POST['description'] ?? ''));
            $price = Validate::number($_POST['price'] ?? '');
            $discount = Validate::number($_POST['discount'] ?? '');
            $send = Validate::number($_POST['send'] ?? '');
            $image = Validate::file($_FILES['image']['name']);
            $published = $_POST['published'] ?? '';
            $relation1 = $_POST['relation1'] != '' ? $_POST['relation1'] : 0;
            $relation2 = $_POST['relation2'] != '' ? $_POST['relation2'] : 0;
            $relation3 = $_POST['relation3'] != '' ? $_POST['relation3'] : 0;
            $mostSold = isset($_POST['mostSold']) ? '1' : '0';
            $new = isset($_POST['new']) ? '1' : '0';
            $status = $_POST['status'] ?? '';
            //Books
            $author = addslashes(htmlentities($_POST['author'] ?? ''));
            $publisher = addslashes(htmlentities($_POST['publisher'] ?? ''));
            $pages = Validate::number($_POST['pages'] ?? '');
            //Courses
            $people = addslashes(htmlentities($_POST['people'] ?? ''));
            $objetives = addslashes(htmlentities($_POST['objetives'] ?? ''));
            $necesites = addslashes(htmlentities($_POST['necesites'] ?? ''));

            // Validamos la información
            if (empty($name)) {
                array_push($errors, 'El nombre del producto es requerido');
            }
            if (empty($description)) {
                array_push($errors, 'La descripción del producto es requerida');
            }
            if ( ! is_numeric($price)) {
                array_push($errors, 'El precio del producto debe de ser un número');
            }
            if ( ! is_numeric($discount)) {
                array_push($errors, 'El descuento del producto debe de ser un número');
            }
            if (! is_numeric($send)) {
                array_push($errors, 'Los gastos de envío del producto deben de ser numéricos');
            }
            if (is_numeric($price) && is_numeric($discount) && $price < $discount) {
                array_push($errors, 'El descuento no puede ser mayor que el precio');
            }
            if ( ! Validate::date($published) ) {
                array_push($errors, 'La fecha o su formato no es correcto');
            } elseif ( ! Validate::dateDif($published)) {
                array_push($errors, 'La fecha de publicación no puede ser anterior a hoy');
            }
            if ($type == 1) {
                if (empty($people)) {
                    array_push($errors, 'El público objetivo del curso es obligatorio');
                }
                if (empty($objetives)) {
                    array_push($errors, 'Los objetivos del curso son necesarios');
                }
                if (empty($necesites)) {
                    array_push($errors, 'Los requisitos del curso son necesarios');
                }
            } elseif ($type == 2) {
                if (empty($author)) {
                    array_push($errors, 'El autor del libro es necesario');
                }
                if (empty($publisher)) {
                    array_push($errors, 'La editorial del libro es necesaria');
                }
                if ( ! is_numeric($pages)) {
                    $pages = 0;
                    array_push($errors, 'La cantidad de páginas de un libro debe de ser un número');
                }
            } else {
                array_push($errors, 'Debes seleccionar un tipo válido');
            }

            // Creamos el array de datos
            $dataForm = [
                'type'  => $type,
                'name'  => $name,
                'description' => $description,
                'author'    => $author,
                'publisher' => $publisher,
                'people'    => $people,
                'objetives' => $objetives,
                'necesites' => $necesites,
                'price' => $price,
                'discount' => $discount,
                'send' => $send,
                'pages' => $pages,
                'published' => $published,
                'image' => $image,
                'mostSold' => $mostSold,
                'new' => $new,
            ];

            var_dump($dataForm);

            if ( ! $errors ) {

                // Enviamos la información al modelo

                if ( ! $errors ) {

                    // Redirigimos al index de productos

                }
            }
        }
        $data = [
            'titulo' => 'Administración de Productos - Alta',
            'menu' => false,
            'admin' => true,
            'type' => $type,
            'status' => $status,
            'catalogue' => $catalogue,
            'errors' => $errors,
            'data' => $dataForm,
        ];

        $this->view('admin/products/create', $data);
    }

    public function update($id)
    {

    }

    public function delete($id)
    {

    }
}