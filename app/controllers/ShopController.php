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
            'user' => $session->getUser()
        ];

        $this->view('shop/index' , $data);
    }

}