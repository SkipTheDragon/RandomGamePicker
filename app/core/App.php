<?php

class App extends Base
{
    /**
     * This is the URL used to fetch the controller, method and params.
     *
     * @var
     */
    protected $url;

    /**
     * The default controller.
     *
     * @var string
     */
    protected $controller = 'homeController';

    /**
     * The default method.
     *
     * @var string
     */
    protected $method = 'index';

    /**
     * Params from the URI; default empty array.
     *
     * @var array
     */
    protected $params = [];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->url = $this->parseUrl();

    }

    /**
     * Run the app by calling the controller method and passing it
     * any given params.
     */
    public function dispatch()
    {
        $this->setController();

        $this->setMethod();

        $this->setParams();
        
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parse the url into an array so we can access the indexes as a
     * controller, method and optional params.
     *
     * @return array
     */
    private function parseUrl()
    {
        if(isset($_GET['url']) && !empty($_GET['url'])) 
        {
            return explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));
        }
        elseif(!isset($_GET['url']) && empty($_GET['url']))
        {
            return array('home');
        }

    }

    /**
     * Set the controller using the first index of the url.
     */
    private function setController()
    {
        $url = $this->url[0];

        $path = 'app/controllers/' . $url . 'Controller.php';
        
        
        if(file_exists($path)) 
        {
            $this->controller = $url . 'Controller';
            unset($url);
        }
        elseif(!file_exists($path) && !empty($url)) 
        {
            $this->respondNotFound();
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller();
    }

    /**
     * Set the method using the second index of the url.
     */
    private function setMethod()
    {
        if (isset($this->url[1]) && method_exists($this->controller, $this->url[1])) {
            $this->method = $this->url[1];
            unset($this->url[1]);
        }
    }

    /**
     * Set the params to pass to the controller method.
     *
     * Params equal the remaining values in the url array rebased.
     *
     * Additionally, we pass the $_POST super global for any optional
     * POST data
     */
    private function setParams()
    {
        $this->params = $this->url ? [array_values($this->url), $_POST] : [$_POST];
    }
}