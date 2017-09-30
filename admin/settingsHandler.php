<?php

header('Content-type: application/json');
require_once('includes/functions.php');

$jsonarray = array();


switch($_POST['event']){
	
	///////////////////// Password modify 
	case "password":	
		foreach($_POST as $key => $value){
			$jsonarray[$key] = $value;
		}

		$bool = isset($_POST['old_pwd']) && !empty($_POST['old_pwd'])
		&& isset($_POST['new_pwd']) && !empty($_POST['new_pwd'])
		&& isset($_POST['new_pwd_confirm']) && !empty($_POST['new_pwd_confirm']);

		if($bool){
			
			$jsonarray = $admin->modifyPassword($_POST['old_pwd'],$_POST['new_pwd'],$_POST['new_pwd_confirm']);
			$jsonarray['removeFormInput'] = true;
			echo json_encode($jsonarray);
			
		}else {
			$jsonarray['message'] = 'Nem sikerült elküldeni az adatokat!';
			$jsonarray['type'] = 0;
			echo json_encode($jsonarray);
		}

		
		break;
	
	///////////////////// Email modify
	case "email":
	
		foreach($_POST as $key => $value){
			$jsonarray[$key] = $value;
		}
		
		$bool = isset($_POST['email']) && !empty($_POST['email']);
		
		if($bool){		
			
			$jsonarray = $admin->modifyEmail($_POST['email']);
			$jsonarray['removeFormInput'] = false;
			echo json_encode($jsonarray);
		
		} else {
			$jsonarray['message'] = 'Nem sikerült elküldeni az adatokat!';
			$jsonarray['type'] = 0;
			echo json_encode($jsonarray);
		}
				
		break;

	///////////////////// Default
	default:
		$jsonarray['message'] = 'Nem sikerült elküldeni az adatokat!';
		$jsonarray['type'] = 0;
		echo json_encode($jsonarray);
		break;
}

return;

?>