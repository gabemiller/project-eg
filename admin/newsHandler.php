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
				&& isset($_POST['publicDate']) && !empty($_POST['publicDate'])
				&& isset($_POST['createDate']) && !empty($_POST['createDate'])
				&& isset($_POST['title']) && !empty($_POST['title'])
				&& isset($_POST['author']) && !empty($_POST['author'])
				&& isset($_POST['newsContent']) && !empty($_POST['newsContent'])
				&& isset($_POST['hidden']) && !empty($_POST['hidden']);
		
		if($bool){
				
			$news = new News($_POST['id']
							,$_POST['title']
							,$_POST['author']
							,$_POST['newsContent']
							,str_replace('-','.',$_POST['publicDate'])
							,str_replace('-','.',$_POST['createDate'])
							,$_POST['hidden']);
			
			$jsonarray = $news->saveArticle();
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
			$news = News::getArticle($_POST['id']);
			$jsonarray = $news->deleteArticle();
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
			$news = News::getArticle($_POST['id']);
			
			$newsTitle = $news->__get('title');
			
			if(strlen($newsTitle)>20){
				$newsTitle = substr($newsTitle,0,20)."...";
			}
			
			$newsTag = 
			'<ul class="muted unstyled inline" style="margin-bottom:0;"><li>
            	<i class="icon-time"></i>
            	<span>'.str_replace('-','.',$news->__get('publicDate')).'</span>
            </li>
            <li>
            |
            </li>
            <li>
            	<i class="icon-user"></i>
            	<span>'.$news->__get('author').'</span>
            </li></ul>';
			
			$jsonarray['html'] = '<div class="modal-header">'
								 .'<a class="close" data-dismiss="modal">&times;</a>'
								 .'<h3>'.$newsTitle.'</h3>'
								 .$newsTag
								 .'</div>'
								 .'<div class="modal-body">'
								 .$news->__get('content')
								 .'</div>'
								 .'<div class="modal-footer">'
								 .'<a class="btn btn-primary pull-left" href="newsupload.php?id='
								 .$news->__get('id').'"><i class="icon-pencil" ></i> Szerkesztés</a>'
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
				&& isset($_POST['publicDate']) && !empty($_POST['publicDate'])
				&& isset($_POST['createDate']) && !empty($_POST['createDate'])
				&& isset($_POST['title']) && !empty($_POST['title'])
				&& isset($_POST['author']) && !empty($_POST['author'])
				&& isset($_POST['newsContent']) && !empty($_POST['newsContent'])
				&& isset($_POST['hidden']) && !empty($_POST['hidden']);
		
		if($bool){
			
			$news = new News($_POST['id']
							,$_POST['title']
							,$_POST['author']
							,$_POST['newsContent']
							,str_replace('-','.',$_POST['publicDate'])
							,str_replace('-','.',$_POST['createDate'])
							,$_POST['hidden']);
			
			$jsonarray = $news->modifyArticle();
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