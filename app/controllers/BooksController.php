<?php

class BooksController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Book');
    }

    public function index()
    {
        $session = new Session();

        //$session->redirectIfNotLogin(ROOT);

        $books = $this->model->getBooks();

        $data = [
            'titulo' => 'Libros',
            'menu' => true,
            'active' => 'books',
            'data' => $books,
        ];

        $this->view('books/index', $data);
    }
}