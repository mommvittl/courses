<?php
abstract class baseMemory{
	protected $db = null;
	protected $strQuery;
	protected $result;
	protected $row;		
	
    public function __construct(){
        $this->db = PDOLib::getInstance()->getPdo();
    }
	
	public function getElementById($idData){
		if($idData){
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($idData));
			if ($this->result !== false){			
				$this->row = $this->result->fetch();		
				if($this->row){
					return $this->row;
				}
			}
		}
		return false;
	}
	
	public function getALLElement(){
		$this->result = $this->db->query($this->strQuery);
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();		
			if($this->row){
				return $this->row;
			}
		}			
		return false;
	}
	
	abstract public function createElement($elementData);
	abstract public function findElementById($elementData);
	abstract public function updateElement($elementData);
	abstract public function deleteElement($elementData);
	
}