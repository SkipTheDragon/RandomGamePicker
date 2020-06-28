<?php 
class simpleController Extends Controller {
    
    private $advanced_model;
    
    public function index()
    {
        $this->advanced_model  = $this->model('advanced');
        $this->view('simple',["run" => $this->run()]);
    }
    
    private function run()
    {
        if(isset($_POST['submit']))
        {
            $game_id = $this->advanced_model->randomGame($this->advanced_model->allGames());
            
            header('location:/game/?id='.$game_id.'');
        }
    }
    
}