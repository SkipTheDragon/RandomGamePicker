<?php
require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$init = new API();
$isSet = new IfIsSet();
$db = new DataBase();


$query = $db->conn->prepare('SELECT * FROM `settings` WHERE `name` = ? ');
$query->execute(array('last_rating_id'));
$last_rating = $query->fetch();

$query = $db->conn->prepare('SELECT * FROM `settings` WHERE `name` = ? ');
$query->execute(array('last_rating_checked_id'));
$last_rating_checked = $query->fetch();

$index = $last_rating_checked['value'] + 400;
if($last_rating_checked['value'] <= $last_rating['value']) {
    for ($i = $last_rating_checked['value'] + 1; $i <= $index; $i++) {
        if($last_rating_checked['value'] <= $last_rating['value']) {

            $query = $db->conn->prepare('SELECT `steam_appid` FROM `games` WHERE `id` = ? ');
            $query->execute(array($i));
            $game_id = $query->fetch();
            @$result = $init->parseStore("appreviews/$game_id?json=1");

            if ($result->success == 1) {
                $review_number = $result->query_summary->total_reviews;
                $review_score = $result->query_summary->review_score;
                $q = $db->conn->prepare('UPDATE `rating` SET ratings = ?,score = ? WHERE appid = ?');
                $q->execute(array($review_number, $review_score, $game_id['steam_appid']));
            }
            $q = $db->conn->prepare('UPDATE `settings` SET `value` = ? WHERE `name` = ? ');
            $q->execute(array($i, 'last_rating_checked_id'));
        }
        sleep(1);
    }

}