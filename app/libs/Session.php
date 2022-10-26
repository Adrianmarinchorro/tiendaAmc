<?php

class Session
{

    private $login = false;
    private $user;
    private $cartTotal;

    public function __construct()
    {

        //permite acceder a los datos de session  si los hay
        session_start();

        if (isset($_SESSION['user'])) {

            //TODO comprender
            $this->user = $_SESSION['user'];
            $this->login = true;
            $_SESSION['cartTotal'] = $this->cartTotal();
            $this->cartTotal = $_SESSION['cartTotal'];

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

    //TODO: mirar
    //hace la conexion sin necesidad del modelo, pero hay que
    //establecer la conexion.
    public function cartTotal()
    {
        $db = Mysqldb::getInstance()->getDatabase();

        $sql = 'SELECT sum(p.price * c.quantity) - sum(c.discount) + sum(c.send) as total
                FROM carts as c, products as p
                WHERE c.user_id=:user_id AND c.product_id=p.id AND c.state=0';
        $query = $db->prepare($sql);
        $query->execute([':user_id' => $this->getUserId()]);
        $data = $query->fetch(PDO::FETCH_OBJ);
        unset($db);

        return ($data->total ?? 0);
    }

}