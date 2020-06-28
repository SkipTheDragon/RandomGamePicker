<?php
require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$init = new API();
$isSet = new IfIsSet();
$db = new DataBase();

$query = $db->conn->prepare('SELECT * FROM `settings` WHERE `name` = ? ');
$query->execute(array('last_price_checked'));
$row = $query->fetch();

$query = $db->conn->prepare('SELECT `steam_appid` FROM `games` ORDER BY `id` DESC LIMIT 1 ');
$query->execute();
$all_games = $query->fetch(PDO::FETCH_ASSOC);

    if($row['value'] < $all_games['steam_appid']) {
        $index = $row['value'] + 600;

        $query = $db->conn->prepare('SELECT `steam_appid` FROM `games` WHERE `steam_appid` > ? LIMIT ' . $index . ' ');
        $query->execute(array($row['value']));
        $game_ids = $query->fetchAll(PDO::FETCH_ASSOC);
        $games = array();
        foreach ($game_ids as $key => $item) {
            array_push($games, $item['steam_appid']);
        }
        $games = implode(',', $games);

        @$result = $init->parseStore("api/appdetails?appids=$games&cc=us&filters=price_overview");
        foreach (get_object_vars($result) as $key => $item) {
            if ($item->data->price_overview->discount_percent > 0) {
                $discount = $item->data->price_overview->discount_percent;
                $query = $db->conn->prepare('UPDATE `prices` SET actual_price = ?, discount = ? WHERE steam_appid = ?');
                $query->execute(array($discount, $key));
            }
            $query = $db->conn->prepare('UPDATE `settings` SET `value` = ? WHERE `name` = ? ');
            $query->execute(array($key, 'last_price_checked'));
        }
    }