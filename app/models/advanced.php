<?php
class Advanced {
    
    
    private $object;
    
    private $db;

    public function __construct()
    {
        require_once 'app/core/DataBase.php';
        
        $this->object = new DataBase();
        $this->db = $this->object->conn;
    }
  
    public function randomGameInfo()
    {
        $max_id = $this->db->prepare('SELECT `id` FROM `games` ORDER BY `id` DESC LIMIT 1 ');
        $max_id->execute();
        $max_id_fetch = $max_id->fetch(\PDO::FETCH_ASSOC);
        
        $random_game_id = rand('1',$max_id_fetch);
         
        $rand_row = $this->db->prepare('SELECT * FROM `games` WHERE `id` = ? ');
        $rand_row->execute([$random_game_id]);
        $row_id = $rand_row->fetch();
         
        return $row_id;
    }
    
    public function allGames()
    {
        $max_id = $this->db->prepare('SELECT `id` FROM `games` ORDER BY `id` DESC LIMIT 1 ');
        $max_id->execute();
        $max_id_fetch = $max_id->fetch(\PDO::FETCH_ASSOC);
        
        $all_games = range(1,$max_id_fetch['id'],1);

        return $all_games;
    }
   
    public function setFilterRule($rule_name, $rule)
    {
        $q = $this->db->prepare('SELECT `id` FROM `games` WHERE '.$rule_name.' '.$rule.'  ');
        $q->execute();
        $row = $q->fetchAll(PDO::FETCH_ASSOC);
        $result = array_column($row,'id');

        return $result;
    }

    public function filterArrays($game_array,$chosen_filters,$filter_type)
    {
        $filter = $this->db->prepare('SELECT '.$filter_type.' FROM `games` WHERE `id` = ? ');
        $filter->execute(array($game_array));
        $fetch = $filter->fetchAll(PDO::FETCH_ASSOC);
        $decode_q = json_decode($fetch[0][$filter_type],true);
            if(empty($decode_q)) {
                $decode_q = array('Nothing');
            }
         if(!is_array($decode_q)) {
             echo $game_array.'<br/>';
         }
           $verify_data = array_intersect($decode_q,$chosen_filters);
        if(count($verify_data) >= count($chosen_filters)) {
                if (!empty($verify_data)) {
                    return $game_array;
                }
            }
        return NULL;
    }
    
    public function getFilterDetailsByType($type)
    {
        $options = $this->db->prepare('SELECT value,id FROM `filters` WHERE type = ? ');
        $options->execute(array($type));
        $fetch = $options->fetchAll(PDO::FETCH_ASSOC);
        $rC = $options->rowCount();
        $fetch['biggestID'] = $rC;

        return $fetch;
    }

    public function getFilterDetails($name, $type)
    {
        $options = $this->db->prepare('SELECT * FROM `filters` WHERE value = ? AND type = ?');
        $options->execute(array($name, $type));
        $fetch = $options->fetch();
        $rC = $options->rowCount();

        if($rC > 0) {
            return $fetch['value'];
        } else {
            return NULL;
        }
    }

    public function appidToDatabaseID($id)
    {
        $options = $this->db->prepare('SELECT `steam_appid` FROM `games` WHERE id = ?');
        $options->execute(array($id));
        $fetch = $options->fetch();
        $rC = $options->rowCount();

        if($rC > 0) {
            return $fetch['value'];
        } else {
            return NULL;
        }
    }

    public function getScore($game_array)
    {
        $options = $this->db->prepare('SELECT * FROM `rating` WHERE appid = ?');
        $options->execute(array($name, $type));
        $fetch = $options->fetch();
        $rC = $options->rowCount();

        if($rC > 0) {
            return $fetch['value'];
        } else {
            return NULL;
        }
    }
}