<?php
class manager_groupsController extends BasisController
{
	protected $group;
	protected $arrGroup;
	protected $groupsRepository;
	protected $groupsListRepository;
	protected $groupsList;
	protected $lessonRepository;
	protected $cpecialitys;
	protected $arrCpecialitys;
	protected $cpecialitysRepository;
	protected $teacher = null;
	protected $arrTeacher = null;
	protected $teacherRepository = null;
	protected $studentsRepository = null;
	
	public function __construct(){
		parent::__construct();
		$this->groupsRepository = new groupsRepository();
		$this->cpecialitysRepository = new cpecialitysRepository();
		$this->teacherRepository = new teacherRepository();
	}
	
    public function indexAction() { 
		$this->pathAccess = 'groups/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("groups/manager_groupsIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
	
	public function createAction(){
		$this->pathAccess = 'groups/create';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("groups/manager_groupsCreate.tmpl");		
		$this->arrParameterForPage['arrCpecial'] = $this->getAllCpecialitys();
		$this->arrParameterForPage['arrTeacher'] = $this->getAllTeachers();		
		if(isset($_POST['addGroupsFormGo']) ){
			$valueData = [];
			$valueData['idCpecial'] = (int)($_POST['idCpecial']); 
			$valueData['title'] = trim($_POST['title']); 
			$valueData['price'] = $_POST['price']; 
			$valueData['periodicity'] = $_POST['periodicity']; 
			$valueData['quantity'] = trim($_POST['quantity']); 
			$valueData['duration'] = (int)($_POST['duration']); 
			$valueData['bossId'] = (int)($_POST['bossId']); 
			$valueData['startDataPlan'] = $_POST['startDataPlan'];
			$valueData['endDataPlan'] = $_POST['endDataPlan'];
			$valueData['status'] = 'anons';
			$valueData['numLesson'] = $_POST['numLesson'];
			$this->group = groupsModel::fromState($valueData);
			$this->arrParameterForPage['informFlag'] = true;
			if ( groupsValidate::validate($this->group)){
				if( groupsValidate::addGroupsValidate($this->group) ){
					if( $this->groupsRepository->createElement($this->group) !== false ){		
						$this->arrParameterForPage['stringInform'] = "Новая группа добавлена в базу данных";
						$valueData = [];
					}else{
						$this->arrParameterForPage['stringInform']  = "Ошибка при записи данных.";
					}
				}else{
					$this->arrParameterForPage['stringInform'] = "Такая группа уже есть в базе данных";
				}	
			}else{
				$this->arrParameterForPage['stringInform']  = "Некорректные данные.";
			}
			$this->arrParameterForPage['valueData'] = $valueData;			
		}
		
		$this->page->displayPage($this->arrParameterForPage);
	}

	public function studentsAction() { 
		$this->pathAccess = 'groups/students';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("groups/manager_groupsStudents.tmpl");
		$this->arrCpecialitys = $this->cpecialitysRepository->getAllWorkElement();
		$this->arrParameterForPage['arrCpecial'] = $this->getAllCpecialitys();
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
	
	public function startAction() { 
		$this->pathAccess = 'groups/start';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("groups/manager_groupsStart.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);		
    }

	public function archivAction() { 
		$this->pathAccess = 'groups/archiv';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("groups/manager_groupsArchiv.tmpl");			
		if(isset($_POST['deleteGroupInArhivGo']) ){
			$this->lessonRepository = new lessonRepository;
			$this->groupsListRepository = new groupsListRepository;
			$this->studentsRepository = new studentsRepository;
			if(isset($_POST['groupName']) && is_array($_POST['groupName'])){
				foreach($_POST['groupName'] as $value){
					$this->group = $this->groupsRepository->getElementById( (int)$value );
					if(is_object($this->group) ){
						$this->lessonRepository->deleteElementByIdGroup($this->group->id);
						$this->groupsList = $this->groupsListRepository->getElementById($this->group->id);					
						if(is_array($this->groupsList->groupList) ){
							foreach($this->groupsList->groupList as $elem){
								if($elem['status'] == 'graduate'){ $this->studentsRepository->deleteElement($elem['students']); }
							}
						}
						$this->groupsListRepository->deleteElement($this->groupsList);
						$this->groupsRepository->deleteElement($this->group);
					}
				}
				$this->arrParameterForPage['informFlag'] = true;
				$this->arrParameterForPage['stringInform'] = "Информация связанная с группой удалена из базы данных";
			}
		}
		$arr = [];
		$this->arrGroup = $this->groupsRepository->searchBystatus('archiv');	
		if(is_array($this->arrGroup) ){
			foreach($this->arrGroup as $value){ 
				$newgrup = $value->getArrForXMLDocument();
				$newgrup['bossSurname'] = (is_object($value->boss)) ? $value->boss->surname : '';
				$arr[] =  $newgrup;
			}
			$this->arrParameterForPage['arrGroup'] = $arr;
		}
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }
	
	public function endingAction() { 
		$this->pathAccess = 'groups/ending';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("groups/manager_groupsEnding.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);
		
    }	
	private function getAllCpecialitys(){
		$this->arrCpecialitys = $this->cpecialitysRepository->getAllWorkElement();
		$arr = [];
		foreach($this->arrCpecialitys as $value){
			$arr[] = $value->getArrForXMLDocument();
		}
		return $arr;
	}
	private function getAllTeachers(){
		$this->arrTeacher = $this->teacherRepository->getAllWorkElement();
		$arr = [];
		foreach($this->arrTeacher as $value){
			$arr[] =  $value->getArrForXMLDocument();
		}
		return $arr;
	}
}





















