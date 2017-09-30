<?php

class Pages{

	private $id;
	private $parentId;
	private $name;
	private $url;
	private $content;
	private $active;
	private $hidden;
	
	public function __construct($array){
		$this->id = $array['id'];
		$this->parentId = $array['parent_id'];
		$this->name = $array['name'];
		$this->url = $array['url'];
		$this->content = Misc::removeStyleAttributeExceptImg(str_replace('../img','img',$array['content']));
		$this->active = $array['active'];
		$this->hidden = $array['hidden'];
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	/**
	 *
	 *
	 */
	
	public function getLink(){
		if(!empty($this->url)){
			return $this->url;
		}
		return 'oldalak.php?id='.$this->id.'';
	}
	
	public static function isPage($id){
		global $connection;
		$query = $connection->database->query('SELECT * FROM pages WHERE id=\''.$id.'\' LIMIT 1');
		
		if($query->columnCount() > 0){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Get event by id.
	 *
	 * @params $id 
	 * return new Events object if there is an article with the same id, otherwise return null.
	 */
	
	public static function getPage($id){
		global $connection;
		$query = $connection->database->query('SELECT * FROM pages WHERE id=\''.$id.'\' LIMIT 1');
		
		if($query->columnCount() > 0){
			$result = $query->fetchALL(PDO::FETCH_ASSOC);
			return new Pages($result[0]);
		} else {
			return NULL;
		}
	}
	
}

?>