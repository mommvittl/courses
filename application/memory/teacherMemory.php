<?php
class teacherMemory extends  basisMemory{
	private $teacher;
	
	public function getElementById($idData){
		$this->strQuery = "SELECT t.*,a.`id`,a.`status`,a.`username` FROM `teacher` as t left join `authents` as a on (t.`id`=a.`id_staff`)  where t.`id`=? AND t.`work`='1' ";
		parent::getElementById($idData);
		if($this->row){
			$arr = ['id'=>$this->row[0],'name'=>$this->row['name'],'surname'=>$this->row['surname'],'birthday'=>$this->row['birthday'],
						'telephon'=>$this->row['telefon'],'adress'=>$this->row['adress'],'email'=>$this->row['email'],'skype'=>$this->row['skype'],
						'status'=>$this->row['status'],'work'=>$this->row['work'],'idAuthent'=>$this->row[10],
						'statusAuthent'=>$this->row[11],'nameAuthent'=>$this->row[12]];						
			return teacherModel::fromState($arr);	
		}else{ return false; }			
	}
	
	public function getALLElement(){
		$this->arrResult = [];
		$this->strQuery = "SELECT t.*,a.`id`,a.`status`,a.`username` FROM `teacher` as t left join `authents` as a on (t.`id`=a.`id_staff`)  where t.`work`='1' ";
		parent::getALLElement();
		if($this->row){
			foreach($this->row as $value){
				$arr = ['id'=>$value['id'],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
					'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
					'status'=>$value['status'],'work'=>$value['work'],'idAuthent'=>$value[10],'statusAuthent'=>$value[11],'nameAuthent'=>$value[12] ];
				$this->arrResult[] = teacherModel::fromState($arr);
				}
			return $this->arrResult;
		}else{ return false; }		
	}
	
	public function createElement($elementData){
		$this->strQuery = "insert into `teacher` values(NULL,?,?,?,?,?,?,?,?,?) ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->status,$elementData->work ) );
		return $this->result->rowCount();	
	}
	
	public function findElement($elementData){
		$this->arrResult = [];
		$this->strQuery = "SELECT t.*,a.`id`,a.`status`,a.`username` FROM `teacher` as t left join `authents` as a on (t.`id`=a.`id_staff`)  where 
			t.`name`=? and t.`surname`=? and t.`birthday`=? and t.`telefon`=? and t.`adress`=? and t.`email`=? and t.`skype`=? and t.`status`=? and t.`work`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->status,$elementData->work ) );	
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();
			if($this->row ){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
						'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
						'status'=>$value[8],'work'=>$value['work'],'idAuthent'=>$value[10],'statusAuthent'=>$value[11],'nameAuthent'=>$value[12] ];
					$this->arrResult[] = teacherModel::fromState($arr);
				}
				return $this->arrResult;	
			}			
		}
		return false;
	}
	
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `teacher` SET `name`=?,`surname`=?,`birthday`=?,`telefon`=?,`adress`=?,`email`=?,
			`skype`=?,`status`=?,`work`=? WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->status,$elementData->work,$elementData->id ) );
		return $this->result->rowCount();		
	}
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `teacher` WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->id));
		return $this->result->rowCount();	
	}
}

















