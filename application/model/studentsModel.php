<?php
class studentsModel extends basisModel{
	
	protected $id = null; 
	protected $name = null; 
	protected $surname = null; 
	protected $birthday = null; 
	protected $telephon = null; 
	protected $adress = null; 
	protected $email = null; 
	protected $skype = null; 
	protected $kharacteristica = null; 
	protected $dogovor = null; 

	public function __construct($arrParameter = []){	
		if(!empty($arrParameter) && (is_array($arrParameter)) ){
			foreach($arrParameter as $key=>$value){
				if( in_array($key,array('id','name','surname','birthday','telephon','adress','email','skype','kharacteristica','dogovor') ) ){
					$this->$key = $value;
				}
			}
		}		
	}
	public static function fromState($state){
        return new self($state);
    }
	public function getArrForXMLDocument(){
		return array('id'=>$this->id,'name'=>$this->name,'surname'=>$this->surname,'birthday'=>$this->birthday,'telephon'=>$this->telephon,
			'adress'=>$this->adress,'email'=>$this->email,'skype'=>$this->skype,'kharacteristica'=>$this->kharacteristica,'dogovor'=>$this->dogovor);
	}
}