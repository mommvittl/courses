<?php
class teacher_ajaxStudentsController{
	protected $functionHandler; 
	protected $xmlString; 
	
	public function __construct(){
		header('Content-Type: text/XML');
		header(' Cache-Control: no-cache');
		$this->functionHandler = trim($_POST['functionHandler']);		
	}
	
	protected function returnXmlResponse($data){
		$this->xmlString = "<response><functionHandler>".$this->functionHandler."</functionHandler>";
		$this->xmlString .= $data;
		$this->xmlString .= "</response>";
		exit($this->xmlString);
	}
	
	public function studentListAction(){
		$page = ( isset($_POST['page']) ) ?  (int)$_POST['page'] : 1 ;
		$studentsRepository = new studentsRepository;
		$colPage = $studentsRepository->countStudentsInList();
		$pagerModel = new pagerModel($colPage, COLSTRINGINLIST );
		$arrPageData = $pagerModel->getArrPage($page);		
		if($arrPageData){
			$strResponse = "<pager>".json_encode($arrPageData['arrPage'])."</pager>";
			$page = $arrPageData['page'];
		}else{ 
			$page = 1;
			$strResponse = "";
		}
		$strResponse .= "<page>".$page."</page>";
		$arrStudents = $studentsRepository->getAllWorkElement( COLSTRINGINLIST ,(int)( COLSTRINGINLIST * ($page-1) ));
		if($arrStudents){
			foreach($arrStudents as $value){
				$arr = $value->getArrForXMLDocument();
				$strResponse .= "<nextStaff>".json_encode($arr)."</nextStaff>";
			}
		}				
		$this->returnXmlResponse($strResponse);
	}
	
	public function personalStudentAction(){
		$idStud = (int)$_POST['idStud'];
		$strResponse = "";
		$studentsRepository = new studentsRepository;
		$cpecialitysRepository = new cpecialitysRepository;
		$groupsRepository = new groupsRepository;
		$student = $studentsRepository->getElementById($idStud);
		if(is_object($student)){
			$arr = $student->getArrForXMLDocument();
			$strResponse .= "<nextStaff>".json_encode($arr)."</nextStaff>";
			$groups = $groupsRepository->getElementById($student->idGroups);
			if(is_object($groups)){				
				$arr = $groups->getArrForXMLDocument();
				$cpecialitys = $cpecialitysRepository->getElementById($groups->idCpecial);
				$arr['groupBoss'] = $groups->boss->surname;
				if(is_object($cpecialitys)){
					$arr['cpecTitle'] = $cpecialitys->title;
					$arr['cpecBoss'] = $cpecialitys->boss->surname;
				}
				
				$strResponse .= "<nextCpec>".json_encode($arr)."</nextCpec>";
			}
		}		
		$this->returnXmlResponse($strResponse);
	}

}	




















