<?php

class GameController Extends Controller {

    private $model;

    public function index()
    {
        if(!isset($_GET['id'])) {
            $gameid = 1;
        }
        else {
            $gameid = intval($_GET['id']);
        }

        $this->model = $this->model('game');

        if($gameid > $this->model->lastGameID() || $gameid == 0) {
            $gameid = 1;
        }

        $this->view('game', ['getGameDetails' => $this->model->getRowData($gameid),
                'price' => $this->model->getGamePrice($gameid),
                'categories' => $this->listGameFilters($gameid,'categories','diff'),
                'genres' => $this->listGameFilters($gameid,'genres'),
                'num_of_players' => $this->listGameFilters($gameid,'categories','intersect'),
                'platforms' => $this->listPlatforms($gameid),
                'dlc_number' => $this->countDLCS($gameid),
                'languages' => $this->languages($gameid),
                'rating' => $this->translateScores($gameid),
                'imageStatus' => $this->checkImageStatus($gameid),
            ]);

    }

    private function listGameFilters($gameid,$filters,$e = '')
    {
        $list = $this->model->getRowData($gameid);
        $decoder = json_decode($list[$filters], true);
        if(!empty($e)) {
            $row = $this->model->numOfPlayers();
            $array = array();
            $array2 = array();

            for ($i = 0; $i < 100; $i++) {
                if (isset($row[$i]['id'])) array_push($array, $row[$i]['id']);
                if (isset($decoder[$i])) array_push($array2, $decoder[$i]);
            }
            if($e == 'intersect') {
                $array_mod = array_intersect($array, $array2);
            } elseif ($e == 'diff') {
                $array_mod = array_diff($array2, $array);
            } else {
                $array_mod = array();
            }
            $decoder = $array_mod;
        }
        $named_filer = [];
        foreach( $decoder as $key => $item) {
            if(!is_null($item))
            array_push($named_filer,$this->model->getFilterName($item));
        }

        return $named_filer;
    }
    private function listPlatforms($gameid)
    {
        $q = $this->model->getPlatforms($gameid);
        $decoder = json_decode($q,true);

        return $decoder;
    }

    private function countDLCS($gameid)
    {
        $q = $this->model->getRowData($gameid);
        $decoder = json_decode($q['dlc'],true);

        return count($decoder);
    }

    private function languages($gameid, $all_languages = FALSE)
    {
        $q = $this->model->getRowData($gameid);

        $result = str_replace('*','', $q['language']);
        $result = str_replace('**','', $q['language']);
        $result = str_replace('languages with full audio support','', $q['language']);

        $result = explode(',', $result);

        if ($all_languages == TRUE) {
            return $result;
        } else {
            if (count($result) > 2) {
                $number = count($result) - 2;
                return $result[0] .', '. $result[1] . ', <span id="all_languages">' . $number . ' other</span>';
            } else {
                return $result[0];
            }
        }
    }
    private function checkImageStatus($gameid)
    {
        $gameid = $this->model->getRowData($gameid);
        $gameid = $gameid['steam_appid'];
        $file = "https://steamcdn-a.akamaihd.net/steam/apps/$gameid/page_bg_generated_v6b.jpg";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$file);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch)!==FALSE)
        {
            return true;
        }
            return false;
    }
    private function translateScores($gameid)
    {
        $game_rep = $this->model->gameRep($gameid);

        if($game_rep == TRUE){
            $game_score = $game_rep['score'];
            $ratings = $game_rep['ratings'];


            if($game_score == 0)
            {
                $score_name =  'No user reviews';
            } elseif($game_score == 1) {
                $score_name = "Overwhelmingly Negative";
            } elseif($game_score == 2) {
                $score_name = "Very Negative";
            } elseif($game_score == 3) {
                $score_name = "Negative";
            } elseif($game_score == 4) {
                $score_name = "Mostly Negative";
            } elseif($game_score == 5) {
                $score_name = "Mixed";
            } elseif($game_score == 6) {
                $score_name = "Mostly Positive";
            } elseif($game_score == 7) {
                $score_name = "Positive";
            } elseif($game_score == 8) {
                $score_name = "Very Positive";
            } elseif($game_score == 9) {
                $score_name = "Overwhelmingly Positive";
            } elseif($game_score == 10) {
                $score_name = "Overwhelmingly Positive";
            } else {
                $score_name = "Unknown Yet";
            }

            return $score_name. " ($ratings)";

        }
            return 'Unknown';
    }
}