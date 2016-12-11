<?php
class cpecialitysRepository extends basisRepository{
		
//	public static function getCpecialitys($idData) {
//		$mem = new 	cpecialitysMemory;
//		return $mem->getElementById($idData);
//	}	
	
	public function getAllWorkElement(){
		$arrResult = [];
		$arr = $this->getALLElement();
		foreach($arr as $value){
			if($value->work){ $arrResult[] = $value; }
		}
		return $arrResult;
	}
	
	public function getElementById($idData){
		$this->strQuery = "SELECT s.* FROM `cpecialitys` as s  where s.`id`=?  ";
		parent::getElementById($idData);
		if($this->row){	;	
			$arr = ['id'=>$this->row[0],'title'=>$this->row['title'],'priseBasis'=>$this->row['priseBasis'],'description'=>$this->row['description'],
						'quantity'=>$this->row['quantity'],'bossId'=>$this->row['bossId'],'work'=>$this->row['work']];				
			return cpecialitysModel::fromState($arr);	
		}else{ return false; }	
	}
	
	public function getAllElement(){
		$this->arrResult = [];
		$this->strQuery = "SELECT s.* FROM `cpecialitys` as s  ";
		parent::getALLElement();
		if($this->row){
			foreach($this->row as $value){
		$arr = ['id'=>$value[0],'title'=>$value['title'],'priseBasis'=>$value['priseBasis'],'description'=>$value['description'],
						'quantity'=>$value['quantity'],'bossId'=>$value['bossId'],'work'=>$value['work']];		
				$this->arrResult[] = cpecialitysModel::fromState($arr);
				}
			return $this->arrResult;
		}else{ return false; }	
	}
	
	public function findElement($elementData){
		$this->arrResult = [];			
		$this->strQuery = "SELECT s.* FROM `cpecialitys` as s where 
			s.`title`=? and s.`priseBasis`=? and s.`quantity`=? and s.`work`=? ";
		$this->result = $this->db->prepare($this->strQuery);		
		$this->result->execute(array($elementData->title,$elementData->priseBasis,$elementData->quantity,$elementData->work ) );	
		if ($this->result !== false){
			
			$this->row = $this->result->fetchAll();
			if($this->row ){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'title'=>$value['title'],'priseBasis'=>$value['priseBasis'],'description'=>$value['description'],
						'quantity'=>$value['quantity'],'bossId'=>$value['bossId'],'work'=>$value['work']];	
					$this->arrResult[] = cpecialitysModel::fromState($arr);
				}
				return $this->arrResult;	
			}			
		}
		return false;
	}
	
	public function createElement($elementData){
		$teacher = $this->findElement($elementData);
		if (!$teacher){ 
			$this->strQuery = "insert into `cpecialitys` values(NULL,?,?,?,?,?,?) ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($elementData->title,$elementData->priseBasis,$elementData->description,$elementData->quantity,$elementData->bossId,$elementData->work ) );		
			$countRow = $this->result->rowCount();		
			if($countRow){ return $countRow; }
		}
		return false;
	}
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `cpecialitys` WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->id));
		return $this->result->rowCount();	
	}	
	
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `cpecialitys` SET `title`=?,`priseBasis`=?,`description`=?,`quantity`=?,`bossId`=?,`work`=? WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->title,$elementData->priseBasis,$elementData->description,$elementData->quantity,
			$elementData->bossId,$elementData->work,$elementData->id ) );
		return $this->result->rowCount();
	}	
	
}