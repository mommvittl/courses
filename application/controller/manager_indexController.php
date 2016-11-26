<?php
class manager_indexController extends BaseController
{
    public function indexAction() { 
		$this->pathAccess .= 'index/index';
		$arrParametr = []; 
		$arrParametr['pathAccess'] = $this->pathAccess;
		$arrParametr['userNameRegistr'] = $_SESSION['username'];
		$this->page = new indexPage("index/manager_index.tmpl");
		$this->page->displayPage($arrParametr);
		
    }
}