<?php
require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$init = new API();
$isSet = new IfIsSet();
$db = new DataBase();
$expired_time = time() - 43200;


$q = $db->conn->prepare('SELECT * FROM `sessions` WHERE `start_time` < ?');
$q->execute(array($expired_time));

    if($q->rowCount() > 10)
    {
        $q = $db->conn->prepare('DELETE FROM `sessions` WHERE `start_time` < ?');
        $q->execute(array($expired_time));
    }

