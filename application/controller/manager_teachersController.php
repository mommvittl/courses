<?php
class manager_teachersController extends BasisController
{
	private $teacher;
	private $arrTeacher;
	private $teacherList;
	
    public function indexAction() { 
		$this->pathAccess = 'teachers/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("teachers/manager_teachersIndex.tmpl");
		$this->teacherList = new teacherRepository;
		$this->arrTeacher = $this->teacherList->getAllElement();
		$arr = [];
		foreach($this->arrTeacher as $value){
			$arr[] = $value->getArrForXMLDocument();
		}
//		var_dump($arr);
		$this->arrParameterForPage['arrTeacher'] = $arr;		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
}