<?php
abstract class basisModel{
	
    public function __construct(){       
    }
	public function __get($parametrName){
		if (isset($this->$parametrName) ){
			return $this->$parametrName;
		}else{ return false;}
	} 
	public function __set($parametrName,$valueName){
		if (isset($this->$parametrName) ){
			$this->$parametrName = $valueName;
		}
	}
	public function __isset($parametrName){
		return (isset($this->$parametrName))?true:false;
	} 
	
}
