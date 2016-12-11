<?php
class manager_ajaxGroupsController{
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
	
	public function newCpecAction(){
		$idCpec = (int)$_POST['cpecialitys'];
		$cpecialitysRepository = new cpecialitysRepository;
		$teacherRepository = new teacherRepository;
		$cpecialitys = $cpecialitysRepository->getElementById($idCpec);
		$arr = $cpecialitys->getArrForXMLDocument();
		$teacher = $teacherRepository->getElementById($cpecialitys->bossId);
		if($teacher !== false){  $arr["bossSurname"] = $teacher->surname; }
		$strResponse = "<nextStaff>".json_encode($arr)."</nextStaff>";
		$this->returnXmlResponse($strResponse);
	}
	
	public function arrGroupAction(){
		$idCpec = (!empty($_POST['cpecialitys'])) ? (int)$_POST['cpecialitys'] : 0 ;
		$page = (!empty($_POST['page'])) ? (int)$_POST['page'] :  1;
		$status =  (!empty($_POST['status']) && (in_array($_POST['status'],array('anons', 'work', 'archiv')))) ? $_POST['status'] : 0;
//		if (!empty($_POST['status']) && (in_array($_POST['status'],array('anons', 'work', 'archiv')) ) ){
//			$status = $_POST['status'];  
//		}else{ $status = 0; }	
		$groupsRepository = new groupsRepository;
		$cpecialitysRepository = new cpecialitysRepository;
		$groupsListRepository = new groupsListRepository;				
		$teacherRepository = new teacherRepository;
		$strResponse = "";
		if($status){
			$colPage = $groupsRepository->countGroup('status',$status);
			$pagerModel = new pagerModel($colPage, COLSTRINGINLIST );
			$arrPageData = $pagerModel->getArrPage($page);
			if($arrPageData){ $page = $arrPageData['page']; }	
			$arrGroup = $groupsRepository->searchBystatus($status,COLSTRINGINLIST ,(int)( COLSTRINGINLIST * ($page-1) ));	
		}else{
			if($idCpec){
				$cpecialitys = $cpecialitysRepository->getElementById($idCpec);
				$arr = $cpecialitys->getArrForXMLDocument();
				$teacher = $teacherRepository->getElementById($cpecialitys->bossId);
				if($teacher !== false){  $arr["bossSurname"] = $teacher->surname; }
				$strResponse .= "<cpecialitys>".json_encode($arr)."</cpecialitys>";
				$colPage = $groupsRepository->countGroup('id_special',$idCpec,'1');
				$pagerModel = new pagerModel($colPage, COLSTRINGINLIST );
				$arrPageData = $pagerModel->getArrPage($page);
				if($arrPageData){ $page = $arrPageData['page']; }	
				$arrGroup = $groupsRepository->searchByid_special($idCpec, COLSTRINGINLIST ,(int)( COLSTRINGINLIST * ($page-1) ),true);	
			}else{
				$colPage = $groupsRepository->countWorkGroupsInList();
				$pagerModel = new pagerModel($colPage, COLSTRINGINLIST );
				$arrPageData = $pagerModel->getArrPage($page);
				if($arrPageData){ $page = $arrPageData['page']; }	
				$arrGroup = $groupsRepository->getAllWorkElement( COLSTRINGINLIST ,(int)( COLSTRINGINLIST * ($page-1) ));	
			}		
		}
		$strResponse .= "<nextCpec>" . $idCpec . "</nextCpec>";
		$strResponse .= "<page>".$page."</page>";
		if($arrPageData){
			$strResponse .= "<pager>".json_encode($arrPageData['arrPage'])."</pager>";
		}
		if(!$arrGroup){
			exit("<underreporting>По заданным параметрам поиска группы не найдены.</underreporting>");
		}else{		
			foreach($arrGroup as $value){
				$arr = $value->getArrForXMLDocument();
				if(is_object($value->boss) ){ $arr['bossSurname'] = $value->boss->surname; }
				$groupList = $groupsListRepository->getElementById($value->id);
				if(is_object($groupList) ){  
					$arr['countStudent'] = count($groupList->groupList); 
				}else{ $arr['countStudent'] = 0; }
				$cpecialitys = $cpecialitysRepository->getElementById($value->idCpecial);
				if(is_object($cpecialitys) ){  $arr['cpecTitle'] = $cpecialitys->title; }
				$strResponse .= "<nextStaff>".json_encode($arr)."</nextStaff>";
			}
			$this->returnXmlResponse($strResponse);				
		}	
	}

	private function getFullPersonalData($idGroup){
		$groupsRepository = new groupsRepository;
		$groupsListRepository = new groupsListRepository;
		$cpecialitysRepository = new cpecialitysRepository;	
		$groups = $groupsRepository->getElementById($idGroup);
		if(is_object($groups)){
			$arr = $groups->getArrForXMLDocument();
			if(is_object($groups->boss) ){ $arr['bossSurname'] = $groups->boss->surname; }
			$groupList = $groupsListRepository->getElementById($groups->id);
			if(is_object($groupList) ){  $arr['countStudent'] = count($groupList->groupList); }
			$cpecialitys = $cpecialitysRepository->getElementById($groups->idCpecial);
			if(is_object($cpecialitys) ){  $arr['cpecTitle'] = $cpecialitys->title; }
			return $arr;
		}else{ return false; }
		
	}
	
	public function	personalGroupAction(){
		$idGroup = (int)$_POST['idGroup'];
		$arr = $this->getFullPersonalData($idGroup);
		if($arr){
			$strResponse = "<nextStaff>".json_encode($arr)."</nextStaff>";
			$this->returnXmlResponse($strResponse);	
		}else{
			exit("<underreporting>Sorry...Error.Группа не найдена в базе данных</underreporting>");
		}		
	}
	
	public function	fullInformationForStartAction(){
		$idGroup = (int)$_POST['idGroup'];
		$arr = $this->getFullPersonalData($idGroup);
		if($arr){
			$strResponse = "<nextStaff>".json_encode($arr)."</nextStaff>";			
		}else{
			exit("<underreporting>Sorry...Error.Группа не найдена в базе данных</underreporting>");
		}
		$teacherRepository = new teacherRepository;
		$arrTeachers = $teacherRepository->getAllElement();
		$arr = [];
		if($arrTeachers){
			foreach($arrTeachers as $value){ $arr[$value->id] = $value->surname; }
			$strResponse .= "<nextTeachers>".json_encode($arr)."</nextTeachers>";	
		}
		$auditoriasRepository = new auditoriasRepository;
		$arrAuditorias = $auditoriasRepository->getAllElement();
//		var_dump($arrAuditorias);
		$arr = [];
		if($arrAuditorias){
			foreach($arrAuditorias as $value){ $arr[$value->id] = $value->title; }
			$strResponse .= "<nextAuditorias>".json_encode($arr)."</nextAuditorias>";	
		}
		$this->returnXmlResponse($strResponse);	
	}	

	public function groupListAction(){
		$idGroup = (int)$_POST['idGroup'];
		$groupsRepository = new groupsRepository;
		$groupsListRepository = new groupsListRepository;		
		$groups = $groupsRepository->getElementById($idGroup);
		if (!$groups){
			exit("<underreporting>Sorry...Error.Группа не найдена в базе данных</underreporting>");
		}else{
			$arr = $groups->getArrForXMLDocument();
			if(is_object($groups->boss) ){ $arr['bossSurname'] = $groups->boss->surname; }
			$strResponse = "<nextCpec>".json_encode($arr)."</nextCpec>";			
			$groupList = $groupsListRepository->getElementById($groups->id);
			if(is_object($groupList) ){
				foreach($groupList->groupList as $value){
					$arr = $value['students']->getArrForXMLDocument();
					$arr['receiptData'] = $value['receiptData'];
					$arr['expulsionData'] = $value['expulsionData'];
					$arr['status'] = $value['status'];
					if($arr['status'] == 'student'){
						$strResponse .= "<nextStaff>".json_encode($arr)."</nextStaff>";
					}					
				}
			}	
			$this->returnXmlResponse($strResponse);
		}		
	}	
	
	public function deleteStudentAction(){
		$idStud = (int)$_POST['idStud'];
		$idGroup = (int)$_POST['idGroup'];		
		$groupsListRepository = new groupsListRepository;
		$groupsListRepository->updateStatusAndExpDataStudent(array('expulsionData'=>date("Y-m-d"),'idGroup'=>$idGroup,'idStudent'=>$idStud,'newStatus'=>'expulsion') );
		$studentsRepository = new studentsRepository;
		$student = $studentsRepository->getElementById($idStud);
		$student->work = 0;
		$studentsRepository->updateElement($student);
		$this->groupListAction();
	}
	
	public function updateStudentAction(){
		$idStud = (int)$_POST['idStud'];
		$idGroup = (int)$_POST['idGroup'];
		$studentsRepository = new studentsRepository;	
		$groupsListRepository = new groupsListRepository;
		$student = $studentsRepository->getElementById($idStud);	
		if(!is_object($student) ){ exit("<underreporting>Error...Ошибка данных id students</underreporting>"); }
		$student->idGroups = $idGroup;
		$groupsListRepository->deleteStudentInGroup($student);
		$this->groupListAction();
	}
	
	public function studentListAction(){
		$page = (int)$_POST['page'];
		$studentsRepository = new studentsRepository;
		$colPage = $studentsRepository->countStudentForAddToGroup();
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
		$arrStudentr = $studentsRepository->getStudentForAddToGroup( COLSTRINGINLIST ,(int)( COLSTRINGINLIST * ($page-1) ));
		if(empty($arrStudentr) ){ exit("<underreporting>Нет новых студентов для зачисления</underreporting>"); }
		foreach($arrStudentr as $value){
			if( is_object($value) ){
				$strResponse .= "<nextStaff>".json_encode($value->getArrForXMLDocument())."</nextStaff>";
			}			
		}
		$this->returnXmlResponse($strResponse);	
	}
	
	public function	addStudentInGroupFormAction(){
		$idGroup = (int)$_POST['idGroup'];
		$groupsRepository = new groupsRepository;
		$studentsRepository = new studentsRepository;
		$groupsListRepository = new groupsListRepository;
		$group = $groupsRepository->getElementById($idGroup);
		$arr = [];
		if(!is_object($group) ){ exit("<underreporting>Error...Ошибка данных id группы</underreporting>"); }
		foreach($_POST as $key=>$value){
			if(is_numeric($key) ){
				$student = $studentsRepository->getElementById($value);
				if(is_object($student) && $student->work == '1' ){
					$arr[] = ['idStudent'=>$student->id,'students'=>$student,'receiptData'=>date("Y-m-d"),
							'expulsionData'=>NULL,'status'=>'student' ];	
				}
			}
		}
		$groupsList = groupsListModel::fromState(array('idGroup'=>$group->id,'groupList'=>$arr) );
		if(!is_object($groupsList)){exit("<underreporting>Error...Ошибка записи данных groupList </underreporting>");}
		$col = $groupsListRepository->createElement($groupsList);
		$this->groupListAction();
	}
	
	public function endingGroupAction(){
		$groupsRepository = new groupsRepository;
		$studentsRepository = new studentsRepository;
		$groupsListRepository = new groupsListRepository;
		$groups = $groupsRepository->getElementById( (int)$_POST['idGroup'] );
		if (!is_object($groups)){ exit("<underreporting>Sorry...Error.Группа не найдена в базе данных</underreporting>"); }	
		if(isset($_POST['variant']) && in_array($_POST['variant'],array('student','graduate'))){
			$variant = $_POST['variant'];
		}else{ $variant = 'graduate'; }
		$groups->status = 'archiv';
		$groups->endDataFact = date("Y-m-d");
		$groupsRepository->updateElement($groups);
		$groupsList = $groupsListRepository->getElementById($groups->id);
		if (is_object($groupsList)){
			if($variant == 'student'){
				$groupsListRepository->deleteElement($groupsList);
			}else{				
				foreach($groupsList->groupList as $value){					
					$student = $value['students'];
					$arr = ['newStatus'=>'graduate','expulsionData'=>date("Y-m-d"),'idGroup'=>$groupsList->idGroup,'idStudent'=>$value['idStudent'] ];
					$groupsListRepository->updateStatusAndExpDataStudent($arr);
					if (is_object($student)){
						$student->work = '0';
						$studentsRepository->updateElement($student);
					}				
				}
			}
			
		}
		exit("<underreporting>Группа закрыта.Данные записаны в БД.</underreporting>");
	}
	
}	




















