<?php

require '../core/ifIsSet.php';

require '../core/API.php';

require '../core/DataBase.php';

$init = new API();
$isSet = new IfIsSet();
$db = new DataBase();


$qa = $db->conn->prepare('SELECT * FROM `settings` WHERE `name` = ? ');
$qa->execute(array('last_rating_id'));
$row = $qa->fetch();

$apps = $init->parseSteam('ISteamApps','GetAppList','v0001');

    $index = count($apps->applist->apps->app);
    for ($i = $row['value'] + 1; $i <= $index; $i++) {
        if($row['value'] <= count($apps->applist->apps->app)) {
            @$result = $init->parseStore("appreviews/".$apps->applist->apps->app[$i]->appid."?json=1");

            if ($result->success == 1) {
                $review_number = $result->query_summary->total_reviews;
                $review_score = $result->query_summary->review_score;
                $desc = $result->query_summary->review_score_desc;
                $q = $db->conn->prepare('INSERT INTO `rating`(appid,ratings,score) VALUES (?,?,?)');
                $q->execute(array($apps->applist->apps->app[$i]->appid, $review_number, $review_score));
            }

            $q = $db->conn->prepare('UPDATE `settings` SET `value` = ? WHERE `name` = ? ');
            $q->execute(array($i, 'last_rating_id'));
        }
        sleep(1);
    }
