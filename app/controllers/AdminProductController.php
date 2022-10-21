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

                if ($this->model->createCourse($dataForm)) {

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

            //Book
            $author = Validate::text($_POST['author'] ?? '');
            $publisher = Validate::text($_POST['publisher'] ?? '');
            $pages = Validate::text($_POST['pages'] ?? '');

            //Validamos la información

            $errors = Book::validateName($name, $errors);
            $errors = Book::validateDescription($description, $errors);
            $errors = Book::validatePrice($price, $errors);
            $errors = Book::validateDiscount($discount, $errors);
            $errors = Book::validateSend($send, $errors);
            $errors = Book::validateDiscountLowerThanPrice($discount, $price, $errors);
            $errors = Book::validatePublishedDate($published, $errors);
            $errors = Book::validateAuthor($author, $errors);
            $errors = Book::validatePublisher($publisher, $errors);
            $errors = Book::validatePages($pages, $errors);


            $errors = Book::validateImage($image, $errors);

            // Creamos el array de datos
            $dataForm = [
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'author' => $author,
                'publisher' => $publisher,
                'pages' => $pages,
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

                if ($this->model->createBook($dataForm)) {

                    header('location:' . ROOT . 'AdminProduct');

                }
                $errors[] = 'Se ha producido un error en la inserción en la BD';
            }
        }

        $this->viewCreateForm($errors, $dataForm);
    }

    //TODO: mirar js
    public function updateView($id, $errors = [])
    {

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

            //ya no es obligatorio pasar una imagen
            if ($image) {
                $errors = Course::validateImage($image, $errors);
            }

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
                'published' => $published,
                'pages' => null,
                'image' => $image,
                'mostSold' => $mostSold,
                'new' => $new,
                'relation1' => $relation1,
                'relation2' => $relation2,
                'relation3' => $relation3,
                'status' => $status,
            ];

            if (!$errors) {

                if (count($this->model->updateCourse($dataForm)) == 0) {

                    header('location:' . ROOT . 'AdminProduct');
                    return;
                }

                $errors[] = 'Se ha producido un error en la inserción en la BD';
            }

            $this->updateView($id, $errors);

        }
    }


    public function updateBook($id)
    {


    }

    //TODO: Mirar y cambiar codigo carlos
    public function delete($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        $this->deleteView($id);
        return;
        }

        $errors = $this->model->delete($id);

        if (!empty($errors)) {
            $this->deleteView($id, $errors);
            return;
        }

        header('location:' . ROOT . 'AdminProduct');
    }

    public function deleteView($id, $errors = [])
    {
        $product = $this->model->getProductById($id);
        $typeConfig = $this->model->getConfig('productType');

        $data = [
            'titulo' => 'Administración de Productos - Eliminación',
            'menu' => false,
            'admin' => true,
            'type' => $typeConfig,
            'product' => $product,
            'errors' => $errors,
        ];
        $this->view('admin/products/delete', $data);
    }
}