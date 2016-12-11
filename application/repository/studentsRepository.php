<?php
class studentsRepository extends basisRepository{
			
	public function getElementById($idData){
//		$this->strQuery = "SELECT s.* FROM `students` as s  where s.`id`=?  ";
		$this->strQuery  = "SELECT s.*,gl.`id_group` FROM `students` as s left join `groups_list` as gl on (s.`id`=gl.`id_student`)  where s.`id`=?  "; 
		parent::getElementById($idData);
		if($this->row){
			$arr = ['id'=>$this->row[0],'name'=>$this->row['name'],'surname'=>$this->row['surname'],'birthday'=>$this->row['birthday'],
						'telephon'=>$this->row['telefon'],'adress'=>$this->row['adress'],'email'=>$this->row['email'],'skype'=>$this->row['skype'],
						'characteristic'=>$this->row['characteristic'],'dogovor'=>$this->row['dogovor'],'work'=>$this->row['work'],'idGroups'=>$this->row['id_group']];						
			return studentsModel::fromState($arr);	
		}else{ return false; }	
	}
	
	public function getAllElement($colData = 0, $startDaata = 0){
		$start = abs((int)$startDaata);		
		$col = abs((int)$colData);		
		$this->arrResult = [];
		if($col){
			$this->strQuery = "SELECT s.* FROM `students` as s  LIMIT $start , $col";
		}else{
			$this->strQuery = "SELECT s.* FROM `students` as s  ";
		}		
		parent::getALLElement();
		if($this->row){
			foreach($this->row as $value){
				$arr = ['id'=>$value['id'],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
					'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
					'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'],'work'=>$value['work'] ];
				$this->arrResult[] = studentsModel::fromState($arr);
				}
			return $this->arrResult;
		}else{ return false; }		

	}
	
	public function getAllWorkElement($colData = 0, $startDaata = 0){
		$start = abs((int)$startDaata);		
		$col = abs((int)$colData);		
		$this->arrResult = [];
		if($col){
			$this->strQuery = "SELECT s.* FROM `students` as s where s.`work`=1 LIMIT $start , $col";
		}else{
			$this->strQuery = "SELECT s.* FROM `students` as s where s.`work`=1 ";
		}		
		parent::getALLElement();
		if($this->row){
			foreach($this->row as $value){
				$arr = ['id'=>$value['id'],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
					'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
					'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'],'work'=>$value['work'] ];
				$this->arrResult[] = studentsModel::fromState($arr);
				}
			return $this->arrResult;
		}else{ return false; }		

	}
	
	public function countStudentsInList(){
		$this->strQuery = "SELECT count(*) FROM `students` where `work`='1'  ";
		$this->result = $this->db->query($this->strQuery);
		if ($this->result !== false){
			list($col) = $this->result->fetch();
			return $col;
		}
		return false;
	}
	
	public function createElement($elementData){
		$teacher = $this->findElement($elementData);
		if (!$teacher){
			$this->strQuery = "insert into `students` values(NULL,?,?,?,?,?,?,?,?,?,?) ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->characteristic,$elementData->dogovor,$elementData->work ) );
			$countRow = $this->result->rowCount();
			if($countRow){ return $countRow; } 			
		}
		return false;
	}
	
	public function findElement($elementData){
		$this->arrResult = [];			
		$this->strQuery = "SELECT s.* FROM `students` as s where 
			s.`name`=? and s.`surname`=? and s.`birthday`=? and s.`telefon`=? and s.`adress`=? and s.`email`=? and s.`skype`=? and s.`dogovor`=?  and s.`work`=? ";
		$this->result = $this->db->prepare($this->strQuery);		
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->dogovor,$elementData->work ) );	
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();
			if($this->row ){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
						'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
						'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'],'work'=>$value['work'] ];
					$this->arrResult[] = studentsModel::fromState($arr);
				}
				return $this->arrResult;	
			}			
		}
		return false;
	}
	
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `students` SET `name`=?,`surname`=?,`birthday`=?,`telefon`=?,`adress`=?,`email`=?,
			`skype`=?,`characteristic`=?,`dogovor`=?,`work`=? WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->name,$elementData->surname,$elementData->birthday,$elementData->telephon,$elementData->adress,
			$elementData->email,$elementData->skype,$elementData->characteristic,$elementData->dogovor,$elementData->work,$elementData->id ) );
		return $this->result->rowCount();
	}	
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `students` WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->id));
		return $this->result->rowCount();	
	}	
	
	public function __call($method,$arg){
		if(preg_match('/^searchBy(.+)$/',$method,$arr) ){
			if( in_array(trim($arr[1]),array('id','name','surname','birthday','telephon','adress','email','skype','kharacteristica','dogovor','work','idGroups') ) ){
				$this->arrResult = [];
				$this->strQuery = "SELECT s.* FROM `students` as s where `" . trim($arr[1]) ."`=? ";
				$this->result = $this->db->prepare($this->strQuery);
				$this->result->execute(array(trim($arg[0])));
				if ($this->result !== false){			
					$this->row = $this->result->fetchAll();
					if($this->row ){
						foreach($this->row as $value){
							$arr = ['id'=>$value[0],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
								'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
								'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'],'work'=>$value['work'] ];
							$this->arrResult[] = studentsModel::fromState($arr);
						}
					return $this->arrResult;	
					}			
				}			
			}
			return false;			
		}
		
	}
	
	public function countStudentForAddToGroup(){
		$this->strQuery = "SELECT count(*) FROM `students` as s left join  `groups_list` as gl on (s.`id`=gl.`id_student`) WHERE s.`work`='1' and (gl.`id_group` is null) ";
		$this->result = $this->db->query($this->strQuery);
			if ($this->result !== false){
				list($col) = $this->result->fetch();
				return $col;
			}
		return false;	
	}
	
	public function getStudentForAddToGroup($colData = 0, $startDaata = 0){
		$start = abs((int)$startDaata);		
		$col = abs((int)$colData);	
		$this->arrResult = [];
		if($col){
			$this->strQuery = "SELECT s.* FROM `students` as s left join  `groups_list` as gl on (s.`id`=gl.`id_student`) WHERE s.`work`='1' and (gl.`id_group` is null) LIMIT $start , $col";
		}else{
			$this->strQuery = "SELECT s.* FROM `students` as s left join  `groups_list` as gl on (s.`id`=gl.`id_student`) WHERE s.`work`='1' and (gl.`id_group` is null) ";
		}
		
//		$this->strQuery = "SELECT DISTINCT s.* FROM `students` as s left join  `groups_list` as gl on (s.`id`=gl.`id_student`) WHERE s.`work`='1' and (gl.`id_group` is null
//			OR ( gl.`id_group` is not null AND gl.`status`='vacation' 
//			and ((select count(*) FROM `groups_list` as gl WHERE gl.`id_student`=s.`id` AND gl.`status`='student' ) is null) ) )";
		$this->result = $this->db->query($this->strQuery);
		if ($this->result !== false){
			$this->row = $this->result->fetchAll();
			if($this->row ){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
						'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
						'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'],'work'=>$value['work'] ];
					$this->arrResult[] = studentsModel::fromState($arr);
				}
				return $this->arrResult;	
			}
		}
		return false;
	}
}
