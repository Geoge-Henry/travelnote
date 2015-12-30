<?php
define("SP_PATH",dirname(__FILE__)."/SpeedPHP");
define("APP_PATH",dirname(__FILE__));
$spConfig = array(
	"db" => array(
		'host' => 'SAE_MYSQL_HOST_M',
		'port'=>  'SAE_MYSQL_PORT',
		'login' => 'SAE_MYSQL_USER',
		'password' => 'SAE_MYSQL_PASS',
		'database' => 'SAE_MYSQL_DB',
	),
);
require(SP_PATH."/SpeedPHP.php");
spRun(); // SpeedPHP 3л┬╠плн