<?php
class teacher_temetableController extends BasisController
{
    public function indexAction() { 
		$this->pathAccess = 'temetable/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("temetable/teacher_temetableIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
}