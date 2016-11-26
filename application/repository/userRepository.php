<?php
class userRepository extends baseRepository{
	private $login = false;
	private $password = false;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getUserByLogin($userLogg,$userPassw){		
		$this->login = hash( "sha256", $userLogg );
		$this->password = hash( "sha256", $userPassw );		
		$this->strQuery = "select * from `authents` where `login`=? and `password`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($this->login,$this->password) );
		if ($this->result !== false){
			$this->row = $this->result->fetch();		
			if($this->row){				
				$arr = ['id'=>$this->row['id'],'login'=>$this->row['login'],'password'=>$this->row['password'],
				'status'=>$this->row['status'],'username'=>$this->row['username'],'idStaff'=>$this->row['id_staff'] ];
				return userModel::fromState($arr);
			}
		}
		return false;		
	}
	public function	registrationNewUser($loginData,$passwordData,$usernameData){
		$this->login = hash( "sha256", $loginData );
		$this->password = hash( "sha256", $passwordData );	
		$this->strQuery = "insert into `authents` set `login`=?,`password`=?,`username`=?";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($this->login,$this->password,$usernameData) );
		if ( $this->result->rowCount() ){
			return $this->getUserByLogin($loginData,$passwordData);
		}else{
			return false;
		}	
	}
	public function updateUser($idData,$arrParametrData){
		
	}

}