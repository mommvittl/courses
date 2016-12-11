<?php
class teacherRepository extends basisRepository{
	
	public function __construct(){
		$this->memory = new teacherMemory;
	}
	
	public function getTeachLessAccess(){
		
	}
	
	public function getAllElement(){
		return $this->memory->getALLElement();
	}
	public function getAllWorkElement(){
		$arrResult = [];
		$arr = $this->memory->getALLElement();
		foreach($arr as $value){
			if($value->work){ $arrResult[] = $value; }
		}
		return $arrResult;
	}
	public function getElementById($idData){
		return $this->memory->getElementById($idData);
	}
	public static function getTeacher($idData) {
		$mem = new 	teacherMemory;
		return $mem->getElementById($idData);
	}	
	
	public function findElement($elementData){
		return $this->memory->findElement($elementData);
	}
	
	public function createElement($elementData){
		$teacher = $this->memory->findElement($elementData);
		if (!$teacher){ 
			$countRow = $this->memory->createElement($elementData);		
			if($countRow){
				return $this->memory->findElement($elementData)[0];
			}
		}
		return false;
	}
	
	public function deleteElement($elementData){
		return $this->memory->deleteElement($elementData);
	}	
	
	public function updateElement($elementData){
		return $this->memory->updateElement($elementData);
	}	
	
}