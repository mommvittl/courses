<?php
abstract class basisController	
{
	protected $pathAccess;
	protected $page;
	protected $sessionUserName = 'user';
	protected $arrParameterForPage = [];
	
    public function __construct(){
		if( isset($_SESSION['username']) ){ $this->sessionUserName =  $_SESSION['username']; }
    }
}