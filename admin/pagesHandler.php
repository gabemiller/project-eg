<?php

header('Content-type: application/json');
require_once('includes/functions.php');

$jsonarray = array();


switch($_POST['event']){
	
	///////////////////// Upload 
	case "upload":	
		foreach($_POST as $key => $value){
			$jsonarray[$key] = $value;
		}

		$bool = isset($_POST['id']) && !empty($_POST['id'])
		&& isset($_POST['name']) && !empty($_POST['name'])
		&& isset($_POST['pageContent']) && !empty($_POST['pageContent'])
		&& isset($_POST['hidden']) && !empty($_POST['hidden']);

		if($bool){
			
			$events = new Pages($_POST['id']
								,$_POST['name']
								,$_POST['pageContent']
								,$_POST['hidden']);
			
			$jsonarray = $events->savePage();
			$jsonarray['reloadPage'] = true;
			echo json_encode($jsonarray);
			
		}else {
			$jsonarray['message'] = 'Nem sikerült elküldeni az adatokat!';
			$jsonarray['type'] = 0;
			echo json_encode($jsonarray);
		}

		
		break;
	
	///////////////////// Remove
	case "remove":
		foreach($_POST as $key => $value){
			$jsonarray[$key] = $value;
		}
		
		$bool = isset($_POST['id']) && !empty($_POST['id']);
		
		if($bool){		
			$page = Pages::getPage($_POST['id']);
			
			
			$jsonarray = $page->deletePage();
			$jsonarray['page'] = true;
			$jsonarray['id'] = $_POST['id'];
			echo json_encode($jsonarray);
		
		} else {
			$jsonarray['message'] = 'Nem sikerült elküldeni az adatokat!';
			$jsonarray['type'] = 0;
			echo json_encode($jsonarray);
		}
				
		break;
	
	///////////////////// Watch
	case "watch":
		foreach($_POST as $key => $value){
			$jsonarray[$key] = $value;
		}
		
		$bool = isset($_POST['id']) && !empty($_POST['id']);
		
		if($bool){		
			$page = Pages::getPage($_POST['id']);
			
			$jsonarray['page'] = true;
			$jsonarray['html'] = $page->__get('content');					 
			$jsonarray['id'] = $_POST['id'];
			
			echo json_encode($jsonarray);
		
		} else {
			$jsonarray['message'] = 'Nem sikerült elküldeni az adatokat!';
			$jsonarray['type'] = 0;
			echo json_encode($jsonarray);
		}
		
		break;
		
	///////////////////// Modify
	case "modify":
		foreach($_POST as $key => $value){
			$jsonarray[$key] = $value;
		}

		$bool = isset($_POST['id']) && !empty($_POST['id'])
		&& isset($_POST['name']) && !empty($_POST['name'])
		&& isset($_POST['pageContent']) && !empty($_POST['pageContent'])
		&& isset($_POST['hidden']) && !empty($_POST['hidden']);

		if($bool){
			
			$events = new Pages($_POST['id']
								,$_POST['name']
								,$_POST['pageContent']
								,$_POST['hidden']);
			
			$jsonarray = $events->modifyPage();
			$jsonarray['reloadPage'] = true;
			echo json_encode($jsonarray);
			
		}else {
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