<?php

class SearchController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Search');
    }

    public function products()
    {
        $search = $_POST['search'] ?? '';
        $search = trim($search);

        if ($search == '') {
            header('location:' . ROOT . 'shop');
        }

        $dataSearch = $this->model->getProducts($search);

        $data = [
            'titulo' => 'Buscador de productos',
            'subtitle' => 'Resultado de la búsqueda',
            'data' => $dataSearch,
            'menu' => true,
        ];

        $this->view('search/search', $data);
    }
}