<?php
class manager_accessController extends BaseController{
	
	private $userList;
	private $teachList;
	private $userArray;
	private $teachArray;
	private $arrayResultUserData = [];
	private $arrayResultTechData = [];
	
	public function __construct(){
        parent::__construct();
    }
	
    public function indexAction() {
		$this->pathAccess = 'access/index';
		$arrParameterForPage = [];
		$arrParameterForPage['pathAccess'] = $this->pathAccess;
		$arrParameterForPage['userNameRegistr'] = $_SESSION['username'];
		$this->page = new indexPage("access/manager_accessIndex.tmpl");			
		$this->userList = new accessRepository; 
		$this->teachList = new teachRepository; 
		$this->userArray = $this->userList->getUserById();
		$this->arrayResultUserData = [];
		foreach($this->userArray as $key=>$value){
			if ($value->idStaff){ $this->teachArray = $this->teachList->getTeachById($value->idStaff)[0]; }
			$this->arrayResultUserData[] = ['username'=>$value->username,'status'=>$value->status,'name'=>$this->teachArray->name,'surname'=>$this->teachArray->surname,
				'birthday'=>$this->teachArray->birthday];			
		}	
		$arrParameterForPage['arrUser'] = $this->arrayResultUserData;
		$this->page->displayPage($arrParameterForPage);
    }
	
	public function authorizeAction(){
		$this->page = new indexPage("access/manager_accessAuthorize.tmpl");	
		$this->pathAccess = 'access/authorize';
		$arrParameterForPage['userNameRegistr'] = $_SESSION['username'];	
		$arrParameterForPage = [];
		$this->arrayResultTechData = [];
		$this->arrayResultUserData = [];
		$arrParameterForPage['pathAccess'] = $this->pathAccess;	
		$this->userList = new accessRepository; 
		$this->teachList = new teachRepository; 		
		if (isset($_POST['accessNewFormGo'])){
			foreach($_POST as $key=>$val){
				if(is_int($key)){
					$array = [];
					$array = ['id' => $key,'status' => $_POST['status_' . $key],'idStaff' => $_POST['idStaff_' . $key] ];
					if ($array['id'] && $array['status'] && $array['idStaff']  ){ 
						$teach = [];
						$teach = $this->teachList->getTeachById($array['idStaff']);	
						if ((in_array($array['status'],array('manager','teacher'))) && (is_object($teach[0])) && (!$teach[0]->idAuthent)){ 
							$this->userList->addRegistration($array);								 
						}														
					}
				}
			}			
		}						
		$this->userArray = $this->userList->getNewUser();
		$this->teachArray = $this->teachList->getTeachLessAccess();
		if($this->userArray){
				foreach($this->userArray as $value){
					$this->arrayResultUserData[] = ['id'=>$value->id,'username'=>$value->username,'userpassw'=>$value->userpassw];
					$arrParameterForPage['arrUser'] = $this->arrayResultUserData;		
				}
				if ($this->teachArray){
					foreach($this->teachArray as $value){
						$nameDat = $value->name . " " . $value->surname . " " . $value->birthday . " " . " регистрация доступа: ";
						$nameDat .= ($value->statusAuthent) ? $value->statusAuthent : ' нет... ';				
						$this->arrayResultTechData[] = ['id' => $value->id, 'name' => $nameDat ];
					}
					$arrParameterForPage['arrTeach'] = $this->arrayResultTechData;			
					}else{
						$arrParameterForPage['flagInform'] = true;
						$arrParameterForPage['strInform'] = 'На данный момент нет сотрудников с неустановленными правами доступа.
							Перейдите с меню редактирования или внесите в базу данных нового сотрудника.';
					}
			}else{
				$arrParameterForPage['flagError'] = true;
				$arrParameterForPage['strError'] = 'На данный момент нет пользователей без регистрации.';
			}	
		$this->page->displayPage($arrParameterForPage);
	}
	
	public function updateAction(){
		$this->pathAccess = 'access/update';
		$arrParameterForPage['pathAccess'] = $this->pathAccess;
		$arrParameterForPage['userNameRegistr'] = $_SESSION['username'];		
		$this->page = new indexPage("access/manager_accessUpdate.tmpl");
		$this->userList = new accessRepository; 
		$this->teachList = new teachRepository;		
		if (isset($_POST['accessUpdateFormGo'])){
			foreach($_POST as $key=>$value){
				if(is_int($key) && $value && $this->userList->getUserById($key) && in_array($value,array('manager','teacher'))) {
				$this->userList->updateUserStatus($key,$value);
				}
			}
			if($_POST['delete']){
				foreach($_POST['delete'] as $value){
					$this->userList->deleteUser($value);
				}
			}			
		}
		$this->userArray = $this->userList->getUserById();
		$this->arrayResultUserData = [];
		foreach($this->userArray as $key=>$value){
			$flag = false;
			if ($value->idStaff){ $this->teachArray = $this->teachList->getTeachById($value->idStaff)[0]; $flag = true; }
			$this->arrayResultUserData[] = ['id'=>$value->id,'username'=>$value->username,'status'=>$value->status,'name'=>$this->teachArray->name,'surname'=>$this->teachArray->surname,
				'birthday'=>$this->teachArray->birthday,'flag'=>$flag];			
		}	
		$arrParameterForPage['arrUser'] = $this->arrayResultUserData;
		$this->page->displayPage($arrParameterForPage);	
	}
	
}












