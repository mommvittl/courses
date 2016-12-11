<?php
class groupsListModel extends basisModel{
	
	protected $idGroup = null; 
	protected $group = null; 
	protected $groupList = []; 
	
	public function __construct($arrParameter = []){	
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('idGroup','groupList') ) ){
					$this->$key = $value;
				}
			}
			if($this->idGroup){
				$this->group = ( new groupsRepository)->getElementById($this->idGroup);
			}
		}		
	}
	public static function fromState($state){
        return new self($state);
    }
	public function getArrForXMLDocument(){
		return true;
	}
	
}