<?php

session_start();

function __autoload($classname){
	require_once('classes/'.$classname.'.php');
}

require_once('render.php');

$connection = ConnectDataBase::getInstance();

?>