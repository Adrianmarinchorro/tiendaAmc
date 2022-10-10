<?php

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();
    }

    public function verifyUser($data): array
    {
        $errors = [];

        $password = hash_hmac('sha512', $data['password'], ENCRYPTKEY);

        $admins = $this->findByEmail($data['user']);

        if( ! $admins ) {

            $errors[] =  'El usuario no existe en nuestros registros';

        } elseif(count($admins) > 1) {

            $errors[] = 'El correo electronico esta duplicado';

        } elseif($password != $admins[0]->password) {

            $errors[] = 'La clave de acceso no es correcta';

        } else {

            $sql2 = 'UPDATE admins SET login_at=:login WHERE id=:id;';

            $query2 = $this->db->prepare($sql2);
            $params = [
                ':login' => date(format: 'Y-m-d H:i:s'),
                ':id' => $admins[0]->id,
            ];

            if(! $query2->execute($params)) {
                $errors[] = 'Error al modificar la fecha de ultimo acceso';
            }
        }

        return $errors;

    }

    public function findByEmail($email)
    {

        $sql = 'SELECT * FROM admins WHERE email=:email;';

        $query = $this->db->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);

    }

    public function updateLastLogin($errors, $admins)
    {

        $sql2 = 'UPDATE admins SET login_at=:login WHERE id=:id;';

        $query2 = $this->db->prepare($sql2);
        $params = [
            ':login' => date(format: 'Y-m-d H:i:s'),
            ':id' => $admins[0]->id,
        ];

        if(! $query2->execute($params)) {
            $errors[] = 'Error al modificar la fecha de ultimo acceso';
        }

        return $errors;

    }

}