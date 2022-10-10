<?php

class Login
{

    private $db;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();

    }

    public function existsEmail($email): bool
    {
        //Se pone : delante del nombre de la variable para parametrizarla
        // Y asi asegurarse de que no se cuela otra consulta.
        //Defino consulta
        $sql = 'SELECT * FROM users WHERE email=:email';

        //almaceno la preparacion de la consulta haciendo uso de  la
        // instancia de la conexion y la analiza de que sea correcta
        //Preparo la consulta
        $query = $this->db->prepare($sql);

        //enlaza el valor con el parametro, me permite añadir una cte de la
        //clase PDO, para informar de lo que estoy pasando, en este caso un string
        //Enlazo valores
        $query->bindParam(':email', $email, PDO::PARAM_STR);

        //ejecutamos la consulta
        $query->execute();

        //devuelve 0 = false y por tanto no hay usuarios con el email
        return $query->rowCount();
    }

    public function createUser($data): bool
    {
        //respuesta
        $response = false;

        if(! $this->existsEmail($data['email'])) {

            //Crear el usuario, primero encriptar la pass con el algoritmo
            // lo que deseamos encriptar y la semilla
            $password = hash_hmac('sha512', $data['password'], ENCRYPTKEY);

            //generamos la consulta
            $sql = 'INSERT INTO users (first_name, last_name_1, last_name_2, email, 
            address, city, state, zipcode, country, password) VALUES (:first_name, :last_name_1, :last_name_2, :email, 
            :address, :city, :state, :zipcode, :country, :password)';

            $params = [
                ':first_name' => $data['firstName'],
                ':last_name_1' => $data['lastName1'],
                ':last_name_2' => $data['lastName2'],
                ':email' => $data['email'],
                ':address' => $data['address'],
                ':city' => $data['city'],
                ':state' => $data['state'],
                ':zipcode' => $data['postcode'],
                ':country' => $data['country'],
                ':password' => $password,
            ];

            $query = $this->db->prepare($sql);
            $response = $query->execute($params);

        }

        return $response;

    }

    public function getUserByEmail($email)
    {

        $sql = 'SELECT * FROM users WHERE email=:email';

        $query = $this->db->prepare($sql);

        $query->bindParam(':email', $email, PDO::PARAM_STR);

        $query->execute();

        //fetch cuando solo sea una fila.
        //el modo es PDO:FETCH_OBJ me devuelve un objeto con variables de la fila
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function sendEmail($email)
    {

        $user = $this->getUserByEmail($email);

        // En caso de poner espacios en blanco luego hay que hacer un trim
        $name = $user->first_name . ' ' .
                    $user->last_name_1 . ' ' .
                    $user->last_name_2 ?? '';

        $fullName = rtrim($name);

        $msg = $fullName . ', acceda al siguiente enlace para cambiar su contraseña.<br>';

        $msg .= '<a href="' . ROOT . 'login/changePassword/' . $user->id . '">Cambia la clave de acceso</a>';

        $headers = 'MIME-Version: 1.0\r\n';
        $headers .= 'Content-type:text/html; charset=UTF-8\r\n';
        $headers .= 'From: RobaEneba\r\n';
        $headers .= 'Reply-to: Robaeneba@.local';

        $subject = 'Cambiar contraseña en RobaEneba';

        return mail($email, $subject, $msg, $headers);

    }

    public function changePassword($id, $password): bool
    {

        $pass = hash_hmac('sha512', $password, ENCRYPTKEY);

        $sql = 'UPDATE users SET password=:password WHERE id=:id';

        $params = [
            ':id' => $id,
            ':password' => $pass,
        ];

        $query = $this->db->prepare($sql);

        // enlazamos los parametros en la ejecucion.
        return $query->execute($params);
    }

    public function verifyUser($email, $password): array
    {

        $errors = [];

        $user = $this->getUserByEmail($email);

        $pass = hash_hmac('sha512', $password, ENCRYPTKEY);

        if( ! $user ) {

            array_push( $errors, 'El usuario no existe en nuestros registros');

        } elseif($user->password != $pass) {

            array_push($errors, 'La contraseña no es correcta');
        }

        return $errors;
    }


}