<?php
class accessRepository extends baseRepository{
	protected $userId = false;
	protected $arrResult = [];
	
	public function __construct(){
		parent::__construct();
	}
	public function getUserById($userIdData = false){
		$this->arrResult = [];		
		if ($userIdData) {
			$this->userId = (int)$userIdData; 
		}else{ $this->userId = false; }		
		if ($this->userId){
			$this->strQuery = "select * from `authents` where `id`=? ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($this->userId) );
		}else{
			$this->strQuery = "select * from `authents` order by `id_staff` ";
			$this->result = $this->db->query($this->strQuery);			
		}
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();		
			if($this->row){	
				foreach($this->row as $value){
					$arr = ['id'=>$value['id'],'login'=>$value['login'],'password'=>$value['password'],'status'=>$value['status'],
						'username'=>$value['username'],'idStaff'=>$value['id_staff'] ];
					$arrResult[] = userModel::fromState($arr);
				}
				return $arrResult;
			}
		}
		return false;		
	}
	
	public function getNewUser(){
		$this->arrResult = [];
		$this->strQuery = "SELECT * FROM `authents` WHERE `id_staff` is null AND `status` is null";
		$this->result = $this->db->query($this->strQuery);
		if ($this->result !== false){
			$this->row = $this->result->fetchAll();	
				if($this->row){	
				foreach($this->row as $value){
					$arr = ['id'=>$value['id'],'login'=>$value['login'],'password'=>$value['password'],'status'=>$value['status'],
						'username'=>$value['username'],'idStaff'=>$value['id_staff'] ];
					$arrResult[] = userModel::fromState($arr);
				}
				return $arrResult;
			}
		}
		return false;	
	}
	public function addRegistration($array){
		$this->strQuery = "UPDATE `authents` SET `status`= ?,`id_staff`= ? WHERE `id`= ? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($array['status'],$array['idStaff'],$array['id']) );
		
	}
	public function updateUserStatus($idData,$newStatusData){
		$this->strQuery = "UPDATE `authents` SET `status`= ? WHERE `id`= ? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($newStatusData,$idData) );
	}
	public function deleteUser($idData){
		$this->strQuery = "DELETE FROM `authents`  WHERE `id`= ? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($idData) );
	}
}