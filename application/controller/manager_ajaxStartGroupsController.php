<?php
class manager_ajaxStartGroupsController{
	protected $functionHandler; 
	protected $xmlString; 
	protected $arrLesson;
	protected $idAuditorias;
	protected $auditorias = null;
	protected $teacher = null;
	protected $idTeacher;
	protected $groups = null;
	protected $groupsRepository;
	protected $auditoriasRepository;
	protected $teacherRepository;
	protected $lessonRepository;
	protected $startData;
	protected $arrHeppy = ['31-12','01-01','08-03'];
	
	public function __construct(){
		header('Content-Type: text/XML');
		header(' Cache-Control: no-cache');
		$this->functionHandler = trim($_POST['functionHandler']);
		$this->groupsRepository = new groupsRepository;
		$this->auditoriasRepository = new auditoriasRepository;
		$this->teacherRepository = new teacherRepository;
		date_default_timezone_set('Europe/Kiev');
		
	}
	protected function returnXmlResponse($data){
		$this->xmlString = "<response><functionHandler>".$this->functionHandler."</functionHandler>";
		$this->xmlString .= $data;
		$this->xmlString .= "</response>";
		exit($this->xmlString);
	}
	protected function myf_checkData($dataData){
		list($year,$month,$day) = explode("-",(string)$dataData);
		if( !is_numeric($year) || !is_numeric($month) || !is_numeric($day) ){ return false; }
		return checkdate($month,$day,$year);
	}
	
	public function autoStartAction(){
		$strResponse = "";
		$this->groups = $this->groupsRepository->getElementById( (int)$_POST['idGroup'] );
		if (!is_object($this->groups)){ exit("<underreporting>Sorry...Error.Группа не найдена в базе данных</underreporting>"); }
		if ($this->groups->status != 'anons'){ exit("<underreporting>Эта группа уже занимается.</underreporting>"); }
		if(!empty($_POST['startData']) && temetableValidate::myf_checkData($_POST['startData']) ){ 
				$this->startData = new DateTime( trim($_POST['startData']) );		
		}else{ $this->startData = new DateTime(); }
		if(isset($_POST['periodicity']) && in_array( trim($_POST['periodicity']),array("day","week","month") ) ){  
			$periodicity = trim($_POST['periodicity']);
		}else{ $periodicity = 'day'; }
		if(isset($_POST['auditorias'])){ 
			$this->auditorias = $this->auditoriasRepository->getElementById((int)$_POST['auditorias']);
			if(is_object($this->auditorias) ){
				 $this->idAuditorias = $this->auditorias->id;
			}else{ $this->idAuditorias = null; }
		}else{ $this->auditorias = null; }
		if(isset($_POST['teacher'])){  
			$this->teacher = $this->teacherRepository->getElementById((int)$_POST['teacher']);	
			if(is_object($this->teacher) ){
				 $this->idTeacher = $this->teacher->id;
			}else{ $this->idTeacher = null; }
		}else{ $this->teacher = null; }
		$arrDay = [];
		foreach($_POST as $key=>$value){
			if( is_numeric($key) ){ $arrDay[$key] = $value;  }
		}
		ksort($arrDay);	
		$this->arrLesson = [];
		$funct = "funct_" . $periodicity;
		$this->$funct($arrDay);
		if(is_array($this->arrLesson)){
			foreach($this->arrLesson as $value){
			$strResponse .= "<nextStaff>".json_encode($value)."</nextStaff>";	
			}
		}		
		$this->returnXmlResponse($strResponse);
	}
	
	protected function funct_day($arrData){
		reset($arrData);
		$col = count($arrData);
		for($i = 0; $i < $this->groups->numLesson; $i++){
			$elem = each($arrData);
			if($elem === false){ reset($arrData); $elem = each($arrData); }	
			if( !in_array( $this->startData->format('d-m'),$this->arrHeppy )){
				$this->arrLesson[] = [ 'data' => $this->startData->format('Y-m-d'),'time'=> $elem['value'],'auditorias'=>$this->idAuditorias,'teacher'=>$this->idTeacher ];			
			}else{
				$this->startData->modify("next day");
				$i--;
			}
			if(!(($i+1) % $col)){ $this->startData->modify("next day");  }
		}
//		var_dump($this->arrLesson);	
		return true;
	}
	
	protected function funct_week($arrData){
		$arrNameDay = ['1'=>'mon','2'=>'tue','3'=>'wed','4'=>'thu','5'=>'fri','6'=>'sat','7'=>'sun'  ];
		$numStartDay = $this->startData->format('N');
		$array = array_keys($arrData);		
		sort($array);		
		if( ($numStartDay > $array[0]) && ($numStartDay <= $array[ count($array) - 1 ])){		
			reset($arrData);
			while( ($numStartDay > key($arrData)) && (key($arrData)) ){
				next($arrData); 
			}	
		}	
		for($i = 0; $i < $this->groups->numLesson; $i++){
			$elem = each($arrData);
			if($elem === false){ reset($arrData); $elem = each($arrData); }	
			$this->startData->modify($arrNameDay[ $elem['key'] ]);
			if( !in_array( $this->startData->format('d-m'),$this->arrHeppy )){
				$this->arrLesson[] = [ 'data' => $this->startData->format('Y-m-d'),'time'=> $elem['value'],'auditorias'=>$this->idAuditorias,'teacher'=>$this->idTeacher ];			
			}else{ $i--;}
		}
//		var_dump($this->arrLesson);
		return true;	
	}
	
	protected function funct_month($arrData){		
		$numStartDay = $this->startData->format('j');
		$array = array_keys($arrData);	
		sort($array);
		if( ($numStartDay > $array[0]) && ($numStartDay <= $array[ count($array) - 1 ])){		
			reset($arrData);
			while( ($numStartDay > key($arrData)) && (key($arrData)) ){
				next($arrData); 
			}	
		}
		for($i = 0; $i < $this->groups->numLesson; $i++){
			$elem = each($arrData);
			if($elem === false){ reset($arrData); $elem = each($arrData); }	
			$dayElem =  $this->startData->format('j');
			if($elem['key'] < $dayElem){
				$this->startData->modify("1 ".$this->startData->format('M'));
				$this->startData->modify("next month"); 
			}
			if( checkdate( $this->startData->format('m'),$elem['key'],$this->startData->format('Y')) ){
				$this->startData->modify($elem['key']." ".$this->startData->format('M'));
			}else{ $this->startData->modify($this->startData->format('t')." ".$this->startData->format('M')); }
			if( !in_array( $this->startData->format('d-m'),$this->arrHeppy )){
				$this->arrLesson[] = [ 'data' => $this->startData->format('Y-m-d'),'time'=> $elem['value'],'auditorias'=>$this->idAuditorias,'teacher'=>$this->idTeacher ];			
			}else{ $i--;}
		}
//		var_dump($this->arrLesson);	
		return true;
	}
	
	protected function funct_hand($arrData){
		return true;
	}
	
	public function addTemetableAction(){
		$strResponse = "";
		$this->arrLesson = [];
		$this->groups = $this->groupsRepository->getElementById( (int)$_POST['idGroup'] );
		if (!is_object($this->groups)){ exit("<underreporting>Sorry...Error.Группа не найдена в базе данных</underreporting>"); }
		if ($this->groups->status != 'anons'){ exit("<underreporting>Эта группа уже занимается.</underreporting>"); }
		$validator = new temetableValidate;
		for($i = 0; $i < $this->groups->numLesson; $i++ ){
			$this->arrLesson[] = [ 'data' =>$_POST['data_' . $i] ,'time'=>$_POST['time_' . $i] ,'auditorias'=>$_POST['auditorias_' . $i],'teacher'=>$_POST['teacher_' . $i] ];				
		}
		$result = $validator->arrLessonValidate($this->arrLesson);
		$this->arrLesson = $result['arrResult'];
		if($result['flagValidate']){
			$this->groups->startDataFact = $this->arrLesson[0]['data'];
			$this->groups->status = 'work';
			$this->groupsRepository->updateElement($this->groups);
			$this->lessonRepository = new lessonRepository;
			$array = [];
			foreach($this->arrLesson as $value){
				$arr = ['idGroup'=>$this->groups->id,'idAuditorias'=>$value['auditorias'],'dataPlan'=>$value['data'],'timePlan'=>$value['time'],
					'duration'=>$this->groups->duration,'status'=>'plan','idTeacherPlan'=>$value['teacher'] ];
				$array = lessonModel::fromState($arr);	
				$this->lessonRepository->createElement($array);
			}
			
//			var_dump($array);
			exit("<underreporting>Расписание сохранено в базе данных</underreporting>");
		}else{ 
			if(is_array($this->arrLesson)){
				foreach($this->arrLesson as $value){ 
					$strResponse .= "<nextStaff>".json_encode($value)."</nextStaff>";						
				}
			}		
		$this->returnXmlResponse($strResponse);
		}
		var_dump($this->arrLesson);
	}
}	




















