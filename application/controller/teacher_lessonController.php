<?php
class teacher_lessonController extends BaseController
{
	public function __construct(){
		parent::__construct();	
	}
	
	public function indexAction() { 
		$this->pathAccess = 'lesson/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("lesson/teacher_index.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);		
    }
	
	public function newlessonAction($idTemetable = null){
		if(is_null($idTemetable) ){ $this->indexAction(); }		
		$this->pathAccess = 'lesson/newlesson';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->arrParameterForPage['idTemetable'] = (int)$idTemetable;
		$this->page = new indexPage("lesson/teacher_newlesson.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		var_dump($_POST);
	}
}