<?php
class studentsMemory extends  basisMemory{
	private $teacher;
	
	public function getElementById($idData){
		$this->strQuery = "SELECT s.* FROM `students` as s  where s.`id`=?  ";
		parent::getElementById($idData);
		if($this->row){
			$arr = ['id'=>$this->row[0],'name'=>$this->row['name'],'surname'=>$this->row['surname'],'birthday'=>$this->row['birthday'],
						'telephon'=>$this->row['telefon'],'adress'=>$this->row['adress'],'email'=>$this->row['email'],'skype'=>$this->row['skype'],
						'characteristic'=>$this->row['characteristic'],'dogovor'=>$this->row['dogovor']];						
			return studentsModel::fromState($arr);	
		}else{ return false; }			
	}
	
	public function getALLElement(){
		$this->arrResult = [];
		$this->strQuery = "SELECT s.* FROM `students` as s  ";
		parent::getALLElement();
		if($this->row){
			foreach($this->row as $value){
				$arr = ['id'=>$value['id'],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
					'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
					'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'] ];
				$this->arrResult[] = studentsModel::fromState($arr);
				}
			return $this->arrResult;
		}else{ return false; }		
	}
	
	public function createElement($elementData){
		$this->strQuery = "insert into `students` values(NULL,?,?,?,?,?,?,?,?,?) ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->characteristic,$elementData->dogovor ) );
		return $this->result->rowCount();	
	}
	
	public function findElement($elementData){
		$this->arrResult = [];
//		var_dump($elementData);
//			echo "<p>===studet====</p>";
			
		$this->strQuery = "SELECT s.* FROM `students` as s where 
			s.`name`=? and s.`surname`=? and s.`birthday`=? and s.`telefon`=? and s.`adress`=? and s.`email`=? and s.`skype`=? and s.`dogovor`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		
		
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->dogovor ) );	
		if ($this->result !== false){
			
			$this->row = $this->result->fetchAll();
//			var_dump($this->row);
//			echo "<p>===sroww====</p>";
			if($this->row ){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
						'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
						'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'] ];
					$this->arrResult[] = studentsModel::fromState($arr);
				}
				return $this->arrResult;	
			}			
		}
		return false;
	}
	
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `students` SET `name`=?,`surname`=?,`birthday`=?,`telefon`=?,`adress`=?,`email`=?,
			`skype`=?,`characteristic`=?,`dogovor`=? WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->characteristic,$elementData->dogovor,$elementData->id ) );
		return $this->result->rowCount();		
	}
	
	public function deleteElement($elementData){		
		$this->strQuery = "DELETE FROM `students` WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->id));
		return $this->result->rowCount();	
	}
}

















