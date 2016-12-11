<?php
class teachersValidate{
	public function __construct(){
		
	}
	
	public static function validate($cpecData){
		if( is_object($cpecData) ){			
			return true;
		}
		return false;
	}
	public static function addTeachersValidate($cpecData){
		if( is_object($cpecData) ){	
			$studentList = new teacherRepository;
			$student = $studentList->findElement($cpecData)[0];
			if( !is_object($student) ){
				return true;
			}			
		}
		return false;
	}
	
}