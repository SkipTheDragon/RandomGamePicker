<?php
class AdvancedController Extends Controller {
    
  
    private $model;
    
    public function index()
    {
       
        $this->model  = $this->model('advanced');
        $this->view('advanced', ['run' =>  $this->run(),
            'genres' => $this->model->formOptions('genres'),
            'features' => $this->model->formOptions('feature')
        ]);
        
    }
    private function nextStep($actual_pos) 
    {
        $math = $actual_pos + 1;
        
        if($math < 5)
        {
            return header("location:/advanced/?step=$math");
        }
    }
    
    private function run()
    {
        if(isset($_GET['step'])){ $step = $_GET['step']; }
        if(empty($step)) { $step = 1; }
        
        if($step > 1 && empty($_SESSION['games']))
        {
            $_SESSION['games'] = $this->model->allGames();
        }
        
        if(isset($_POST['form']) && isset($_POST['submit']))
        {

            if($step == 1 && (isset($_POST['form']['dlc']) || isset($_POST['form']['coming_soon']) || isset($_POST['form']['min_price']) || isset($_POST['form']['max_price'])))
            {
                $_SESSION['games'] = $this->firstStep();
            }
            elseif($step == 2 && isset($_POST['form']['categories']))
            {
                $_SESSION['games'] = $this->secondStep($_SESSION['games']);
            }
            elseif($step == 3 && isset($_POST['form']['genres']))
            {
                $_SESSION['games'] = $this->thirdStep($_SESSION['games']);
            }
            if($step <= 3)
            {
                $this->nextStep($step);
            }
  
            $this->sessionUpdate($step);
        }

        if($step == 4)
        {
            if(empty($_SESSION['games']))
            {
                $_SESSION['games'] = $this->model->allGames();
                
            }
            $game = $this->model->randomGame($_SESSION['games']);
            header('location:/game/?id='.$_SESSION['games'][$game].'');
            unset($_SESSION['games']);
            $_SESSION['last_step'] = 1;
        }
        
        $math = $step - 1;
        $this->sessionDestroyer($math);
        
      /* echo "<pre>";
        var_dump($_SESSION['last_step']);
        var_dump($_POST['submit']);
        echo "</pre>";
        */ 
    }
    
    private function sessionDestroyer($step)
    {
        if($step == 1 && !empty($_SESSION['games']))
        {
            unset($_SESSION['games']);
        }
        
        if(!isset($_SESSION['last_step']))
        {
            $_SESSION['last_step'] = 1;
        }
        
        if(isset($_SESSION['last_step']) && !empty($_SESSION['games']))
        {
            if($_SESSION['last_step'] != $step)
            {
                unset($_SESSION['games']);
                header('location:/advanced/?step=1');
                $_SESSION['last_step'] = 1;
            }
        }
    }
    
    private function sessionUpdate($step) 
    {
        if($step == 1)
        {
            $_SESSION['last_step'] = 1;
        }
        elseif($step == 2)
        {
            $_SESSION['last_step'] = 2;
        }
        elseif($step = 3)
        {
            $_SESSION['last_step'] = 3;
        }
    }
    
    private function firstStep() 
    {
        $game_array = array();
                     
        if (!empty($_POST['form']['dlc']))
        {
            $game_choice = $this->model->setFilterRule('dlc','IS NOT NULL');
                     
            array_push($game_array,$game_choice);
        }
        if (!empty($_POST['form']['coming_soon']))
        {
            $game_choice = $this->model->setFilterRule('coming_soon','!= 0');
                        
            array_push($game_array,$game_choice);
        }
        if (!empty($_POST['form']['min_price']) && !empty($_POST['form']['max_price']))
        {
            if(($_POST['form']['min_price'] > 0) && ($_POST['form']['max_price'] > 0))
            {
                $min_price = $_POST['form']['min_price'];
                $max_price = $_POST['form']['max_price'];
                $form_prices = "BETWEEN $min_price AND $max_price";
                $game_choice = $this->model->setFilterRule('price',$form_prices);
                                
                array_push($game_array,$game_choice);
            }
        }
                    
        if(!isset($game_array[1])){ $game_array[1] = $game_array[0]; }
        if(!isset($game_array[2])){ $game_array[2] = $game_array[0]; }
                    
        $game_array = array_intersect($game_array[0],$game_array[1],$game_array[2]);
                   
        return  $game_array;
    }
    
    private function secondStep($game_array)
    {
        if(empty($game_array)) {$game_array = $this->model->allGames();}
        
        if (!empty($_POST['form']['categories']))
        {
            $chosen_filters = $_POST['form']['categories'];
              
            if(!is_array($chosen_filters))
            {
                $chosen_filters = $this->model->stringToArray($_POST['form']['categories']);
            }
               
            $data_array = array();
                
            foreach ($game_array as $game_id) 
            {
                $filtrer = $this->model->filterArrays($game_id, $chosen_filters,'categories');
                    
            if(!is_null($filtrer))
            {
                array_push($data_array,$filtrer);
            }
        }
        return $data_array;
        }
    }
    
    private function thirdStep($game_array)
    {
        if(empty($game_array)) {$game_array = $this->model->allGames();}
        
        if (!empty($_POST['form']['genres']))
        {
            $chosen_filters = $_POST['form']['genres'];
            
            if(!is_array($chosen_filters))
            {
                $chosen_filters = $this->model->stringToArray($_POST['form']['genres']);
            }
            
            $data_array = array();
            
            foreach ($game_array as $game_id)
            {
                $filtrer = $this->model->filterArrays($game_id, $chosen_filters,'genres');
                
                if(!is_null($filtrer))
                {
                    array_push($data_array,$filtrer);
                }
            }
            return $data_array;
        }
    }
    


    
}