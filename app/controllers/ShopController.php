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

        if($session->getLogin()){

            $data = [

                'title' => 'Bienvenid@ a RobaEneba',
                'menu' => false,

            ];

            $this->view('shop/index' , $data);
        } else {

            header('location:' . ROOT);
        }





    }

}