<?php
class manager_ajaxTemetableController{
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
		$this->lessonRepository = new lessonRepository;
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
	
	public function temetebleByIdGroupAction(){
		$strResponse = "";
		$this->arrLesson = [];
		$plan = 0;
		$fact = 0;
		$this->groups = $this->groupsRepository->getElementById( (int)$_POST['idGroup'] );
		if (!is_object($this->groups)){ exit("<underreporting>Sorry...Error.Группа не найдена в базе данных</underreporting>"); }	
		$this->arrLesson = $this->lessonRepository->getALLElementByIdCroup($this->groups->id);
		if(is_array($this->arrLesson) ){
			foreach($this->arrLesson as $value){
				if($value->status == 'plan'){ $plan++; }
				if($value->status == 'fact'){ $fact++; }
				$arr = $value->getArrForXMLDocument();
				$strResponse .= "<nextLesson>".json_encode($arr)."</nextLesson>";
			}
			$arr = ['allLessonPlan'=>$plan,'allLessonFact'=>$fact];
			$strResponse .= "<totalLesson>".json_encode($arr)."</totalLesson>";
		}
		$this->returnXmlResponse($strResponse);	
	}
}	




















