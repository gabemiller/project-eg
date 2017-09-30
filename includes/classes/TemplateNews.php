<?php

class TemplateNews extends TemplateProcessor{

	public function __construct($queryString){
		global $connection;
		$array = array();
		
		$query = $connection->database->query($queryString);
		
		if($query->columnCount() > 0){
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result as $value){
				$array[] = new News($value);
			}	
		}	
		parent::__construct($array);
	}
	
	public function createTemplate($templateString){
		$templateArray = array();
		
		$search = array('{{shortTitle}}'
						,'{{title}}'
						,'{{author}}'
						,'{{shortContent}}'
						,'{{content}}'
						,'{{createDate}}'
						,'{{publicDate}}'
						,'{{link}}');
						
		if(!empty($this->array)){	
			
			foreach($this->array as $obj){
				$replace = array($obj->__get('title')
								,$obj->__get('title')
								,$obj->__get('author')
								,$obj->getShortContent()
								,$obj->__get('content')
								,$obj->getCreateDate()
								,$obj->getPublicDate()
								,$obj->getLink());
			
				$templateArray[] = str_replace($search,$replace,$templateString);
			}
		} else {
			$templateArray[] = self::emptyTemplate('');
		}
		
		return $templateArray;
	}


}

?>