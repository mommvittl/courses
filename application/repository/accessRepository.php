<?php
class accessRepository extends baseRepository{
	protected $userId = false;
	protected $arrResult = [];
	
	public function __construct(){
		parent::__construct();
	}
	public function getUserById($userIdData = false){
		if ($userIdData) { $this->userId = (int)$userIdData; }		
		if ($this->userId){
			$this->strQuery .= "select * from `authents` where `id`=? ";
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
						'username'=>$value['username'],'userpassw'=>$value['userpassw'],'idStaff'=>$value['id_staff'] ];
					$arrResult[] = userModel::fromState($arr);
				}
				return $arrResult;
			}
		}
		return false;		
	}

}