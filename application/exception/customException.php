<?php
class customException extends Exception{
	private $messageErr;customException
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