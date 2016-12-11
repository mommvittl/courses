<?php
class teachModel extends baseModel{
	
	protected $id = false; 
	protected $name = false; 
	protected $surname = false; 
	protected $birthday = false; 
	protected $telephon = false; 
	protected $adress = false; 
	protected $email = false; 
	protected $skype = false; 
	protected $status = false; 
	protected $work = false; 
	protected $idAuthent = false; 
	protected $statusAuthent = false; 
	protected $nameAuthent = false; 

	public function __construct($arrParameter = []){	
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('id','name','surname','birthday','telephon','adress','email','skype','status','work','idAuthent','statusAuthent','nameAuthent') ) ){
					$this->$key = $value;
				}
			}
		}		
	}
	public static function fromState($state){
        return new self($state);
    }
	
	public function __get($parametrName){
		if (isset($this->$parametrName) ){
			return $this->$parametrName;
		}else{ return false;}
	} 
	
}