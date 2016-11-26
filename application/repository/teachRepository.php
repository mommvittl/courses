<?php
class teachRepository extends baseRepository{
	protected $teachId = false;
	protected $arrResult = [];
	
	public function __construct(){
		parent::__construct();
	}
	public function getTeachById($teachIdData = false){
		$this->arrResult = [];
		if ($teachIdData) {
			$this->teachId = (int)$teachIdData; 
		}else{ $this->userId = false; }		
		if ($this->teachId){
			$this->strQuery = "SELECT t.*,a.`id`,a.`status`,a.`username` FROM `teacher` as t left join `authents` as a on (t.`id`=a.`id_staff`)  where t.`id`=? AND t.`work`='1' ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($this->teachId) );
		}else{
			$this->strQuery = "SELECT t.*,a.`id`,a.`status`,a.`username` FROM `teacher` as t left join `authents` as a on (t.`id`=a.`id_staff`)  where t.`work`='1' ";
			$this->result = $this->db->query($this->strQuery);			
		}
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();		
			if($this->row){	
				foreach($this->row as $value){
					$arr = ['id'=>$value['id'],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
						'telephon'=>$value['telephon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
						'status'=>$value['status'],'work'=>$value['work'],'idAuthent'=>$value[10],'statusAuthent'=>$value[11],'nameAuthent'=>$value[12] ];
					$this->arrResult[] = teachModel::fromState($arr);
				}
				return $this->arrResult;
			}
		}
		return false;		
	}
	public function getTeachLessAccess(){
		$this->arrResult = [];
		$this->strQuery = "SELECT t.*,a.`id`,a.`status`,a.`username` FROM `teacher` as t left join `authents` as a on (t.`id`=a.`id_staff`) 
			where t.`work`='1' AND  a.`status` is null ";
		$this->result = $this->db->query($this->strQuery);
			if ($this->result !== false){			
			$this->row = $this->result->fetchAll();		
			if($this->row){	
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
						'telephon'=>$value['telephon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
						'status'=>$value['status'],'work'=>$value['work'],'idAuthent'=>$value[10],'statusAuthent'=>$value[11],'nameAuthent'=>$value[12] ];
					$this->arrResult[] = teachModel::fromState($arr);
				}
				return $this->arrResult;
			}
		}
		return false;			
	}


}