<?php
class teacher_studentsController extends BasisController
{
	protected $student;
	protected $arrStudent;
	protected $studentList;
	
	public function __construct(){
		parent::__construct();	
	}
	
    public function indexAction() { 
		$this->pathAccess = 'students/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("students/teacher_studentsIndex.tmpl");

		$this->page->displayPage($this->arrParameterForPage);
		
    }
}