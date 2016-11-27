<?php
class manager_studentsController extends BasisController
{
	private $student;
	private $arrStudent;
	private $studentList;
	
    public function indexAction() { 
		$this->pathAccess = 'students/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("students/manager_studentsIndex.tmpl");
		$this->studentList = new studentsRepository();
	
		$this->page->displayPage($this->arrParameterForPage);		
    }
}