<?php

class Application
{
    private $url_controller = null;
    private $url_action = null;
    private $url_params = array();
	
    public function __construct(){
		set_exception_handler(array(get_class($this), "getStaticException"));
    }
	public static function getStaticException($exception){
		throw new customException("Error",43);
	}
    public function run() {
		try{			
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
//						header('location: ' . URL . 'problem2');
						throw new customException("Methot not found",404);	
					}
				}
			} else {
//				header('location: ' . URL . 'problem3');
				throw new customException("File not found",403);
			}
			
		}catch( customException $e ){
			$e->printMessage();
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
class customException extends Exception{
	private $messageErr;
	private $codeErr;
	public function __construct($messErrData,$codeErrData){
		$this->messageErr = $messErrData;
		$this->codeErr = $codeErrData;
	}
	
	public function __toString(){
		$this->printMessage();
	}
	
	public function printMessage(){
		switch($this->codeErr){
			case '404':
				$file = "/application/view/error.php";
				break;
			default:
				$file = "/application/view/error.php";
		}
//		echo ( file_exists("errorView.php") )?"YY<br>":"NOO<br>";
		echo "<html><head><meta charset=\"utf-8\"><title>Error</title><style>.errorDiv { width: 100%; max-width: 600px; text-align: center;
		margin: 0 auto; background: #E0B639; color: #800000; height: 100%; max-height: 600px; border: 6px double #800000; font: 2rem/2.5rem arial;  }
		</style></head><body><div class=\"errorDiv\"><h1>Sorry...Error...</h1>
		<H2>".$this->messageErr."</H2></div></body></html>";		
	}
	public static function exceptionHandler(){
		throw new customExeption();
	}
}