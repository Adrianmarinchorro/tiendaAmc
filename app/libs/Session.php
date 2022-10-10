<?php

class Session
{

    private $login = false;
    private $user;

    public function __construct()
    {

        //permite acceder a los datos de session  si los hay
        session_start();

        if(isset($_SESSION['user'])) {

            $this->user = $_SESSION['user'];

            $this->login = true;

        } else {

            unset($this->user);

            $this->login = false;
        }
    }

    //para saber quien se esta logeando para abrirle una session.
    public function  login($user): void
    {

        if($user) {

            $this->user = $user;

            $_SESSION['user'] = $user;

            $this->login = true;

        }

    }

    public function logout(): void
    {

        unset($_SESSION['user']);
        unset($this->user);
        session_destroy();
        $this->login = false;
    }

    public function redirectIfNotLogin($route)
    {
        if(!$this->login){
            header('location:' . $route);
        }
    }

    public function getLogin(): bool
    {
        return $this->login;
    }

    public function getUser()
    {
        return $this->user;
    }

}