<?php
class manager_ajaxTeachersController{
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
	
	public function teachersListAction(){
		$page = ( isset($_POST['page']) ) ?  (int)$_POST['page'] : 1 ;
		$teacherRepository = new teacherRepository;
		$colPage = $teacherRepository->countTeachersInList();
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
		$arrTeachers = $teacherRepository->getAllWorkElement( COLSTRINGINLIST ,(int)( COLSTRINGINLIST * ($page-1) ));
//		var_dump($arrTeachers);
		if($arrTeachers){
			foreach($arrTeachers as $value){
				$arr = $value->getArrForXMLDocument();
				$strResponse .= "<nextStaff>".json_encode($arr)."</nextStaff>";
			}
		}				
		$this->returnXmlResponse($strResponse);
	}
	
	public function personalTeacherAction(){
		$idTeach = (int)$_POST['idTeach'];
		$strResponse = "";
		$teacherRepository = new teacherRepository;
		$cpecialitysRepository = new cpecialitysRepository;
		$groupsRepository = new groupsRepository;
		$teacher = $teacherRepository->getElementById($idTeach);
		if(is_object($teacher)){
			$arr = $teacher->getArrForXMLDocument();
			$strResponse .= "<nextStaff>".json_encode($arr)."</nextStaff>";
			$groupArray = $groupsRepository->searchBybossId($teacher->id);
//			var_dump($groupArray);
			if($groupArray){
				foreach($groupArray as $value){
					$arr = $value->getArrForXMLDocument();
					$strResponse .= "<nextGroup>".json_encode($arr)."</nextGroup>";
				}				
			}
		}		
		$this->returnXmlResponse($strResponse);
	}

}	




















