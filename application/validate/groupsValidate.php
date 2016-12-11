<?php
class groupsValidate{
	public function __construct(){
		
	}
	
	public static function validate($cpecData){
		if( is_object($cpecData) ){	
			if(is_string($cpecData->title) && (strlen($cpecData->title) > 1 ) ){
				if(($cpecData->price > 0) && ($cpecData->idCpecial > 0)){
					return true;
				}
			}			
		}
		return false;
	}
	
	public static function addGroupsValidate($cpecData){
		if( is_object($cpecData) ){	
			$cpecialitysList = new groupsRepository;
			$cpecialitys = $cpecialitysList->findElement($cpecData)[0];
			if( !is_object($cpecialitys) ){
				return true;
			}			
		}
		return false;
	}
}