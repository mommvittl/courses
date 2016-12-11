<?php
class groupsListRepository extends basisRepository{
			
	public function createElement($elementData){
		$arr = $elementData->groupList;
		$this->strQuery = "insert into `groups_list` values(?,?,?,?,?)";
		$this->result = $this->db->prepare($this->strQuery);
		foreach($arr as $value){
			$this->result->execute(array($elementData->idGroup,$value['idStudent'],$value['receiptData'],$value['expulsionData'],$value['status']));
		}		
		return $this->result->rowCount();
	}
	
	public function findElement($elementData){}	
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `groups_list` WHERE `id_group`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->idGroup));
		return $this->result->rowCount();
	}	
	
	public function deleteStudentInGroup($elementData){
		$this->strQuery = "DELETE FROM `groups_list` WHERE `id_group`=? and `id_student`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->idGroups,$elementData->id));
		return $this->result->rowCount();
	}	
	
	public function getElementById($idData){
		$this->arrResult = [];
		if($idData){
			$this->strQuery = "SELECT gl.*,s.* FROM `groups_list` as gl, `students` as s WHERE `id_group`=? and gl.`id_student`=s.`id`";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($idData));
			if ($this->result !== false){		
				$this->row = $this->result->fetchAll();
				if($this->row ){
					foreach($this->row as $value){
						$arr = ['id'=>$value['id'],'name'=>$value['name'],'surname'=>$value['surname'],'birthday'=>$value['birthday'],
						'telephon'=>$value['telefon'],'adress'=>$value['adress'],'email'=>$value['email'],'skype'=>$value['skype'],
						'characteristic'=>$value['characteristic'],'dogovor'=>$value['dogovor'],'work'=>$value['work']];
						$students = studentsModel::fromState($arr);
						$arr = ['idStudent'=>$value['id_student'],'students'=>$students,'receiptData'=>$value['receipt_data'],
							'expulsionData'=>$value['expulsion_data'],'status'=>$value['status'] ];	
						 $this->arrResult[] = $arr;
					}
					return groupsListModel::fromState( array('idGroup'=>$idData,'groupList'=>$this->arrResult) );					
				}
			}		
		}
		return false;
	}
	
	public function updateStatusAndExpDataStudent($arrParametr){ 	
		$this->strQuery = "UPDATE `groups_list` SET `status`=?,`expulsion_data`=? WHERE `id_group`=? and `id_student`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($arrParametr['newStatus'],$arrParametr['expulsionData'],$arrParametr['idGroup'],$arrParametr['idStudent'] ));
		return $this->result->rowCount();
	}
	
	public function updateElement($elementData){
		
	}
}

















