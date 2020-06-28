<?php
class AdvancedController Extends Controller {

    private $model;

    private $form;
    
    private $step;

    public function index()
    {
        $this->model = $this->model('advanced');

        if (isset($_GET['step'])) $this->step = $_GET['step'];
        if (empty($this->step)) $this->step = 1;
        if(isset($_GET['step']) && $_GET['step'] >= 4) header("location:/advanced");
        if(isset($_POST['form'])) {
            foreach($_POST['form'] as $form_key => $form_item){
                $this->form[$form_key] = $this->sanitizeInputs($form_key,$form_item);
            }
        }
        $this->view('advanced', ['run' =>  $this->run(),
            'genres' => $this->model->getFilterDetailsByType('genres'),
            'features' => $this->model->getFilterDetailsByType('categories'),
            'title' => $this->title()
        ]);
    }
    private function title()
    {
        if($this->step == 1) {
            return 'Features';
        } elseif($this->step == 2) {
            return 'Categories';
        } elseif($this->step == 3) {
            return 'Genres';
        }  else {
            return 'To leave this page cuz itz bugged';
        }
    }
    private function run()
    {
       $this->sessionLeaksPrevention($this->step);
        if(empty($_SESSION['games'])) {
            $_SESSION['games'] = $this->model->allGames();
        }
        if(isset($this->form) && isset($_POST['submit']))
        {
            if($this->step == 1)
            {
                    $_SESSION['games'] = $this->firstStep();
            }
            elseif($this->step == 2 && isset($this->form['categories']))
            {
                    $_SESSION['games'] = $this->secondStep($_SESSION['games']);
            }
            elseif($this->step == 3 && isset($this->form['genres']))
            {
                    $_SESSION['games'] = $this->thirdStep($_SESSION['games']);
            }
           $this->nextStep($this->step);
        }
        if($this->step == 4) {
            if(empty($_SESSION['games'])) {
                header("location:/simple?auto_start=1");
            } else {
                $game = $this->randomGame($_SESSION['games']);
                header('location:/game/?id=' . $_SESSION['games'][$game] . '');
                unset($_SESSION['games']);
            }
        }
       // echo count($_SESSION['games']) . '<br/>';
        return FALSE;
    }

    private function firstStep()
    {
        $game_array = array();
        if(!empty($this->form['dlc']))
        $game_array[0] = $this->firstStepSimpleRules('dlc', 'IS NOT NULL');
        if(!empty($this->form['coming_soon']))
        $game_array[1] = $this->firstStepSimpleRules( 'coming_soon', '= 1');
        if (!empty($this->form['min_price'] && $this->form['max_price'])) {
            $price = $this->moneyTransform([$this->form['min_price'], $this->form['max_price']]);
            $form_prices = "BETWEEN $price[0] AND $price[1]";
            $game_array[2] = $this->firstStepSimpleRules((($this->form['min_price'] > 0) && ($this->form['max_price'] > $this->form['min_price'])), 'price', $form_prices);
        }
        if(!empty($this->form['demo']))
        $game_array[3] = $this->firstStepSimpleRules('demos', 'IS NOT NULL');

        for ($i = 1; $i < 4; $i++) {
                if(!empty($game_array[$i]) && empty($game_array[0])) {
                    if($i > 0) {
                        $math = $i - 1;
                        array_push($game_array[$math], $game_array[$i]);
                        unset($game_array[$i]);
                    }
                }
             }
             if(empty($game_array[1])) {
                 unset($game_array[1]);
             }
        if(count($game_array) > 1) {
            $game_list = call_user_func_array('array_intersect', $game_array);

            return  $game_list;
        }
        return $game_array[0];
    }

    private function secondStep($game_array)
    {
        if (!empty($this->form['categories'])) {
            $chosen_filters = $this->form['categories'];

            if(!is_array($chosen_filters)) {
                $chosen_filters = $this->stringToArray($this->form['categories']);
            }
            $data_array = array();
            foreach ($game_array as $gameid)
            {
                $filter = $this->model->filterArrays($gameid, $chosen_filters,'categories');
                if(!is_null($filter)) {
                    array_push($data_array,$filter);
                }
            }
            return $data_array;
        }
        return array();
    }

    private function thirdStep($game_array)
    {
        if (!empty($this->form['genres'])) {
            $chosen_filters = $this->form['genres'];

            if(!is_array($chosen_filters)) {
                $chosen_filters = $this->stringToArray($this->form['genres']);
            }

            $data_array = array();
            foreach ($game_array as $game_id)
            {
                $filter = $this->model->filterArrays($game_id, $chosen_filters,'genres');

                if(!is_null($filter)) {
                    array_push($data_array,$filter);
                }
            }
            return $data_array;
        }
        return array();
    }

    private function nextStep($actual_pos)
    {
        if($actual_pos <= 3) { // Goes To 4
            $math = $actual_pos + 1;
            return header("location:/advanced/?step=$math");
        }
        return FALSE;
    }

    private function randomGame($game_array)
    {
        return array_rand($game_array);
    }

    private function moneyTransform($prices)
    {
        $trans_prices = array();
        foreach ($prices as $price) {
            $transform = $price * 100;
            array_push($trans_prices,$transform);
        }

        return $trans_prices;
    }

    private function firstStepSimpleRules($form_input_name, $select_rule)
    {
        $result = $this->model->setFilterRule($form_input_name,$select_rule);
        $games = array();
        array_push($games,$result);
        return $games[0];
    }

    private function stringToArray($string)
    {
        $cleared_string = str_replace(['[',']'], '', $string);
        $array = explode(',', $cleared_string);

        return $array;
    }

     private function defaultFormInputs($form_key)
     {
         $default_keys = array('dlc','demo','score','coming_soon','min_price','max_price','categories','genres','submit');
         if (in_array($form_key, $default_keys)) {
             return TRUE;
         } else {
             throw new Exception("Not in array");
         }
         return FALSE;
     }

     private function sanitizeInputs($form_key,$form_item)
     {
             if($this->defaultFormInputs($form_key) == TRUE) {

             if (is_int($form_item) || ctype_digit($form_item)) {
                 $form_item = filter_var($form_item, FILTER_VALIDATE_INT);
                return $form_item;
             } elseif (is_array($form_item)) {
                 foreach ($form_item as $key => $item){
                     if(strlen($item) > 0) {
                         if (ctype_digit($item)) {
                             $form_item[$key] = filter_var($item, FILTER_VALIDATE_INT);
                         } elseif (is_string($item)) {
                             $form_item[$key] = filter_input(INPUT_POST, $item, FILTER_SANITIZE_STRING);
                         } elseif (is_int($item)) {
                             $form_item[$key] = filter_var($item, FILTER_VALIDATE_INT);
                         }
                     } else {
                        unset($form_item[$key]);
                     }
                 }
                 return $form_item;
             }  elseif (is_string($form_item)) {
                 $form_item = filter_input(INPUT_POST,$form_item,FILTER_SANITIZE_STRING);
                 return $form_item;
             }
         }
         return FALSE;
     }

    private function sessionLeaksPrevention($get)
    {
        if(!isset($_SESSION['id'])){
            $_SESSION['id'] = 0;
            return TRUE;

        }
        if($get == 1 && isset($_SESSION['games'])) {
            unset($_SESSION['games']);
            $_SESSION['id'] = 0;
            return TRUE;

        }
        if($_SESSION['id'] == $get || $get < $_SESSION['id']) {
            unset($_SESSION['games']);
            header("location:/advanced/?step=1");
            $_SESSION['id'] = 0;
            return TRUE;
        }

        if(isset($this->form) && isset($_POST['submit'])) {
            if($get == 1){$_SESSION['id'] = 1;}
            if($get == 2){$_SESSION['id'] = 2;}
            if($get == 3){$_SESSION['id'] = 3;}
            if($get == 4){$_SESSION['id'] = 4;}
            return TRUE;
        }
        return FALSE;
    }
}