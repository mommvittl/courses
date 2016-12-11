<?php
class cpecialitysValidate{
	public function __construct(){
		
	}
	
	public static function validate($cpecData){
		if( is_object($cpecData) ){	
			if(is_string($cpecData->title) && (strlen($cpecData->title) > 1 ) ){
				if(($cpecData->priseBasis) && ($cpecData->description) && ($cpecData->quantity >= 0)){
					return true;
				}
			}			
		}
		return false;
	}
	
	public static function addCpecialitysValidate($cpecData){
		if( is_object($cpecData) ){	
			$cpecialitysList = new cpecialitysRepository;
			$cpecialitys = $cpecialitysList->findElement($cpecData)[0];
			if( !is_object($cpecialitys) ){
				return true;
			}			
		}
		return false;
	}
}