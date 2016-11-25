<?php
class UserController extends BaseController
{
	protected $status = false;
	protected $username = false;
	private $login = false;
	private $password = false;
	private $user;
	private $userList;
	
	public function __construct(){
        parent::__construct();
    }
	
    public function indexAction() {
		echo "LoginIndex<br>";
    }
	
	 public function loginAction() {
		$arrParametr = [];  
		$page = new indexPage("login.tmpl"); 
		$this->userList = new userRepository;
		if(isset($_POST['login']) && isset($_POST['password'])){	
			$loginData = trim($_POST['login']);
			$passwordData = trim($_POST['password']);
			$this->user = $this->userList->getUserByLogin($loginData,$passwordData);
			if(is_object($this->user) ){
				if ( $this->userAuthentification($this->user) ){
					$_SESSION['loggedd'] = true;
					$_SESSION['id'] = $this->user->id;
					$_SESSION['username'] = $this->user->username;
					$_SESSION['status'] = $this->user->status;
					header("Location: http://courses/");	
				}							
			}
		} 
		$arrParametr['login'] = htmlentities($loginData , ENT_QUOTES);
		$page->displayPage($arrParametr);	
    }
	
	 public function registerAction() {
		$arrParametr = []; 
		$page = new indexPage("registration.tmpl"); 
		$this->userList = new userRepository;		
		if(isset($_POST['userRegFormGo'])){
			$loginData = trim($_POST['login']);
			$passwordData = trim($_POST['password']);
			$usernameData = trim($_POST['username']);
			$arrParametr['username'] = htmlentities($usernameData , ENT_QUOTES);
			$arrParametr['login'] = htmlentities($loginData , ENT_QUOTES);
			if(!empty($loginData) && !empty($passwordData) && !empty($usernameData) ){
					if($_POST['password'] == $_POST['password2']){
						if(is_object($this->userList->getUserByLogin($loginData,$passwordData))){
							$arrParametr['errFlag'] = true; 			
							$arrParametr['errString'] = 'Вы уже зарегистирированы в системе.'; 	
						}else{
							$this->user = $this->userList->registrationNewUser($loginData,$passwordData,$usernameData);
							if ($this->user != false){
								$arrParametr['errFlag'] = true; 			
								$arrParametr['errString'] = 'Вы зарегистрированы в системе как ' . $arrParametr['username'] . " Ваш логин " . $arrParametr['login'] . " Для
									получения полноценного доступа ваш статус должен подтвердить администратор."; 	
								$page->displayPage($arrParametr);
								exit;
							}	
						}
										
					}else{ 
						$arrParametr['errFlag'] = true; 			
						$arrParametr['errString'] = 'Проверьте вводимые данные, пароли не совпадают.'; 			
					}				
			}else{ 
				$arrParametr['errFlag'] = true; 
				$arrParametr['errString'] = 'Не все поля заполнены'; 
			}
		}	
		$page->displayPage($arrParametr);
		
    }
	 public function logoutAction() {
			unset($_SESSION['loggedd']);
			unset($_SESSION['id']);
			unset($_SESSION['username']);
			unset($_SESSION['status']);
			header("Location: http://courses/");			
    }
	
	private function userAuthentification($userAuthData){
			$arrStatusUser = ['manager','teacher'];
			$id = $userAuthData->id;
			$login = $userAuthData->login;
			$password = $userAuthData->password;
			$username = $userAuthData->username;
			$status = $userAuthData->status;
			$userPassw = $userAuthData->userPassw;
			$idStaff = $userAuthData->idStaff;
			if(in_array($status,$arrStatusUser) ){				
				return true;
			}
			return false; 	
	}
	
}