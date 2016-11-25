<?php
class indexController extends BaseController
{
    public function indexAction() { 
		
		
		$templateFileData = "index.tmpl";
		$tmp = new indexPage("index.tmpl");
		$tmp->displayPage();

    }
}