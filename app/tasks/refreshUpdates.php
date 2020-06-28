<?php
require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$init = new API();
$isSet = new IfIsSet();
$db = new DataBase();


$query = $db->conn->prepare('SELECT `name` FROM `settings` WHERE `name` LIKE \'%checked%\' ');
$query->execute(array('last_rating_id'));

$values_to_delete = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($values_to_delete as $item => $value)
{
    $empty = $db->conn->prepare('UPDATE settings SET value = ? WHERE name = ?');
    $empty->execute(array(1, $value['name']));
}
