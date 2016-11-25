<?php
class manager_accessController extends BaseController{
	
	private $userList;
	private $userArray;
	private $arrayResultUserData = [];
	
	public function __construct(){
        parent::__construct();
    }
	
    public function indexAction() { 
		$arrParameterForPage = [];
		$arrParameterForPage['userNameRegistr'] = $_SESSION['username'];
		$page = new indexPage("manager_index.tmpl");		
		$this->userList = new accessRepository; 
		$this->userArray = $this->userList->getUserById();
		if( $this->userArray && is_array($this->userArray) ){
			foreach($this->userArray as $value){
				$arrayResultUserData[] = ['id'=>$value->id,'username'=>$value->username,'userpassw'=>$value->userpassw,'status'=>$value->status,'idStaff'=>$value->idStaff];
				
			}
		}
		$arrParameterForPage['arrUser'] = $arrayResultUserData;		
		$page->displayPage($arrParameterForPage);		
    }
	
}