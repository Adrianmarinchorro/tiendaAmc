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
        $data = [

            'title' => 'Bienvenid@ a RobaEneba',
            'menu' => false,
            'menu' => true,
            'subtitle' => 'Bienvenid@ a su tienda de confianza',
            'user' => $session->getUser()
        ];

        $this->view('shop/index' , $data);
    }

    public function logout()
    {
        $session = new Session();
        $session->logout();
        header('location:' . ROOT);
    }

}