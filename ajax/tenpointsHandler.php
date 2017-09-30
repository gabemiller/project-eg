<?php

require_once('../includes/functions.php');

if(isset($_POST)){
	$id = $_POST['id'];
	
	$point = TenPoints::getTenPointsById($id);
	
	$jsonarray = array('id'=>$point->__get('id'),
					   'points'=>$point->__get('points'));
	
	echo json_encode($jsonarray);
	
}


?>