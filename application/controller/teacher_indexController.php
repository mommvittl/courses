<?php
class teacher_indexController extends BaseController
{
    public function indexAction() { 
		$this->pathAccess .= 'index/index';
		$arrParametr = []; 
		$arrParametr['pathAccess'] = $this->pathAccess;
		$arrParameterForPage['userNameRegistr'] = $_SESSION['username'];
		$this->page = new indexPage("teach_index.tmpl");
		$this->page->displayPage($arrParameterForPage);
    }
}