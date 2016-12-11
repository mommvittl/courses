<?php
class manager_ajaxController  extends BasisController{
	
	public function __construct(){
		header('Content-Type: text/XML');
		header(' Cache-Control: no-cache');
	}
	public function newCpecAction(){
		
		$str = "<response><functionHandler>viewNewCpecData</functionHandler>RRRRR1111111111111111111RRRRRRRRRRRRRR</response>";
		exit($str);
	}
	
}