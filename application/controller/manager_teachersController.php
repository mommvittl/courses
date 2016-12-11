<?php
class manager_teachersController extends BasisController
{
	protected $teacher;
	protected $arrTeacher;
	protected $teacherList;
	
	public function __construct(){
		parent::__construct();	
	}
	
    public function indexAction() { 
		$this->pathAccess = 'teachers/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("teachers/manager_teachersIndex.tmpl");
/*		
		$this->teacherList = new teacherRepository;
		$this->arrTeacher = $this->teacherList->getAllElement();
		$arr = [];
		foreach($this->arrTeacher as $value){
			$arr[] = $value->getArrForXMLDocument();
		}
//		var_dump($arr);
*/
		$this->arrParameterForPage['arrTeacher'] = $arr;		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
	
	public function createAction(){
		$this->pathAccess = 'teachers/create';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("teachers/manager_teachersCreate.tmpl");
		$this->teacherList = new teacherRepository();
		if(isset($_POST['addTeacherFormGo']) ){
			$valueData = [];
			$valueData['surname'] = trim($_POST['surname']); 
			$valueData['name'] = trim($_POST['name']); 
			$valueData['birthday'] = $_POST['birthday']; 
			$valueData['telephon'] = trim($_POST['telephon']); 
			$valueData['adress'] = trim($_POST['adress']); 
			$valueData['email'] = trim($_POST['email']); 
			$valueData['skype'] = trim($_POST['skype']); 
			$valueData['status'] = 'teacher';
			$valueData['work'] = 1;
			$this->teacher = teacherModel::fromState($valueData);
			$this->arrParameterForPage['informFlag'] = true;
			if( teachersValidate::validate($this->teacher) ){
				if( teachersValidate::addTeachersValidate($this->teacher) ){
					if( $this->teacherList->createElement($this->teacher) !== false ){					
						$this->arrParameterForPage['stringInform'] = "преподаватель добавлен в базу данных";
						$valueData = [];
					}else{
						$this->arrParameterForPage['stringInform']  = "Ошибка при записи преподавателя.";
					}
				}else{
					$this->arrParameterForPage['stringInform'] = "Такой преподаватель уже есть в базе данных";
				}				
			}else{
				$this->arrParameterForPage['stringInform']  = "Некорректные данные.";
			}					
			$this->arrParameterForPage['valueData'] = $valueData;
		}
		
		$this->page->displayPage($this->arrParameterForPage);	
	}
	
	
}