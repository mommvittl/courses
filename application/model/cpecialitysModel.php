<?php
class cpecialitysModel extends basisModel{
	protected $id = null; 
	protected $title = null; 
	protected $priseBasis = null; 
	protected $description = null; 
	protected $quantity = null; 
	protected $bossId = null; 
	protected $work = null; 
	protected $boss = null; 
	
	public function __construct($arrParameter = []){	
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('id','title','priseBasis','description','quantity','bossId','work','boss') ) ){
					$this->$key = $value;
				}
			}
		}
		if ($this->bossId && !$this->boss){
			$this->boss = teacherRepository::getTeacher($this->bossId);
		}
	}
	public static function fromState($state){
        return new self($state);
    }
	public function getArrForXMLDocument(){
		return array('id'=>$this->id,'title'=>$this->title,'priseBasis'=>$this->priseBasis,'description'=>$this->description,
		'quantity'=>$this->quantity,'bossId'=>$this->bossId,'work'=>$this->work);
	}
}