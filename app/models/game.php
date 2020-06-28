<?php

class Game {


    private $db;

    public function __construct()
    {
        require_once 'app/core/DataBase.php';

        $db_init = new DataBase();
        $this->db = $db_init->conn;

    }

    public function getRowData($gameid)
    {
        $q = $this->db->prepare('SELECT * FROM `games` WHERE `id` = ?');
        $q->execute(array($gameid));
        $row = $q->fetch(PDO::FETCH_ASSOC);

        return $row;
    }


    public function getGamePrice($gameid)
    {
        $q = $this->db->prepare('SELECT * FROM `prices` WHERE `id` = ?');
        $q->execute(array($gameid));
        $row = $q->fetch(PDO::FETCH_ASSOC);

        return $row['actual_price'];
    }

    public function numOfPlayers()
    {
        $q = $this->db->prepare('SELECT * FROM `filters` WHERE `id` BETWEEN 356 AND 363');
        $q->execute();
        $row = $q->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getPlatforms($gameid)
    {
        $q = $this->db->prepare('SELECT * FROM `games` WHERE `id` = ?');
        $q->execute(array($gameid));
        $row = $q->fetchAll(PDO::FETCH_ASSOC);

        return $row[0]['platforms'];
    }

    public function lastGameID()
    {
        $q = $this->db->prepare('SELECT * FROM `games` ORDER BY `id` DESC LIMIT 1');
        $q->execute();
        $row = $q->fetch();

        return $row['id'];
    }

    public function gameRep($gameid)
    {
        $q = $this->db->prepare('SELECT * FROM `games` WHERE `id` = ?');
        $q->execute(array($gameid));
        $row = $q->fetch();

        $q1 = $this->db->prepare('SELECT * FROM `rating` WHERE `appid` = ?');
        $q1->execute(array($row['steam_appid']));
        $row1 = $q1->fetch();

        return $row1;
    }
    public function getFilterName($id)
    {
        $q = $this->db->prepare('SELECT * FROM `filters` WHERE `id` = ?');
        $q->execute(array($id));
        $row = $q->fetch();

        return $row['value'];
    }
}