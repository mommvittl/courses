<?php

class Application
{
    private $url_controller = null;
    private $url_action = null;
    private $url_params = array();
	
    public function __construct(){

    }

    public function run() {
        $this->splitUrl();
		if (empty($_SESSION['loggedd']) || !($_SESSION['loggedd'])|| !($_SESSION['status']) ){
			$this->url_controller = "userController";
			if ($this->url_action != "registerAction"){ $this->url_action = "loginAction"; }  
		}else{
			$userNameRegistration = $_SESSION['username'];
			$statusREgistration = $_SESSION['status'];
			$idREgistration = $_SESSION['id'];		
			if (!$this->url_controller) {
				$this->url_controller = 'IndexController';
				$this->url_action = 'indexAction';
			}
			if($this->url_controller != 'userController'){
				$this->url_controller = trim($_SESSION['status']) . "_" . $this->url_controller;
			}						
		} 		
	   if (file_exists(APP . 'controller/' . $this->url_controller . '.php')) {
            $this->url_controller = new $this->url_controller();
            if (method_exists($this->url_controller, $this->url_action)) {
                if (!empty($this->url_params)) {
                    call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);
                } else {
                    $this->url_controller->{$this->url_action}();
                }
            } else {
                if (strlen($this->url_action) == 0) {
                    $this->url_controller->indexAction();
                }
                else {
                    header('location: ' . URL . 'problem2');
                }
            }
        } else {
            header('location: ' . URL . 'problem3');
        }
    }
    private function splitUrl()
    {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->url_controller = isset($url[0]) ? strtolower($url[0]). 'Controller' : null;
            $this->url_action = isset($url[1]) ? strtolower($url[1]) . 'Action' : null;
            unset($url[0], $url[1]);
            $this->url_params = array_values($url);

        }
    }
}
