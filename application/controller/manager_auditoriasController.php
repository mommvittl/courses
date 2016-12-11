<?php
class manager_auditoriasController extends BasisController
{
	protected $auditorias;
	protected $auditoriasList;
	protected $arrAuditorias;
	
	public function __construct(){
		parent::__construct();
		$this->auditoriasList = new auditoriasRepository;
	}
	
	
    public function indexAction() { 
		$this->pathAccess = 'auditorias/index';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;; 
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("auditorias/manager_auditoriasIndex.tmpl");
		
		$this->page->displayPage($this->arrParameterForPage);		
    }
	
	public function createAction(){
		$this->pathAccess = 'auditorias/create';
		$this->arrParameterForPage = []; 
		$this->arrParameterForPage['pathAccess'] = $this->pathAccess;
		$this->arrParameterForPage['userNameRegistr'] = $this->sessionUserName;
		$this->page = new indexPage("auditorias/manager_auditoriasCreate.tmpl");	
		if(isset($_POST['addAuditoriasFormGo']) ){
			$valueData = [];
			$valueData['title'] = trim($_POST['title']); 
			$valueData['adress'] = $_POST['adress']; 
			$valueData['description'] = trim($_POST['description']); 
			$valueData['work'] = 1;
			$this->auditorias = auditoriasModel::fromState($valueData);
			$this->arrParameterForPage['informFlag'] = true;
			if ( auditoriasValidate::validate($this->auditorias)){
				if( auditoriasValidate::addAuditoriasValidate($this->auditorias) ){
					if( $this->auditoriasList->createElement($this->auditorias) !== false ){					
						$this->arrParameterForPage['stringInform'] = "Новая аудитория добавлена в базу данных";
						$valueData = [];
					}else{
						$this->arrParameterForPage['stringInform']  = "Ошибка при записи данных.";
					}
				}else{
					$this->arrParameterForPage['stringInform'] = "Такая аудитория уже есть в базе данных";
				}	
			}else{
				$this->arrParameterForPage['stringInform']  = "Некорректные данные.";
			}
			$this->arrParameterForPage['valueData'] = $valueData;
		}
		$this->page->displayPage($this->arrParameterForPage);	
	}

}