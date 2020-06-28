<?php

class DataBase {

    public $conn;

    public function __construct()
    {
        try
        {
            $this->conn = new PDO('mysql:host=localhost;dbname=programmer_rgg','programmer_u','xyZOCD3qbf');
        } catch (PDOException $e)
        {
            echo "Error!".$e->getMessage();
        }
    }

}