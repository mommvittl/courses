<?php
class teacher_ajaxTemetableController{
	protected $functionHandler; 
	protected $xmlString;
	protected $lessonRepository;	
	protected $arrResult;	
	protected $lesson;	
	protected $groupsRepository;
	protected $cpecialitysRepository;
	protected $auditoriasRepository;
	protected $teacherRepository;
	
	public function __construct(){
		header('Content-Type: text/XML');
		header(' Cache-Control: no-cache');
		$this->functionHandler = trim($_POST['functionHandler']);
		$this->lessonRepository = new lessonRepository;
	}
	
	protected function returnXmlResponse($data = ""){
		$this->xmlString = "<response><functionHandler>".$this->functionHandler."</functionHandler>";
		$this->xmlString .= $data;
		$this->xmlString .= "</response>";
		exit($this->xmlString);
	}

	public function personalTemetableAction(){
		$strResponse = "<page>1</page>";
		$this->groupsRepository = new groupsRepository;
		$this->cpecialitysRepository = new cpecialitysRepository;
		$this->auditoriasRepository = new auditoriasRepository;
		$this->teacherRepository = new teacherRepository;
		$idTemet = ( isset($_POST['idTemet']) ) ?  (int)$_POST['idTemet'] : 0 ;
		$lesson = $this->lessonRepository->getElementById($idTemet);
		$arr = [];
		if(is_object($lesson)){
			$arr = $lesson->getArrForXMLDocument();			
			$teacher = $this->teacherRepository->getElementById($lesson->idTeacherPlan);
			if( is_object($teacher)){ $arr['teacherSurname'] = $teacher->surname; }
			$strResponse .= "<nextTemetable>" . json_encode($arr) . "</nextTemetable>";
			$auditorias = $this->auditoriasRepository->getElementById($lesson->idAuditorias);
			if( is_object($auditorias)){
				$arr = $auditorias->getArrForXMLDocument();
				$strResponse .= "<nextAuditorias>" . json_encode($arr) . "</nextAuditorias>";
			}
			$group = $this->groupsRepository->getElementById($lesson->idGroup);
			if( is_object($group)){
				$arr = $group->getArrForXMLDocument();
				$strResponse .= "<nextGroup>" . json_encode($arr) . "</nextGroup>";
				$cpec = $this->cpecialitysRepository->getElementById($group->idCpecial);
				if( is_object($cpec)){
					$arr = $cpec->getArrForXMLDocument();
					$strResponse .= "<nextCpec>" . json_encode($arr) . "</nextCpec>";
				}
			}
		}
		$this->returnXmlResponse($strResponse);
	}
	
	public function arrTemetableByArrParamAction(){	
		$strResponse = "";
		$page = ( isset($_POST['page']) ) ?  (int)$_POST['page'] : 1 ;
		$arrParam = (isset($_POST['arrParam']) ) ? json_decode(trim($_POST['arrParam']),true) : false ;
		if(is_array($arrParam) ){
			$colPage = $this->lessonRepository->countLessonByArrParameter($arrParam);
			$pagerModel = new pagerModel($colPage, COLSTRINGINLIST );			
			$arrPageData = $pagerModel->getArrPage($page);			
			if($arrPageData){ 
				$page = $arrPageData['page']; 
				$strResponse .= "<pager>".json_encode($arrPageData['arrPage'])."</pager>";
				$arrParam['startLine'] = (int)( COLSTRINGINLIST * ($page-1) );
				$arrParam['limitLine'] = COLSTRINGINLIST; 
			}
			$strResponse .= "<page>" . $page . "</page>";
			$this->groupsRepository = new groupsRepository;
			$this->cpecialitysRepository = new cpecialitysRepository;
			$this->teacherRepository = new teacherRepository;
			$this->arrResult = [];
			$arr = $this->lessonRepository->searchByArrParameter($arrParam);		
			if(is_array($arr) ){
				foreach($arr as $key=>$value){					
					$group = $this->groupsRepository->getElementById($value->idGroup);
					if( is_object($group) && ($group->status == 'work') ){
						$line = $value->getArrForXMLDocument();
						$line['groupTitle'] = $group->title;
						$cpec = $this->cpecialitysRepository->getElementById($group->idCpecial);
						if( is_object($cpec)){ $line['cpecTitle'] = $cpec->title; }				
						$teacher = $this->teacherRepository->getElementById($value->idTeacherPlan);				
						if( is_object($teacher)){ $line['teacherSurname'] = $teacher->surname; }
						$this->arrResult[] = $line;
					}
				}
			}
			if(is_array($this->arrResult) ){
				foreach($this->arrResult as $value){
					$strResponse .= "<nextStaff>" . json_encode($value) . "</nextStaff>";
				}
			}
			$this->returnXmlResponse($strResponse);
		}		
	}
}	




















