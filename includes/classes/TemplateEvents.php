<?php

class TemplateEvents extends TemplateProcessor{

	public function __construct($queryString){
		global $connection;
		$array = array();
		
		$query = $connection->database->query($queryString);
		
		if($query->columnCount() > 0){
			
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result as $value){
				$array[] = new Events($value);
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
						,'{{startDate}}'
						,'{{endDate}}'
						,'{{link}}'
						,'{{picture}}');
						
		if(!empty($this->array)){	
			
			foreach($this->array as $obj){
				$replace = array($obj->__get('name')
								,$obj->__get('name')
								,$obj->__get('content')
								,$obj->__get('content')
								,$obj->getStartDate()
								,$obj->getEndDate()
								,$obj->getLink()
								,$obj->getPicture());
			
				$templateArray[] = str_replace($search,$replace,$templateString);
			}
		} else {
			$templateArray[] = self::emptyTemplate('');
		}
		
		return $templateArray;
	}


}

?>