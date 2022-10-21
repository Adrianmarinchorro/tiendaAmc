<?php

class ShopController extends Controller
{

    private $model;

    public function __construct()
    {

        $this->model = $this->model('Shop');

    }

    public function index(){

        $session = new Session();

        $session->redirectIfNotLogin(ROOT);

        $mostSold = $this->model->getMostSold();
        $news = $this->model->getNews();

        //modificacion de carlos para mostrar los mas vendidos
        $data = [

            'title' => 'Bienvenid@ a RobaEneba',
            'menu' => true,
            'subtitle' => 'Articulos mas vendidos',
            'user' => $session->getUser(),
            'data' => $mostSold,
            'subtitle2' => 'Articulos nuevos',
            'news' => $news,
        ];

        $this->view('shop/index' , $data);
    }

    public function logout()
    {
        $session = new Session();
        $session->logout();
        header('location:' . ROOT);
    }

    // muestra el producto, individualmente
    public function show($id)
    {
        $product = $this->model->getProductById($id);

        $data = [
            'titulo' => 'Detalle del producto',
            'menu' => true,
            'subtitle' => $product->name,
            'errors' => [],
            'data' => $product,
        ];

        $this->view('shop/show', $data);
    }

}