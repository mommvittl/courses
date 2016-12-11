<?php
class teacher_registerController extends BasisController
{
	public function __construct(){
		parent::__construct();	
	}
	
    public function indexAction() { 
		$this->pathAccess = 'register/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("register/teacher_registerIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
}