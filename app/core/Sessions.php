<?php
class Sessions {

    private $db;


    public function __construct()
    {
        require_once 'DataBase.php';
        $db = new DataBase();
        $this->db = $db->conn;
    }

    public function index()
    {
        $this->initSession();
        $this->requestedURL();
    }
    /**
     *
     */
    private function initSession()
    {
        $session_id = $this->generateRandomSessionKey(25);

        if(isset($_COOKIE['session_id']))
        {
            $session = $_COOKIE['session_id'];
            $q = $this->db->prepare('SELECT * FROM `sessions` WHERE `session_id` = ?');
            $q->execute(array($session));
            $row = $q->fetch();

            if(time() > ($row['start_time'] + 43200) && $q->rowCount() == 1)
            {
                setcookie('session_id',$session_id,0,'https://devtheunknown.com/');
            }
        }
        else
        {
            setcookie('session_id',$session_id,time() + 43200,'https://devtheunknown.com/');

            $q = $this->db->prepare('INSERT INTO `sessions`(session_id,start_time) VALUES (?,?)');
            $q->execute(array($session_id,time()));
        }
    }

    private function requestedURL()
    {
        if (isset($_COOKIE['session_id']))
        {
            $q = $this->db->prepare('UPDATE `sessions` SET `current_page` = ? WHERE `session_id` = ?');
            $q->execute(array($_SERVER['REQUEST_URI'], $_COOKIE['session_id']));
        }
    }

    private function generateRandomSessionKey($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}