<?php
class manager_indexController extends BasisController
{
	public function indexAction() { 
		$this->pathAccess = 'index/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("index/manager_index.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);		
    }
}