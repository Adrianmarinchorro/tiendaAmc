<?php

class AdminProduct
{
    //TODO MIRAR SI HAY QUE REFACTORIZAR (CARLOS)

    private const FALSE = 0;
    private const TRUE = 1;
    private $db;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();
    }

    public function getProducts()
    {
        $sql = 'SELECT * FROM products WHERE deleted=FALSE';
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getConfig($type)
    {
        $sql = 'SELECT * FROM config WHERE type=:type ORDER BY value';
        $query = $this->db->prepare($sql);
        $query->execute([':type' => $type]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    // TODO: Refactor o y 1 constantes
    public function getCatalogue()
    {
        $sql = 'SELECT id, name, type FROM products WHERE deleted=FALSE AND status!=FALSE ORDER BY type, name';
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}