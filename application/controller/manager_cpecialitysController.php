<?php
class manager_cpecialitysController extends BasisController
{
	private $cpecialitys = null;
	private $arrCpecialitys = null;
	private $cpecialitysList = null;
	private $teacher = null;
	private $arrTeacher = null;
	private $teacherList = null;
	
	public function __construct(){
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
		if(isset($_POST['createCpecialitysFormGo']) ){
			var_dump($_POST);
			echo "<p></p>";
			$arr= ['title'=>trim($_POST['title']),'priseBasis'=>trim($_POST['priseBasis']),'description'=>trim($_POST['description']),
				'quantity'=>trim($_POST['quantity']),'bossId'=>(int)($_POST['bossId']),'work'=>'1'];
			if((int)($_POST['bossId']))	{
				 $arr['bossId'] = (int)($_POST['bossId']);
			}else{ $arr['bossId'] = null; }	
			$this->cpecialitys = cpecialitysModel::fromState($arr);
			if ( cpecialitysValidate::validate($this->cpecialitys) ){
				$this->cpecialitysList->createElement($this->cpecialitys);
			}
//			$t = 
			
		}
		
		$this->pathAccess = 'cpecialitys/create';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("cpecialitys/manager_cpecialitysCreate.tmpl");		
		$this->arrParameterForPage['arrCpecial'] = $this->getArrayCpecialitysForPage();
		$this->teacherList = new teacherRepository;
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
	
	private function getArrayCpecialitysForPage(){
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










