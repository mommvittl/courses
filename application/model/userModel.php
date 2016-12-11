<?php
class userModel extends baseModel{
	
	protected $id = false; 
	protected $login = false; 
	protected $password = false; 
	protected $status = false; 
	protected $username = false; 
	protected $idStaff = false; 

	public function __construct($arrParameter = []){
		
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('id','login','password','status','username','idStaff') ) ){
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