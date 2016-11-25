<?php
function __autoload($class) {
	preg_match_all("/\w+([A-Z]\w+)$/",$class,$arrResult);
	$result = $arrResult[1][0];
	$pathToClass = APP . strtolower($result) . DIRECTORY_SEPARATOR . $class.'.php';
	if (file_exists($pathToClass)){
		require_once $pathToClass;
	}
}
define("HOST_NAME", 'localhost');
define("DB_NAME", 'courses');
define("USER_NAME", 'tchr205iyw');
define("PASSWORD", 'liraw57tqz');