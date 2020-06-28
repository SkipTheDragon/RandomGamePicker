<?php
require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$init = new API();
$isSet = new IfIsSet();
$db = new DataBase();

$query = $db->conn->prepare('SELECT * FROM `settings` WHERE `name` = ? ');
$query->execute(array('last_game_checked'));
$row = $query->fetch();

$query = $db->conn->prepare('SELECT `steam_appid` FROM `games` ORDER BY `id`');
$query->execute();
$all_games = $query->rowCount();

if($row['value'] <= $all_games) {
    $index = $row['value'] + 300;

    for ($i = $row['value'] + 1; $i <= $index; $i++) {

        if ($row['value'] <= $all_games) {
            $query = $db->conn->prepare('SELECT `steam_appid` FROM `games` WHERE `id` = ? ');
            $query->execute(array($i));
            $game_id = $query->fetch();

            $result = $init->parseStore("api/appdetails?appids=".$game_id['steam_appid']."&cc=us");

            if (@$result->success === TRUE) {
                $data = $result->data->$game_id['steam_appid'];
                $game = $data->name;
                @$dlc = $isSet->verify($data->dlc, true);
                @$desc = strip_tags($data->about_the_game, ['<br>', '<a>',]);
                @$lang = $isSet->verify($data->supported_languages, false, false, true);
                @$requirements = $isSet->verify($data->pc_requirements->minimum, false, false, true);
                $platforms = json_encode($data->platforms);
                @$categories = $isSet->verify($data->categories, true);
                @$genres = $isSet->verify($data->genres, true);
                $coming_soon = $data->release_date->coming_soon ? 1 : 0;
                $release = strtotime($data->release_date->date);

                $query = $db->conn->prepare('UPDATE `games` SET dlc = ?, description = ?,
                                                                language = ?, requirements = ?,
                                                                 platforms = ?, categories = ?,
                                                                   genres = ?, coming_soon = ?, release_date = ? WHERE steam_appid = ?');

                $query->execute(array($dlc, $desc, $lang, $requirements,$platforms,
                                      $categories, $genres, $coming_soon, $release, $game_id['steam_appid']));

                $query = $db->conn->prepare('UPDATE `settings` SET `value` = ? WHERE `name` = ? ');
                $query->execute(array($i, 'last_game_checked'));
            }
        }
        sleep(1);
    }
}