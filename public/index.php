<?php
session_start();
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);

require_once APP . 'config/config.php';

require_once APP . 'core/application.php';

$app = new Application();
$app->run();
