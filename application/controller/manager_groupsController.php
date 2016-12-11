<?php
class manager_groupsController extends BasisController
{
    public function indexAction() { 
		$this->pathAccess = 'groups/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("groups/manager_groupsIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
}