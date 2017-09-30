<?php

class TemplatePages extends TemplateProcessor{

	public function __construct($queryString){
		global $connection;
		$array = array();
		
		$query = $connection->database->query($queryString);
		
		if($query->columnCount() > 0){
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result as $value){
				$array[] = new Pages($value);
			}	
		}	
		parent::__construct($array);
	}
	
	public function createTemplate($templateString){
		$templateArray = array();
		
		$search = array('{{shortTitle}}'
						,'{{title}}'
						,'{{shortContent}}'
						,'{{content}}'
						,'{{link}}');
						
		if(!empty($this->array)){	
			
			foreach($this->array as $obj){
				$replace = array($obj->__get('title')
								,$obj->__get('title')
								,$obj->__get('content')
								,$obj->__get('content')
								,$obj->getLink());
			
				$templateArray[] = str_replace($search,$replace,$templateString);
			}
		} else {
			$templateArray[] = self::emptyTemplate('');
		}
		
		return $templateArray;
	}
	
	/**
	 *
	 *
	 */
	 
	 public static function createMenu(){
		 global $connection;
		 $menu = '';
		 
		 $result = $connection->database->prepare('SELECT * FROM pages 
		 										   WHERE parent_id = 0 
												   AND hidden = 1
												   ORDER BY name = \'Főoldal\' DESC, name = \'Kapcsolat\' ASC, name ASC');
		 $result->execute();
		 
		 foreach($result->fetchAll(PDO::FETCH_ASSOC) as $key => $value ){
			 $hasChildren = TemplatePages::hasChildren($value['id']);
			 $pages = new Pages($value);
			 $menu .= '<li class="';
			 if($hasChildren){
			  	$menu.='dropdown ';
			 } 
			 
			 $menu .= TemplatePages::isActiveMenu(TemplatePages::pageURLCheck($pages->getLink()));
			 $menu .= TemplatePages::isDisabledMenu($pages->__get('active'));
			 $menu .= '">';
			 $menu .= '<a ';
			 
			 if($hasChildren){
			  	$menu.='class="dropdown-toggle"
       					data-toggle="dropdown"
       					href="#">';
			 }else {
			 	$menu .='href="'.$pages->getLink().'" >';
			 }
			 
			 $menu .= $pages->__get('name');
			 
			 if($hasChildren){
			  	$menu.=' <i class="icon-caret-down"></i>';
			 }
			 $menu .= '</a>';
			
			 if($hasChildren){
			 	
				$menu .= TemplatePages::getChildren($value['id']);
			 
			 }				 
			 
			 $menu .= '</li>';
			 
		 }
		 
		 return $menu;
	 }
	 
	 /**
	  *
	  *
	  */
	 
	 private static function hasChildren($id){
		 	global $connection;
			
			$result = $connection->database->prepare('SELECT * FROM pages 
													  WHERE parent_id = :parent_id 
													  AND hidden = 1');
		 	
			$result->execute(array(':parent_id'=>$id));
			
			if($result->rowCount() > 0){
				return true;
			}	
			
			return false;
	 }
	 
	 /**
	  *
	  *
	  */
	 
	 private static function getChildren($id){
		 	global $connection;
			
			$result = $connection->database->prepare('SELECT * FROM pages 
													  WHERE parent_id = :parent_id 
													  AND hidden = 1
													  ORDER BY name ASC');
		 	
			$result->execute(array(':parent_id'=>$id));
			
			$children = '<ul class="dropdown-menu">';
    		
			foreach($result->fetchAll(PDO::FETCH_ASSOC) as $key => $value){
				 $pages = new Pages($value);
				 $children .= '<li><a href='.$pages->getLink().'>'.$pages->__get('name').'</a></li>';
			}
			
			$children .= '</ul>';
			
			
			return $children;
	 }
	 
	 /**
	  *
	  *
	  */
	 
	 private static function pageURLCheck($url) {
			if (strpos($_SERVER['REQUEST_URI'], $url) == false) {
				return false;
			} else {
				return true;
			}
	 }
	 
	 /**
	  *
	  *
	  */
	
	private static function isActiveMenu($active = true) {
		if ($active) {
			return 'active ';
		} else {
			return '';
		}
	 }
	 
	 /**
	  *
	  *
	  */
	 
	 private static function isDisabledMenu($disabled = 1) {
		if ($disabled == 2) {
			return 'disabled"';
		} else {
			return '';
		}
	 }


}

?>