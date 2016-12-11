<?php
class registerRepository extends basisRepository{
	
	public function createElement($elementData){
		$registerLine = $this->findElement($elementData);
		if (!$registerLine){
			$this->strQuery = "insert into `register` values(?,?,?,?,?,?,?) ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($elementData->idTemetable,$elementData->idGroup,$elementData->idStudent,$elementData->attendance,
				$elementData->assesment,$elementData->homework,$elementData->remarks ) );
			$countRow = $this->result->rowCount();
			if($countRow){ return $countRow; } 			
		}
		return false;
	}
	
	public function findElement($elementData){
		$this->arrResult = [];
		$this->strQuery = "SELECT *  FROM `register`  where `id_temetable`=? and `id_group`=? and `id_student`=?  ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->idTemetable,$elementData->idGroup,$elementData->idStudent));
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();	
			if($this->row){
				foreach($this->row as $value){
					$arr = ['idTemetable'=>$value['id_temetable'],'idGroup'=>$value['id_group'],'idStudent'=>$value['id_student'],'attendance'=>$value['attendance'],
						'assesment'=>$value['assesment'],'homework'=>$value['homework'],'remarks'=>$value['remarks'] ];						
					$this->arrResult[] = registerLineModel::fromState($arr);	
				}
				return 	$this->arrResult;	
			}			
		}
		return false; 	
	}
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `register` SET `attendance`=?,`assesment`=?,`homework`=?,`remarks`=? WHERE `id_temetable`=? AND 'id_group'=? AND 'id_student'=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->attendance,$elementData->assesment,$elementData->homework,$elementData->remarks,
			$elementData->idTemetable,$elementData->idGroup,$elementData->idStudent) );
		return $this->result->rowCount();
	}
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `register` WHERE `id_temetable`=? and `id_group`=? and `id_student`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->idTemetable,$elementData->idGroup,$elementData->idStudent));
		return $this->result->rowCount();
	}
	
	public function __call($method,$arg){
		if(preg_match('/^searchBy(.+)$/',$method,$arr) ){
			if( in_array(trim($arr[1]),array('idTemetable','idGroup','idStudent','attendance','assesment','homework','remarks') ) ){
				$this->arrResult = [];
				$this->strQuery = "SELECT s.* FROM `register` as s where `" . trim($arr[1]) ."`=? ";
				$this->result = $this->db->prepare($this->strQuery);
				$this->result->execute(array(trim($arg[0])));
				if ($this->result !== false){			
					$this->row = $this->result->fetchAll();
					if($this->row ){
						foreach($this->row as $value){
							$arr = ['idTemetable'=>$value['id_temetable'],'idGroup'=>$value['id_group'],'idStudent'=>$value['id_student'],'attendance'=>$value['attendance'],
								'assesment'=>$value['assesment'],'homework'=>$value['homework'],'remarks'=>$value['remarks'] ];						
							$this->arrResult[] = registerLineModel::fromState($arr);
						}
						return $this->arrResult;	
					}			
				}			
			}
			return false;			
		}
		
	}

}