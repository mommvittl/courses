<?php
class manager_cpecialitysController extends BasisController
{
	protected $cpecialitys = null;
	protected $arrCpecialitys = null;
	protected $cpecialitysList = null;
	protected $teacher = null;
	protected $arrTeacher = null;
	protected $teacherList = null;
	
	public function __construct(){
		parent::__construct();
		$this->cpecialitysList = new cpecialitysRepository;	
	}
	
    public function indexAction() { 
		$this->pathAccess = 'cpecialitys/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("cpecialitys/manager_cpecialitysIndex.tmpl");		
		$this->arrParameterForPage['arrCpecial'] = $this->getArrayCpecialitysForPage();
		$this->page->displayPage($this->arrParameterForPage);
		
    }
	
	public function allAction(){
		$this->pathAccess = 'cpecialitys/all';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("cpecialitys/manager_allCpecialitysIndex.tmpl");		
		$this->arrCpecialitys = $this->cpecialitysList->getAllElement();
		foreach($this->arrCpecialitys as $value){
			$arrCpecParametr = $value->getArrForXMLDocument();
			if(is_object($value->boss)){
				$arrCpecParametr['bossSurname'] = $value->boss->surname;
			}			
			$arr[] = $arrCpecParametr;
		}
		$this->arrParameterForPage['arrCpecial'] = $arr;
		$this->page->displayPage($this->arrParameterForPage);
	}
	
	public function createAction(){
		$this->pathAccess = 'cpecialitys/create';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("cpecialitys/manager_cpecialitysCreate.tmpl");	
		$this->teacherList = new teacherRepository;
		if(isset($_POST['addCpecialitysFormGo']) ){
			$valueData = [];
			$valueData['title'] = trim($_POST['title']); 
			$valueData['priseBasis'] = $_POST['priseBasis']; 
			$valueData['description'] = trim($_POST['description']); 
			$valueData['quantity'] = (int)($_POST['quantity']); 
			$valueData['bossId'] = (int)($_POST['bossId']); 
			$valueData['work'] = 1;
			$this->cpecialitys = cpecialitysModel::fromState($valueData);
			$this->arrParameterForPage['informFlag'] = true;
			if ( cpecialitysValidate::validate($this->cpecialitys)){
				if( cpecialitysValidate::addCpecialitysValidate($this->cpecialitys) ){
					if( $this->cpecialitysList->createElement($this->cpecialitys) !== false ){					
						$this->arrParameterForPage['stringInform'] = "Новая специальность добавлена в базу данных";
						$valueData = [];
					}else{
						$this->arrParameterForPage['stringInform']  = "Ошибка при записи данных.";
					}
				}else{
					$this->arrParameterForPage['stringInform'] = "Такая специальность уже есть в базе данных";
				}	
			}else{
				$this->arrParameterForPage['stringInform']  = "Некорректные данные.";
			}
			$this->arrParameterForPage['valueData'] = $valueData;
		}
		$this->arrTeacher = $this->teacherList->getAllWorkElement();
		$arr = [];
		foreach($this->arrTeacher as $value){
			$arr[] = [ 'id'=>$value->id,'surname'=>$value->surname ];
		}
		$this->arrParameterForPage['arrTeacher'] = $arr;
		$this->page->displayPage($this->arrParameterForPage);	
	}
	
	public function deleteAction(){
		if(isset($_POST['createCpecialitysFormGo']) ){
			
		}
		
		$this->pathAccess = 'cpecialitys/delete';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("cpecialitys/manager_cpecialityDelete.tmpl");		
		$this->arrParameterForPage['arrCpecial'] = $this->getArrayCpecialitysForPage();
			$this->page->displayPage($this->arrParameterForPage);
	}
	
	protected function getArrayCpecialitysForPage(){
		$arr = [];		
		$this->arrCpecialitys = $this->cpecialitysList->getAllWorkElement();
		foreach($this->arrCpecialitys as $value){
			$arrCpecParametr = $value->getArrForXMLDocument();
			if(is_object($value->boss)){
				$arrCpecParametr['bossSurname'] = $value->boss->surname;
			}			
			$arr[] = $arrCpecParametr;
		}
		return $arr;
	}
}










