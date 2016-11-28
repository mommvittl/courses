<?php
class cpecialitysValidate{
	public function __construct(){
		
	}
	
	public static function validate($cpecData){
		if( is_object($cpecData) ){
			if (($cpecData->title) && (is_string($cpecData->title))){
				if( ($cpecData->priseBasis) && (is_numeric($cpecData->priseBasis)) && ($cpecData->priseBasis >= 0 )){
					if( ($cpecData->quantity) && (is_numeric($cpecData->quantity)) && ($cpecData->quantity >= 0 )){
						return true;
					}
				}
			}
		}
		return false;
	}
	
}