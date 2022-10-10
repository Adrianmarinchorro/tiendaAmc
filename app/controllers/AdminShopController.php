<?php

class AdminShopController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = $this->model('AdminShop');
    }

    public function index()
    {

        $session = new Session();

        if($session->getLogin()) {

            $data =[
                'titulo' => 'Bienvenid@ a la administracion de la tienda',
                'menu' => false,
                'admin' => true,
                'subtitle' => 'Administracion de la tienda',
            ];
            $this->view('admin/shop/index', $data);

        } else {

            header('location:' . ROOT . 'Admin');
        }


    }
}