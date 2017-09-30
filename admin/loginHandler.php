<?php

header('Content-type: application/json');
require_once('includes/functions.php');

$jsonarray = array();

foreach($_POST as $key => $value){
	$jsonarray[$key] = $value;
}

$jsonarray = array_merge($jsonarray, $admin->login());

echo json_encode($jsonarray);

return;


?>