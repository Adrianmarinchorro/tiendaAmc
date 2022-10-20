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

    public function viewCreateForm($errors = [], $dataForm = [])
    {
        $typeConfig = $this->model->getConfig('productType');
        $statusConfig = $this->model->getConfig('productStatus');
        $catalogue = $this->model->getCatalogue();

        $data = [
            'titulo' => 'Administración de Productos - Alta',
            'menu' => false,
            'admin' => true,
            'type' => $typeConfig,
            'status' => $statusConfig,
            'catalogue' => $catalogue,
            'errors' => $errors,
            'data' => $dataForm,
        ];

        $this->view('admin/products/create', $data);
    }

    //TODO: Create no existe, hay que crear createBook createCourse
    public function createCourse()
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //product
            $type = $_POST['type'] ?? '';
            $name = Validate::text($_POST['name'] ?? '');
            $description = Validate::text($_POST['description'] ?? '');
            $price = Validate::number((float)($_POST['price'] ?? 0.0));
            $discount = Validate::number((float)($_POST['discount'] ?? 0.0));
            $send = Validate::number((float)($_POST['send'] ?? 0.0));
            $image = Validate::file($_FILES['image']['name']);
            $published = $_POST['published'] ?? '';
            $relation1 = $_POST['relation1'] != '' ? $_POST['relation1'] : 0;
            $relation2 = $_POST['relation2'] != '' ? $_POST['relation2'] : 0;
            $relation3 = $_POST['relation3'] != '' ? $_POST['relation3'] : 0;
            $mostSold = isset($_POST['mostSold']) ? '1' : '0';
            $new = isset($_POST['new']) ? '1' : '0';
            $status = $_POST['status'] ?? '';

            //Course
            $people = Validate::text($_POST['people'] ?? '');
            $objetives = Validate::text($_POST['objetives'] ?? '');
            $necesites = Validate::text($_POST['necesites'] ?? '');

            //Validamos la información

            $errors = Course::validateName($name, $errors);
            $errors = Course::validateDescription($description, $errors);
            $errors = Course::validatePrice($price, $errors);
            $errors = Course::validateDiscount($discount, $errors);
            $errors = Course::validateSend($send, $errors);
            $errors = Course::validateDiscountLowerThanPrice($discount, $price, $errors);
            $errors = Course::validatePublishedDate($published, $errors);
            $errors = Course::validateObjectivePublic($people, $errors);
            $errors = Course::validateObjetives($objetives, $errors);
            $errors = Course::validateNecesites($necesites, $errors);

            //es lo de abajo comentado
            $errors = Course::validateImage($image, $errors);

            // Creamos el array de datos
            $dataForm = [
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'people' => $people,
                'objetives' => $objetives,
                'necesites' => $necesites,
                'price' => $price,
                'discount' => $discount,
                'send' => $send,
                'published' => $published,
                'image' => $image,
                'mostSold' => $mostSold,
                'new' => $new,
                'status' => $status,
                'relation1' => $relation1,
                'relation2' => $relation2,
                'relation3' => $relation3,
            ];


            if (!$errors) {

                if ($this->model->createProduct($dataForm)) {

                    header('location:' . ROOT . 'AdminProduct');

                }
                $errors[] = 'Se ha producido un error en la inserción en la BD';
            }
        }

        $this->viewCreateForm($errors, $dataForm);
    }

    //TODO: hay que aplicar los mismo cambios que en createCourse
    public function createBook()
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $type = $_POST['type'] ?? '';
            $name = addslashes(htmlentities($_POST['name'] ?? ''));
            $description = addslashes(htmlentities($_POST['description'] ?? ''));
            $price = Validate::number(intval($_POST['price']) ?? '');
            $discount = Validate::number(intval($_POST['discount']) ?? '');
            $send = Validate::number(intval($_POST['send']) ?? '');
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
            $pages = Validate::number(intval($_POST['pages']) ?? '');

            // Validamos la información
            if (empty($name)) {
                $errors[] = 'El nombre del producto es requerido';
            }
            if (empty($description)) {
                $errors[] = 'La descripción del producto es requerida';
            }
            if (!is_numeric($price)) {
                $errors[] = 'El precio del producto debe de ser un número';
            }
            if (!is_numeric($discount)) {
                $errors[] = 'El descuento del producto debe de ser un número';
            }
            if (!is_numeric($send)) {
                $errors[] = 'Los gastos de envío del producto deben de ser numéricos';
            }
            if (is_numeric($price) && is_numeric($discount) && $price < $discount) {
                $errors[] = 'El descuento no puede ser mayor que el precio';
            }
            /*if (!Validate::date($published) ) {
                $errors[] = 'La fecha o su formato no es correcto';
            } elseif ( ! Validate::dateDif($published)) {
                $errors[] = 'La fecha de publicación no puede ser anterior a hoy';
            }*/

            if (empty($author)) {
                $errors[] = 'El autor del libro es necesario';
            }
            if (empty($publisher)) {
                $errors[] = 'La editorial del libro es necesaria';
            }
            if (!is_numeric($pages)) {
                $pages = 0;
                $errors[] = 'La cantidad de páginas de un libro debe de ser un número';
            }

            // Creamos el array de datos
            $dataForm = [
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'author' => $author,
                'publisher' => $publisher,
                'price' => $price,
                'discount' => $discount,
                'send' => $send,
                'pages' => $pages,
                'published' => $published,
                'image' => $image,
                'mostSold' => $mostSold,
                'new' => $new,
                'status' => $status,
            ];

            if (!$errors) {

                // Enviamos la información al modelo

                if (!$errors) {

                    // Redirigimos al index de productos

                }
            }
        }

        $this->viewCreateForm($errors, $dataForm);
    }

    //TODO: mirar js
    public function updateView($id)
    {
        $errors = [];
        $typeConfig = $this->model->getConfig('productType');
        $statusConfig = $this->model->getConfig('productStatus');
        $catalogue = $this->model->getCatalogue();

        $product = $this->model->getProductById($id);

        $data = [
            'titulo' => 'Administración de Productos - Edicion',
            'menu' => false,
            'admin' => true,
            'type' => $typeConfig,
            'status' => $statusConfig,
            'catalogue' => $catalogue,
            'errors' => $errors,
            'product' => $product,
        ];

        $this->view('admin/products/update', $data);

    }

    //TODO: mirar y refactorizar codigo carlos
    public function updateCourse($id)
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //product
            $type = $_POST['type'] ?? '';
            $name = Validate::text($_POST['name'] ?? '');
            $description = Validate::text($_POST['description'] ?? '');
            $price = Validate::number((float)($_POST['price'] ?? 0.0));
            $discount = Validate::number((float)($_POST['discount'] ?? 0.0));
            $send = Validate::number((float)($_POST['send'] ?? 0.0));
            $image = Validate::file($_FILES['image']['name']);
            $published = $_POST['published'] ?? '';
            $relation1 = $_POST['relation1'] != '' ? $_POST['relation1'] : 0;
            $relation2 = $_POST['relation2'] != '' ? $_POST['relation2'] : 0;
            $relation3 = $_POST['relation3'] != '' ? $_POST['relation3'] : 0;
            $mostSold = isset($_POST['mostSold']) ? '1' : '0';
            $new = isset($_POST['new']) ? '1' : '0';
            $status = $_POST['status'] ?? '';

            //Course
            $people = Validate::text($_POST['people'] ?? '');
            $objetives = Validate::text($_POST['objetives'] ?? '');
            $necesites = Validate::text($_POST['necesites'] ?? '');

            //Validamos la información

            $errors = Course::validateName($name, $errors);
            $errors = Course::validateDescription($description, $errors);
            $errors = Course::validatePrice($price, $errors);
            $errors = Course::validateDiscount($discount, $errors);
            $errors = Course::validateSend($send, $errors);
            $errors = Course::validateDiscountLowerThanPrice($discount, $price, $errors);
            $errors = Course::validatePublishedDate($published, $errors);
            $errors = Course::validateObjectivePublic($people, $errors);
            $errors = Course::validateObjetives($objetives, $errors);
            $errors = Course::validateNecesites($necesites, $errors);
            $errors = Course::validateImage($image, $errors);

            // Creamos el array de datos
            $dataForm = [
                'id' => $id,
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'author' => null,
                'publisher' => null,
                'people' => $people,
                'objetives' => $objetives,
                'necesites' => $necesites,
                'price' => $price,
                'discount' => $discount,
                'send' => $send,
                'pages' => null,
                'published' => $published,
                'image' => $image,
                'mostSold' => $mostSold,
                'new' => $new,
                'relation1' => $relation1,
                'relation2' => $relation2,
                'relation3' => $relation3,
                'status' => $status,
            ];

            if (!$errors) {

                if (count($this->model->updateProduct($dataForm)) == 0) {

                    header('location:' . ROOT . 'AdminProduct');

                }
                $errors[] = 'Se ha producido un error en la inserción en la BD';
            }
        }
    }


    public function updateBook($id)
    {



    }
    //TODO: Mirar y cambiar codigo carlos
    public function delete($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = $this->model->delete($id);

            if (empty($errors)) {
                header('location:' . ROOT . 'AdminProduct');
            }

        }

        $product = $this->model->getProductById($id);
        $typeConfig = $this->model->getConfig('productType');

        $data = [
            'titulo' => 'Administración de Productos - Eliminación',
            'menu' => false,
            'admin' => true,
            'type' => $typeConfig,
            'product' => $product,
        ];
    }
}