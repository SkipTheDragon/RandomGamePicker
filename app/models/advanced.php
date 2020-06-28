<?php
class Advanced {
    
    
    private $object;
    
    private $db;
    
  
    public function __construct()
    {
        require_once 'app/core/Source.php';
        
        $this->object = new \Source();
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
        $row = $q->fetchAll(\PDO::FETCH_ASSOC);
       
        $result = array_column($row,'id');
        return $result;
    }
    
    public function stringToArray($string)
    {
        
        $cleared_string = str_replace(['[',']'], '', $string);
        $array = explode(',', $cleared_string);
        
        return $array;
    }
    
    public function randomGame($game_array)
    {
        $rndm = array_rand($game_array);
        return $rndm;
    }
    
    public function filterArrays($game_array,$chosen_filters,$filter_type)
    {
        $data = array();
        
            
        $filter = $this->db->prepare('SELECT '.$filter_type.' FROM `games` WHERE `id` = ? ');
        $filter->execute(array($game_array));
        $fetch = $filter->fetchAll(PDO::FETCH_ASSOC);
            
        $decode_q = json_decode($fetch[0][$filter_type],true);
            
        $index = count($decode_q);
            
           for ($i = 0; $i < $index; $i++) {
               $data['description'][$i] = $decode_q[$i]['description'];
            }
            if(empty($data['description']))
            {
                $data['description'] = array('Nothing');
            }
        $verify_data = array_intersect($data['description'],$chosen_filters);
           
           if(empty($verify_data)) 
           {
               return;   
           }
           else 
           {
               $verify_data['id'] = $game_array;
               
               return $verify_data['id'];
           }
 
    }
    
    public function formOptions($req_type)
    {
        $options = $this->db->prepare('SELECT `value` FROM `filters` WHERE type = ?');
        $options->execute(array($req_type));
        $fetch = $options->fetchAll(PDO::FETCH_ASSOC);
        $rC = $options->rowCount();
        $fetch['biggestID'] = $rC;
        
        return $fetch;
    }


}