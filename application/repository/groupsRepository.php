<?php
class groupsRepository extends basisRepository{
	
	protected function sendQueryForAllElement(){
		$this->arrResult = [];
		$this->result = $this->db->query($this->strQuery);
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();	
			if($this->row){
				foreach($this->row as $value){
					$arr = ['id'=>$value[0],'idCpecial'=>$value['id_special'],'title'=>$value['title'],'price'=>$value['price'],'periodicity'=>$value['periodicity'],
						'quantity'=>$value['quantity'],'duration'=>$value['duration'],'bossId'=>$value['bossId'],
						'startDataPlan'=>$value['start_data_plan'],'startDataFact'=>$value['start_data_fact'],'endDataPlan'=>$value['end_data_plan'],
						'endDataFact'=>$value['end_data_fact'],'status'=>$value['status'],'numLesson'=>$value['numLesson'] ];	
					$this->arrResult[] = groupsModel::fromState($arr);
				}
				return $this->arrResult;
			}						
		} 
		return false; 	
	}
	
	protected function sendQueryForCountElement(){
		$this->result = $this->db->query($this->strQuery);
		if ($this->result !== false){
			list($col) = $this->result->fetch();
			return $col;
		}
		return false;
	}

	public function countGroup($paramData,$valueData,$workFlag = 0){
		$arrColName = ['id_special','title','price','periodicity','quantity','duration','bossId','start_data_plan','start_data_fact','end_data_plan','end_data_fact','status','numLesson'];
		if(in_array($paramData,$arrColName) ){
			$this->strQuery = "SELECT count(*) FROM `groups` where `" . $paramData . "` = ?  ";
			if($workFlag){ $this->strQuery .= " AND `status` != 'archiv' "; }
			$this->result = $this->db->prepare($this->strQuery);		
			$this->result->execute(array($valueData));
			if ($this->result !== false){
				list($col) = $this->result->fetch();
				return $col;
			}
		}
		return false;	
	}
	
	public function countGroupsInList(){
		$this->strQuery = "SELECT count(*) FROM `groups` ";
		return $this->sendQueryForCountElement();
	}	
	
	public function countWorkGroupsInList(){
		$this->strQuery = "SELECT count(*) FROM `groups` where `status` != 'archiv'  ";
		return $this->sendQueryForCountElement();
	}

	public function getAllElement($colData = 0, $startDaata = 0){
		$start = abs((int)$startDaata);		
		$col = abs((int)$colData);		
		if($col){
			$this->strQuery = "SELECT * FROM `groups`   LIMIT $start , $col";
		}else{
			$this->strQuery = "SELECT * FROM `groups`   ";
		}	
		return $this->sendQueryForAllElement();	
	}
	
	public function getAllWorkElement($colData = 0, $startDaata = 0){
		$start = abs((int)$startDaata);		
		$col = abs((int)$colData);		
		if($col){
			$this->strQuery = "SELECT * FROM `groups` where `status` != 'archiv' LIMIT $start , $col";
		}else{
			$this->strQuery = "SELECT * FROM `groups`  where `status` != 'archiv' ";
		}	
		return $this->sendQueryForAllElement();		
	}
	
	public function getElementById($idData){
		$this->strQuery = "SELECT g.* FROM `groups` as g  where g.`id`=?  ";
		parent::getElementById($idData);
		if($this->row){
			$arr = ['id'=>$this->row[0],'idCpecial'=>$this->row['id_special'],'title'=>$this->row['title'],'price'=>$this->row['price'],'periodicity'=>$this->row['periodicity'],
						'quantity'=>$this->row['quantity'],'duration'=>$this->row['duration'],'bossId'=>$this->row['bossId'],
						'startDataPlan'=>$this->row['start_data_plan'],'startDataFact'=>$this->row['start_data_fact'],'endDataPlan'=>$this->row['end_data_plan'],
						'endDataFact'=>$this->row['end_data_fact'],'status'=>$this->row['status'],'numLesson'=>$this->row['numLesson'] ];						
			return groupsModel::fromState($arr);	
		}else{ return false; }	
	}
	
	public function findElement($elementData){
		$this->arrResult = [];			
		$this->strQuery = "SELECT g.* FROM `groups` as g where 
			g.`id_special`=? and g.`title`=? and g.`price`=? and g.`periodicity`=? and g.`quantity`=? and g.`duration`=? and g.`bossId`=? and g.`status`=? ";
		$this->result = $this->db->prepare($this->strQuery);		
		$this->result->execute(array($elementData->idCpecial,$elementData->title,$elementData->price,$elementData->periodicity,$elementData->quantity,
			$elementData->duration,$elementData->bossId,$elementData->status ) );	
		if ($this->result !== false){		
			$this->row = $this->result->fetchAll();
			if($this->row ){
				foreach($this->row as $value){
		$arr = ['id'=>$value[0],'idCpecial'=>$value['id_special'],'title'=>$value['title'],'price'=>$value['price'],'periodicity'=>$value['periodicity'],
						'quantity'=>$value['quantity'],'duration'=>$value['duration'],'bossId'=>$value['bossId'],
						'startDataPlan'=>$value['start_data_plan'],'startDataFact'=>$value['start_data_fact'],'endDataPlan'=>$value['end_data_plan'],
						'endDataFact'=>$value['end_data_fact'],'status'=>$value['status'],'numLesson'=>$value['numLesson'] ];	
					$this->arrResult[] = groupsModel::fromState($arr);
				}
				return $this->arrResult;	
			}			
		}
		return false;
	}
	
	public function createElement($elementData){
		$teacher = $this->findElement($elementData);
		if (!$teacher){ 
			$this->strQuery = "insert into `groups` values(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($elementData->idCpecial,$elementData->title,$elementData->price,$elementData->periodicity,$elementData->quantity,
				$elementData->duration,$elementData->bossId,$elementData->startDataPlan,$elementData->startDataFact,
				$elementData->endDataPlan,$elementData->endDataFact,$elementData->status,$elementData->numLesson ) );		
			$countRow = $this->result->rowCount();
			if($countRow){ return  $countRow;}
		}
		return false;
	}
	
	public function deleteElement($elementData){
		$this->strQuery = "DELETE FROM `groups` WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->id));
		return $this->result->rowCount();	
	}	
	
	public function updateElement($elementData){
		$this->strQuery = "UPDATE `groups` SET `id_special`=?,`title`=?,`price`=?,`periodicity`=?,`quantity`=?,`duration`=?,
			`bossId`=?,`start_data_plan`=?,`start_data_fact`=?,`end_data_plan`=?,`end_data_fact`=?,`status`=?,`numLesson`=? WHERE `id`=? ";
		$this->result = $this->db->prepare($this->strQuery);
		$this->result->execute(array($elementData->idCpecial,$elementData->title,$elementData->price,$elementData->periodicity,$elementData->quantity,
			$elementData->duration,$elementData->bossId,$elementData->startDataPlan,$elementData->startDataFact,
			$elementData->endDataPlan,$elementData->endDataFact,$elementData->status,$elementData->numLesson ,$elementData->id ) );
		return $this->result->rowCount();	
	}	
	
	public function __call($method,$arg){	
		if(preg_match('/^searchBy(.+)$/',$method,$arr) ){
			if( in_array(trim($arr[1]),array('id','id_special','cpecial','title','price','periodicity','quantity','duration','bossId','boss',
					'startDataPlan','startDataFact','endDataPlan','endDataFact','status','numLesson','arrStudent') ) ){
				$this->arrResult = [];
				$col = (isset($arg[1]) ) ? abs((int)$arg[1]) : 0 ;
				$start = (isset($arg[2]) ) ? abs((int)$arg[2]) : 0 ;
				$workFlag = (isset($arg[3]) ) ? true : false ;					
				if($col){
					$this->strQuery = "SELECT * FROM `groups` where `" . trim($arr[1]) ."`=?   LIMIT $start , $col";
					if($workFlag){ $this->strQuery = "SELECT * FROM `groups` where `" . trim($arr[1]) ."`=?  AND `status` != 'archiv'   LIMIT $start , $col";}
				}else{
					$this->strQuery = "SELECT * FROM `groups` where `" . trim($arr[1]) ."`=?  ";
				}				
				$this->result = $this->db->prepare($this->strQuery);
				$this->result->execute(array(trim($arg[0])));
				if ($this->result !== false){			
					$this->row = $this->result->fetchAll();
					if($this->row ){
						foreach($this->row as $value){
						$arr = ['id'=>$value[0],'idCpecial'=>$value['id_special'],'title'=>$value['title'],'price'=>$value['price'],'periodicity'=>$value['periodicity'],
						'quantity'=>$value['quantity'],'duration'=>$value['duration'],'bossId'=>$value['bossId'],
						'startDataPlan'=>$value['start_data_plan'],'startDataFact'=>$value['start_data_fact'],'endDataPlan'=>$value['end_data_plan'],
						'endDataFact'=>$value['end_data_fact'],'status'=>$value['status'],'numLesson'=>$value['numLesson'] ];	
							$this->arrResult[] = groupsModel::fromState($arr);
						}
					return $this->arrResult;	
					}			
				}			
			}
			return false;			
		}		
	}	
}