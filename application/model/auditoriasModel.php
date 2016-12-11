<?php
class auditoriasModel extends baseModel{
// columnName in MySQL : id,title,adress,description,work	
	protected $id = false; 
	protected $title = false; 
	protected $adress = false; 
	protected $description = false; 
	protected $work = false; 

	public function __construct($arrParameter = []){	
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('id','title','adress','description','work') ) ){
					$this->$key = $value;
				}
			}
		}		
	}
	public static function fromState($state){
        return new self($state);
    }
	
	public function getArrForXMLDocument(){
		return array('id'=>$this->id,'title'=>$this->title,'adress'=>$this->adress,'description'=>$this->description,'work'=>$this->work);
	}
	
	public function __get($parametrName){
		if (isset($this->$parametrName) ){
			return $this->$parametrName;
		}else{ return false;}
	} 
	
}
