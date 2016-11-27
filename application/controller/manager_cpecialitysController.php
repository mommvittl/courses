<?php
class manager_cpecialitysController extends BasisController
{
    public function indexAction() { 
		$this->pathAccess = 'cpecialitys/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("cpecialitys/manager_cpecialitysIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
}