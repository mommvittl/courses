<?php
class teacher_indexController extends BaseController
{
	public function __construct(){
		parent::__construct();	
	}
	
	public function indexAction() { 
		$this->pathAccess = 'index/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("index/teacher_index.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);		
    }
}