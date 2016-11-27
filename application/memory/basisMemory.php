<?php
abstract class basisMemory{
	
	protected $db = null;
	protected $strQuery;
	protected $result;
	protected $row;		
	protected $arrResult;		
	
    public function __construct(){
        $this->db = PDOLib::getInstance()->getPdo();
    }
	
	public function getElementById($idData){
		if($idData){
			$this->result = $this->db->prepare($this->strQuery);
			$this->result->execute(array($idData));
			if ($this->result !== false){			
				$this->row = $this->result->fetch();		
			}
		}else{ $this->row = false; }
	}
	
	public function getALLElement(){
		$this->result = $this->db->query($this->strQuery);
		if ($this->result !== false){			
			$this->row = $this->result->fetchAll();		
		}else{ $this->row = false; }			
	}
	
	abstract public function createElement($elementData);
	abstract public function findElement($elementData);
	abstract public function updateElement($elementData);
	abstract public function deleteElement($elementData);
		
}