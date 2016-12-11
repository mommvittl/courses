<?php
class groupsModel extends basisModel{
	
	protected $id = null; 
	protected $idCpecial = null; 
	protected $cpecial = null; 
	protected $title = null; 
	protected $price = null; 
	protected $periodicity = null; 
	protected $quantity = null; 
	protected $duration = null; 
	protected $bossId = null; 
	protected $boss = null; 
	protected $startDataPlan = null; 
	protected $startDataFact = null; 
	protected $endDataPlan = null; 
	protected $endDataFact = null; 
	protected $status = null; 
	protected $numLesson = null; 
	protected $arrStudent = []; 
	
	public function __construct($arrParameter = []){	
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('id','idCpecial','cpecial','title','price','periodicity','quantity','duration','bossId','boss',
					'startDataPlan','startDataFact','endDataPlan','endDataFact','status','numLesson','arrStudent') ) ){
					$this->$key = $value;
				}
			}
		}
		if ($this->bossId && !$this->boss){
			$this->boss = ( new teacherRepository)->getElementById($this->bossId);
		}	
	}
	public static function fromState($state){
        return new self($state);
    }
	public function getArrForXMLDocument(){
		return array('id'=>$this->id,'idCpecial'=>$this->idCpecial,'title'=>$this->title,'price'=>$this->price,'periodicity'=>$this->periodicity,
			'quantity'=>$this->quantity,'duration'=>$this->duration,'bossId'=>$this->skype,'startDataPlan'=>$this->startDataPlan,
			'startDataFact'=>$this->startDataFact,'endDataPlan'=>$this->endDataPlan,'endDataFact'=>$this->endDataFact,'status'=>$this->status,'numLesson'=>$this->numLesson);
	}
}