<?php
class manager_studentsController extends BasisController
{
	protected $student;
	protected $arrStudent;
	protected $studentList;
	
	public function __construct(){
		parent::__construct();	
		$this->studentList = new studentsRepository();
	}
	
    public function indexAction() { 
		$this->pathAccess = 'students/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("students/manager_studentsIndex.tmpl");
		$this->studentList = new studentsRepository();
		
		$this->page->displayPage($this->arrParameterForPage);		
    }
	
	public function createAction(){
		$this->pathAccess = 'students/create';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("students/manager_studentsCreate.tmpl");		
		if(isset($_POST['addStudentFormGo']) ){
			$valueData = [];
			$valueData['surname'] = trim($_POST['surname']); 
			$valueData['name'] = trim($_POST['name']); 
			$valueData['birthday'] = $_POST['birthday']; 
			$valueData['telephon'] = trim($_POST['telephon']); 
			$valueData['adress'] = trim($_POST['adress']); 
			$valueData['email'] = trim($_POST['email']); 
			$valueData['skype'] = trim($_POST['skype']); 
			$valueData['dogovor'] = (int)($_POST['dogovor']);
			$valueData['work'] = 1;
			$this->student = studentsModel::fromState($valueData);
			$this->arrParameterForPage['informFlag'] = true;
			if( studentsValidate::validate($this->student) ){
				if( studentsValidate::addStudentValidate($this->student) ){
					if( $this->studentList->createElement($this->student) !== false ){					
						$this->arrParameterForPage['stringInform'] = "Студент добавлен в базу данных";
						$valueData = [];
					}else{
						$this->arrParameterForPage['stringInform']  = "Ошибка при записи студента.";
					}
				}else{
					$this->arrParameterForPage['stringInform'] = "Такой студент уже есть в базе данных";
				}				
			}else{
				$this->arrParameterForPage['stringInform']  = "Некорректные данные.";
			}					
			$this->arrParameterForPage['valueData'] = $valueData;
		}
		
		$this->page->displayPage($this->arrParameterForPage);	
	}
	
}