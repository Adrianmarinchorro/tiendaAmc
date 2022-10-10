<?php

class AdminUser
{
    private $db;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();
    }

    public function createAdminUser($data): bool
    {

        $response = false;

        if(! $this->existsEmail($data['email'])){

            $pass = hash_hmac('sha512', $data['password'], ENCRYPTKEY);

            $sql = 'INSERT INTO admins(name, email, password, status, deleted, login_at, created_at, updated_at, deleted_at) VALUES (:name, :email, :password, :status, :deleted, :login_at, :created_at, :updated_at, :deleted_at);';

            $params = [
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':password' => $pass,
                ':status' => 1,
                ':deleted' => 0,
                ':login_at' => null,
                ':created_at' => date('Y-m-d H:i:s'),
                ':updated_at' => null,
                ':deleted_at' => null,
            ];

            $query = $this->db->prepare($sql);
            $response = $query->execute($params);

        }


        return $response;

    }

    public function existsEmail($email)
    {
        $sql = 'SELECT * from admins WHERE email=:email;';
        $query = $this->db->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);

        $query->execute();

        return $query->rowCount();


    }

    public function getUser(): array
    {
        $sql = 'SELECT * FROM admins WHERE deleted = 0';
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUserById($id)
    {
        $sql = 'SELECT * FROM admins WHERE id=:id;';
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);

        return $query->fetch(PDO::FETCH_OBJ);

    }

    public function getConfig($type)
    {
        $sql = 'SELECT * FROM config WHERE type=:type ORDER BY value DESC;';
        $query = $this->db->prepare($sql);
        $query->execute([':type' => $type]);

        return $query->fetchAll(PDO::FETCH_OBJ);

    }

}