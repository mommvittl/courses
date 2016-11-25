<?php

final class PDOLib
{
    private $pdo; // PDO instance;
    public static function getInstance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new PDOLib();
        }
        return $inst;
    }
    public function getPDO() {
        return $this->pdo;
    }
    private function __construct()
    {
        $dsn = "mysql:host=".HOST_NAME.";dbname=".DB_NAME.";charset=utf8";
		$this->pdo = new PDO($dsn, USER_NAME, PASSWORD);	
    }
    private function __clone()
    {

    }
}