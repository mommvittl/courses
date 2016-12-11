<?php
abstract class baseController	
{
	protected $pathAccess;
	protected $page;
    protected $db = null;
    public function __construct(){
        $this->db = PDOLib::getInstance()->getPdo();
    }
}