<?php
class manager_auditoriasController extends BasisController
{
    public function indexAction() { 
		$this->pathAccess = 'teachers/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("auditorias/manager_auditoriasIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
}