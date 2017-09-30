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
	 	&& isset($_POST['startDate']) && !empty($_POST['startDate'])
		&& isset($_POST['endDate']) && !empty($_POST['endDate'])
		&& isset($_POST['eventContent']) && !empty($_POST['eventContent'])
		&& isset($_POST['hidden']) && !empty($_POST['hidden']);

	if($bool){
	
	$events = new Events($_POST['id']
						,$_POST['name']
						,$_POST['eventContent']
						,str_replace('-','.',$_POST['startDate'])
						,str_replace('-','.',$_POST['endDate'])
						,$_POST['hidden']);
						
			$jsonarray = $events->saveEvent();
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
			$events = Events::getEvent($_POST['id']);
			$jsonarray = $events->deleteEvent();
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
			$events = Events::getEvent($_POST['id']);
			
			$eventsName = $events->__get('name');
			
			if(strlen($eventsName)>20){
				$eventsName = substr($eventsName,0,20)."...";
			}
			
			$eventsTag = 
			'<ul class="muted unstyled inline" style="margin-bottom:0;"><li>
            	<i class="icon-time"></i>
            	<span>'.str_replace('-','.',$events->__get('startDate')).'</span>
            </li>
            <li>
            |
            </li>
            <li>
				<i class="icon-time"></i>
            	<span>'.str_replace('-','.',$events->__get('endDate')).'</span>
            </li></ul>';
			
			$jsonarray['html'] = '<div class="modal-header">'
								 .'<a class="close" data-dismiss="modal">&times;</a>'
								 .'<h3>'.$eventsName.'</h3>'
								 .$eventsTag
								 .'</div>'
								 .'<div class="modal-body">'
								 
								 .$events->__get('content')
								 .'</div>'
								 .'<div class="modal-footer">'
								 .'<a class="btn btn-primary pull-left" href="eventsupload.php?id='
								 .$events->__get('id').'"><i class="icon-pencil"></i> Szerkesztés</a>'
								 .'<a class="btn" data-dismiss="modal">Bezárás</a>'
								 .'</div>';
								 
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
	 	&& isset($_POST['startDate']) && !empty($_POST['startDate'])
		&& isset($_POST['endDate']) && !empty($_POST['endDate'])
		&& isset($_POST['eventContent']) && !empty($_POST['eventContent'])
		&& isset($_POST['hidden']) && !empty($_POST['hidden']);

	if($bool){
	
	$events = new Events($_POST['id']
						,$_POST['name']
						,$_POST['eventContent']
						,str_replace('-','.',$_POST['startDate'])
						,str_replace('-','.',$_POST['endDate'])
						,$_POST['hidden']);
						
			$jsonarray = $events->modifyEvent();
			$jsonarray['reloadPage'] = false;
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