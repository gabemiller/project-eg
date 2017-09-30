<?php

session_start();
date_default_timezone_set("Europe/Budapest");

function __autoload($classname){
	require_once('classes/'.$classname.'.php');
}

require_once('thumbnailer/ThumbLib.inc.php');
require_once('render.php');

$admin = new LoginAdmin();
$admin->logoutAfterTime();
$_SESSION['start_time'] = time();

?>