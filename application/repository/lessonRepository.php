<?php
class lessonRepository extends basisRepository{
	
	public function getElementById($idData){
		$this->strQuery = "SELECT *  FROM `temetable`  where `id`=?  ";
		parent::getElementById($idData);
		if($this->row){
			$arr = ['id'=>$this->row[0],'idGroup'=>$this->row['id_group'],'idAuditorias'=>$this->row['id_auditorias'],'dataPlan'=>$this->row['dataPlan'],
						'dataFact'=>$this->row['dataFact'],'timePlan'=>$this->row['timePlan'],'timeFact'=>$this->row['timeFact'],'duration'=>$this->row['duration'],
						'status'=>$this->row['status'],'idTeacherPlan'=>$this->row['id_teacherPlan'],'idTeacherFact'=>$this->row['id_teacherFact'] ];						
			return lessonModel::fromState($arr);	
		}else{ return false; }		
	}
	
	public function getALLElementByIdCroup($idGroup){
		
		$this->strQuery = "SELECT *  FROM `temetable`  where `id_group`=?  ";	
		$this->arrResult = [];
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($idGroup));
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();	
			if($this->row){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'idGroup'=>$value['id_group'],'idAuditorias'=>$value['id_auditorias'],'dataPlan'=>$value['dataPlan'],
						'dataFact'=>$value['dataFact'],'timePlan'=>$value['timePlan'],'timeFact'=>$value['timeFact'],'duration'=>$value['duration'],
						'status'=>$value['status'],'idTeacherPlan'=>$value['id_teacherPlan'],'idTeacherFact'=>$value['id_teacherFact'] ];						
					$this->arrResult[] = lessonModel::fromState($arr);	
				}
				return 	$this->arrResult;	
			}			
		}		
		return false; 
	}
	
	public function createArrayElement($arrElementData){
		if(is_array($arrElementData) ){
			$this->strQuery = "insert into `temetable` values(NULL,?,?,?,?,?,?,?,?,?,?,?) ";
			$this->result = $this->db->prepare($this->strQuery);
			foreach($arrElementData as $value){
				$lesson = $this->findElement($value);
				var_dump($value);
				if(!$lesson){
					$this->result->execute(array($value->idGroup,$value->idAuditorias,$value->dataPlan,$value->dataFact,
						$value->timePlan,$value->timeFact,$value->duration,$value->idTeacherPlan,
						$value->idTeacherFact,$value->theme,$value->status) );
				}
			}
			$countRow = $this->result->rowCount();	
			if($countRow){ return $countRow; }
		}
		return false;		
	}
	
	public function createElement($elementData){
		$lesson = $this->findElement($elementData);
		if(!$lesson){
			$this->strQuery = "insert into `temetable` values(NULL,?,?,?,?,?,?,?,?,?,?,?) ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($elementData->idGroup,$elementData->idAuditorias,$elementData->dataPlan,$elementData->dataFact,$elementData->timePlan,
				$elementData->timeFact,$elementData->duration,$elementData->idTeacherPlan,$elementData->idTeacherFact,$elementData->theme,$elementData->status) );
			$countRow = $this->result->rowCount();	
			if($countRow){ return $countRow; }
		}
		return false;
	}
	
	public function findElement($elementData){
		$this->arrResult = [];
		$this->strQuery = "SELECT *  FROM `temetable`  where `id_group`=? and `id_auditorias`=? and `dataPlan`=? and `timePlan`=? and `duration`=? and `id_teacherPlan`=? ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($elementData->idGroup,$elementData->idAuditorias,$elementData->dataPlan,$elementData->timePlan,
				$elementData->duration,$elementData->idTeacherPlan));
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();	
			if($this->row){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'idGroup'=>$value['id_group'],'idAuditorias'=>$value['id_auditorias'],'dataPlan'=>$value['dataPlan'],
						'dataFact'=>$value['dataFact'],'timePlan'=>$value['timePlan'],'timeFact'=>$value['timeFact'],'duration'=>$value['duration'],
						'status'=>$value['status'],'idTeacherPlan'=>$value['id_teacherPlan'],'idTeacherFact'=>$value['id_teacherFact'] ];						
					$this->arrResult[] = lessonModel::fromState($arr);	
				}
				return 	$this->arrResult;	
			}			
		}
		return false; 		
	}
	
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `temetable` SET `id_group`=?,`id_auditorias`=?,`dataPlan`=?,`dataFact`=?,`timePlan`=?,`timeFact`=?,`duration`=?,
		`id_teacherPlan`=?,`id_teacherFact`=?,`theme`=?,`status`=? WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->idGroup,$elementData->idAuditorias,$elementData->dataPlan,$elementData->dataFact,$elementData->timePlan,
				$elementData->timeFact,$elementData->duration,$elementData->idTeacherPlan,$elementData->idTeacherFact,
				$elementData->theme,$elementData->status,$elementData->id ) );
		return $this->result->rowCount();	
	}
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `temetable` WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->id));
		return $this->result->rowCount();	
	}
	
	public function deleteElementByIdGroup($idGroup){
		$this->strQuery = "DELETE FROM `temetable` WHERE `id_group`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($idGroup));
		return $this->result->rowCount();	
	}
	
	public function __call($method,$arg){
		if(preg_match('/^searchBy(.+)$/',$method,$arr) ){
			if( in_array(trim($arr[1]),array('id','id_group','id_auditorias','dataPlan','dataFact','timePlan','timeFact','duration','id_teacherPlan','id_teacherFact','theme','status') ) ){
				$this->arrResult = [];
				$this->strQuery = "SELECT s.* FROM `temetable` as s where `" . trim($arr[1]) ."`=? ";			
				$this->result = $this->db->prepare($this->strQuery);
				$this->result->execute(array(trim($arg[0])));
				if ($this->result !== false){			
					$this->row = $this->result->fetchAll();
					if($this->row ){
						foreach($this->row as $value){
							$arr = ['id'=>$value[0],'idGroup'=>$value['id_group'],'idAuditorias'=>$value['id_auditorias'],'dataPlan'=>$value['dataPlan'],
								'dataFact'=>$value['dataFact'],'timePlan'=>$value['timePlan'],'timeFact'=>$value['timeFact'],'duration'=>$value['duration'],
								'status'=>$value['status'],'idTeacherPlan'=>$value['id_teacherPlan'],'idTeacherFact'=>$value['id_teacherFact'] ];						
							$this->arrResult[] = lessonModel::fromState($arr);	
						}
					return $this->arrResult;	
					}			
				}			
			}
			return false;			
		}else{ return false; }	
	}
	
	public function countLessonByArrParameter($arrParamData){
		
		$arrValue = [];
		$this->arrResult = [];
		$workGroupFlag = (isset($arrParamData['workGroupFlag']) && in_array($arrParamData['workGroupFlag'],array('anons', 'work', 'archiv'))) ? $arrParamData['workGroupFlag'] : false ;
		
		if($workGroupFlag){
			$this->strQuery = "SELECT count(*) FROM `temetable` as s left join `groups` as g on ( s.`id_group`=g.`id`) where 1 and g.`status` = '" . $workGroupFlag . "' ";
		}else{ $this->strQuery = "SELECT count(*) FROM `temetable` as s where 1 "; }	
		
		if(is_array($arrParamData) ){
			foreach($arrParamData as $key=>$value){
				if(in_array(trim($key),array('id','id_group','id_auditorias','dataPlan','dataFact','timePlan','timeFact','duration','id_teacherPlan','id_teacherFact','theme','status') ) ){
					$this->strQuery .= " AND  s.`" . trim($key) . "`=? ";
					$arrValue[] = trim($value);
				}
			}
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute($arrValue);
			if ($this->result !== false){
				list($col) = $this->result->fetch();
				return $col;
			}
		}
		return false;	
	}
	
	public function searchByArrParameter($arrParamData){
		$arrValue = [];
		$this->arrResult = [];
		$workGroupFlag = (isset($arrParamData['workGroupFlag']) && in_array($arrParamData['workGroupFlag'],array('anons', 'work', 'archiv'))) ? $arrParamData['workGroupFlag'] : false ;
		if($workGroupFlag){
			$this->strQuery = "SELECT s.* FROM `temetable` as s left join `groups` as g on ( s.`id_group`=g.`id`) where 1 and g.`status` = '" . $workGroupFlag . "' ";
		}else{ $this->strQuery = "SELECT s.* FROM `temetable` as s where 1 "; }		
		if(is_array($arrParamData) ){
			foreach($arrParamData as $key=>$value){
				if(in_array(trim($key),array('id','id_group','id_auditorias','dataPlan','dataFact','timePlan','timeFact','duration','id_teacherPlan','id_teacherFact','theme','status') ) ){
					$this->strQuery .= " AND  s.`" . trim($key) . "`=? ";
					$arrValue[] = trim($value);
				}
			}
			$col = (isset($arrParamData['limitLine']) ) ? abs((int)$arrParamData['limitLine']) : 0 ;
			$start = (isset($arrParamData['startLine']) ) ? abs((int)$arrParamData['startLine']) : 0 ;
			if($col){ $this->strQuery .= "  LIMIT $start , $col"; }
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute($arrValue);
			if ($this->result !== false){			
				$this->row = $this->result->fetchAll();
				if($this->row ){
					foreach($this->row as $value){
						$arr = ['id'=>$value[0],'idGroup'=>$value['id_group'],'idAuditorias'=>$value['id_auditorias'],'dataPlan'=>$value['dataPlan'],
							'dataFact'=>$value['dataFact'],'timePlan'=>$value['timePlan'],'timeFact'=>$value['timeFact'],'duration'=>$value['duration'],
							'status'=>$value['status'],'idTeacherPlan'=>$value['id_teacherPlan'],'idTeacherFact'=>$value['id_teacherFact'] ];						
						$this->arrResult[] = lessonModel::fromState($arr);	
					}
				return $this->arrResult;	
				}			
			}	
		}
		return false;
	}
	
}










