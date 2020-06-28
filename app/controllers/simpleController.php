<?php 
class simpleController Extends Controller {
    
    private $model;
    
    public function index()
    {
        $this->model  = $this->model('advanced');
        $this->view('simple',["run" => $this->run()]);
    }
    
    private function run()
    {
        if(isset($_POST['submit']) || isset($_GET['auto_start']))
        {
            $game_id = array_rand($this->model->allGames());
            
            header('location:/game/?id='.$game_id.'');
        }
    }
    
}