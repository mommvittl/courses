<?php
class lessonModel extends basisModel{
//columnName in MySQL : id,id_group,id_auditorias,dataPlan,dataFact,timePlan,timeFact,duration,id_teacherPlan,id_teacherFact,theme,status
	
	protected $id = null; 
	protected $idGroup = null; 
	protected $group = null; 
	protected $idAuditorias = null; 
	protected $auditorias = null; 
	protected $dataPlan = null; 
	protected $dataFact = null; 
	protected $timePlan = null; 
	protected $timeFact = null; 
	protected $duration = null; 
	protected $status = null; 
	protected $theme = null; 
	protected $idTeacherPlan = null; 
	protected $idTeacherFact = null; 

	public function __construct($arrParameter = []){	
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('id','idGroup','group','idAuditorias','auditorias','dataPlan','dataFact','timePlan','timeFact',
					'duration','status','idTeacherPlan','idTeacherFact') ) ){
					$this->$key = $value;
				}
			}
		}		
	}
	public static function fromState($state){
        return new self($state);
    }
	public function getArrForXMLDocument(){
		return array('id'=>$this->id,'idGroup'=>$this->idGroup,'idAuditorias'=>$this->idAuditorias,'dataPlan'=>$this->dataPlan,'dataFact'=>$this->dataFact,
			'timePlan'=>$this->timePlan,'timeFact'=>$this->timeFact,'duration'=>$this->duration,'status'=>$this->status,'theme'=>$this->theme,
			'idTeacherPlan'=>$this->idTeacherPlan,'idTeacherFact'=>$this->idTeacherFact);
	}
	
	public function __set($parametrName,$valueName){
		if (in_array($parametrName,array('id','name','idGroup','group','idAuditorias','auditorias','dataPlan','dataFact','timePlan','timeFact',
					'duration','status','idTeacherPlan','idTeacherFact') ) ){
			$this->$parametrName = $valueName;
		}
	}
	
}