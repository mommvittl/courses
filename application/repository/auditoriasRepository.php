<?php
class auditoriasRepository extends basisRepository{

	public function getElementById($idData){		
		$this->strQuery = "SELECT * FROM `auditorias`  where `id`=?  ";
		parent::getElementById($idData);
		if($this->row){			
			$arr = ['id'=>$this->row[0],'title'=>$this->row['title'],'adress'=>$this->row['adress'],'description'=>$this->row['description'],
				'work'=>$this->row['work'] ];
			return auditoriasModel::fromState($arr);	
		}else{ return false; }
	}
	
	public function getAllElement(){
		$this->arrResult = [];
		$this->strQuery = "SELECT * FROM `auditorias` ";
		parent::getALLElement();
		if($this->row){
			foreach($this->row as $value){
			$arr = ['id'=>$value[0],'title'=>$value['title'],'adress'=>$value['adress'],'description'=>$value['description'],
				'work'=>$value['work'] ];	
				$this->arrResult[] = auditoriasModel::fromState($arr);
				}
			return $this->arrResult;
		}else{ return false; }		
	}
	
	public function getAllWorkElement(){
		$arrResult = [];
		$arr = $this->getALLElement();
		foreach($arr as $value){
			if($value->work){ $arrResult[] = $value; }
		}
		return $arrResult;
	}
	
	public function createElement($elementData){
		$teacher = $this->findElement($elementData);
		if (!$teacher){
			$this->strQuery = "insert into `auditorias` values(NULL,?,?,?,?) ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($elementData->title,$elementData->adress,$elementData->description,$elementData->work ) );
			$countRow = $this->result->rowCount();
			if ($countRow){ return $countRow; }	
		}
		return false;
	}
	public static function getAuditorias($idData) {
		$mem = new 	auditoriasMemory;
		return $mem->getElementById($idData);
	}	
	
	public function findElement($elementData){
		$this->arrResult = [];
		$this->strQuery = "SELECT * FROM `auditorias` where `title`=? and `adress`=?
			 and `description`=? and `work`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->title,$elementData->adress,$elementData->description,$elementData->work ) );	
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();
			if($this->row ){
				foreach($this->row as $value){
				$arr = ['id'=>$value[0],'title'=>$value['title'],'adress'=>$value['adress'],'description'=>$value['description'],
					'work'=>$value['work'] ];
				$this->arrResult[] = auditoriasModel::fromState($arr);
				}
				return $this->arrResult;	
			}			
		}
		return false;
	}
	
	
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `auditorias` WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->id));
		return $this->result->rowCount();	
	}	
	
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `auditorias` SET `title`=?, `adress`=?,
			`description`=?, `work`=?  WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->title,$elementData->adress,$elementData->description,$elementData->work,$elementData->id ) );
		return $this->result->rowCount();		
	}	
	
}