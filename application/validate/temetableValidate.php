<?php
class temetableValidate{
	public function __construct(){
		
	}
	
	public static function myf_checkData($dataData){
		list($year,$month,$day) = explode("-",(string)$dataData);
		if( !is_numeric($year) || !is_numeric($month) || !is_numeric($day) ){ return false; }
		return checkdate($month,$day,$year);
	}
	
	public static function myf_checkTime($timeData){
		list($hour,$minuts,$second) = explode(":",(string)$timeData);
		if(is_null($second)){ $second = 0; }
		if(is_numeric($hour) && $hour>=0 && $hour<=59 ){
			if(is_numeric($minuts) && $minuts>=0 && $minuts<=59 ){
				if(is_numeric($second) && $second>=0 && $second<=59 ){
					return true;
				}
			}
		}
		return false;
	}
	
	public function arrLessonValidate($arrLessonData){		
		$flag = true;
		if(is_array($arrLessonData) ){
			foreach($arrLessonData as $key=>$value){
				$result = $this->lessonValidate($value);
				$arrLessonData[$key] = $result['arrResult'];
				$flag = $flag && $result['flagValidate'];
			}
		}else{$flag = false;}	
		return array('arrResult'=>$arrLessonData,'flagValidate'=>$flag);
	}	
	
	public function lessonValidate($lesson){
		$flag = false;
		if(is_array($lesson) ){
			if(isset($lesson['data']) && self:: myf_checkData($lesson['data']) ){
				if(isset($lesson['time']) && self:: myf_checkTime($lesson['time']) ){
					
					$flag = true;
				}else{ $lesson['message'] = 'Некорректное время';  }
			}else{ $lesson['message'] = 'Некорректная дата';  }
		}
		return array('arrResult'=>$lesson,'flagValidate'=>$flag);
	}
	
}