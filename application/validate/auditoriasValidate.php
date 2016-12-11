<?php
class auditoriasValidate{
	public function __construct(){
		
	}
	
	public static function validate($cpecData){
		if( is_object($cpecData) ){	
			if(is_string($cpecData->title) && (strlen($cpecData->title) > 1 ) ){
				if(($cpecData->adress) && (strlen($cpecData->adress) > 1 ) && ($cpecData->description)){
					return true;
				}
			}			
		}
		return false;
	}
	
	public static function addAuditoriasValidate($cpecData){
		if( is_object($cpecData) ){	
			$auditoriasList = new  auditoriasRepository;
			$auditorias = $auditoriasList->findElement($cpecData)[0];
			if( !is_object($auditorias) ){
				return true;
			}			
		}
		return false;
	}
}