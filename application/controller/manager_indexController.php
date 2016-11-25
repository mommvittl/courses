<?php
class manager_indexController extends BaseController
{
    public function indexAction() { 
		$arrParameterForPage = [];
		$arrParameterForPage['userNameRegistr'] = $_SESSION['username'];
		$tmp = new indexPage("manager_index.tmpl");
		$tmp->displayPage($arrParameterForPage);
		
    }
}