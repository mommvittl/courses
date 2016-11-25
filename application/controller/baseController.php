<?php
abstract class baseController
{
    protected $db = null;
    public function __construct(){
        $this->db = PDOLib::getInstance()->getPdo();
    }
}