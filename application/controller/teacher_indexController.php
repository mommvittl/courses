<?php
class teacher_indexController extends BaseController
{
    public function indexAction() { 
		$arrParameterForPage = [];
		$arrParameterForPage['userNameRegistr'] = $_SESSION['username'];
		$page = new indexPage("teach_index.tmpl");
		$page->displayPage($arrParameterForPage);
    }
}