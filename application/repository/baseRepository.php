<?php
abstract class baseRepository{
	protected $db;
	protected $strQuery;
	protected $result;
	protected $row;		

	public function __construct(){
		$this->db = PDOLib::getInstance()->getPdo();
	}
	
}


