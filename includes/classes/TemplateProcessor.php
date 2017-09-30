<?php

class TemplateProcessor{
	protected $array = array();

	public function __construct(array $array){
		$this->array = $array;
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,array $val){
		$this->$prop = $val;
	}
	
	public static function printTemplate(array $templateArray){
		foreach($templateArray as $value){
			echo $value;
		}
	}
	
	protected static function emptyTemplate($text){
		return '<div class="empty-template">'.$text.'</div>';
	} 

}

?>