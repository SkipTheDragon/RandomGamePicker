<?php
/*
require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$init = new API();
$isSet = new IfIsSet();
$db = new DataBase();

$index = 28324;

    for ($i = 1; $i <= $index; $i++) {
        $qa = $db->conn->prepare('SELECT * FROM `games` WHERE `id` = ? ');
        $qa->execute(array($i));
        $row = $qa->fetch();
        $string_category = json_decode($row['categories'],true);
        $array = [];
        foreach ($string_category as $key => $item) {
            $qa = $db->conn->prepare('SELECT * FROM `filters` WHERE `value` = ? ');
            $qa->execute(array($item['description']));
            $row = $qa->fetch();
            array_push($array,$row['id']);
        }
        $array = json_encode($array);
                $q = $db->conn->prepare('UPDATE `games` SET categories = ? WHERE `id` = ?');
                $q->execute(array($array, $i));
}

*/