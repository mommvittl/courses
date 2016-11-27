<?php
class manager_teachersController extends BasisController
{
    public function indexAction() { 
		$this->pathAccess = 'teachers/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("teachers/manager_teachersIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
}