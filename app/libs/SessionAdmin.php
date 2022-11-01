<?php

class SessionAdmin
{

    private $login = false;
    private $user;

    public function __construct()
    {

        //permite acceder a los datos de session  si los hay
        session_start();

        if (isset($_SESSION['admin'])) {

            //TODO comprender
            $this->user = $_SESSION['admin'];
            $this->login = true;

        } else {

            unset($this->user);

            $this->login = false;
        }
    }

    //para saber quien se esta logeando para abrirle una session.
    public function login($user): void
    {

        if ($user) {

            $this->user = $user;

            $_SESSION['admin'] = $user;

            $this->login = true;

        }

    }

    public function logout(): void
    {

        unset($_SESSION['admin']);
        unset($this->user);
        session_destroy();
        $this->login = false;
    }

    public function redirectIfNotLogin($route)
    {
        if (!$this->login) {
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

    public function getUserId()
    {
        return $this->user->id;
    }
}