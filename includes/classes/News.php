<?php

class News{

	private $id;
	private $title;
	private $author;
	private $createDate = array();
	private $publicDate = array();
	private $content;
	private $hidden;
	
	public function __construct($array){
		$this->id = $array['id'];
		$this->title = $array['title'];
		$this->author = $array['author'];
		$this->content = Misc::removeStyleAttributeExceptImg(str_replace('../img','img',$array['content']));
		$this->createDate = self::explodeDate($array['date']);
		$this->publicDate = self::explodeDate($array['public']);
		$this->hidden = $array['hidden'];
	}
	
	private static function explodeDate($array){
		$a = array();
		$b = array();
		$c = array();
		
		$a = explode(' ',$array);
		$b = explode('-',$a[0]);
		$c = explode(':',$a[1]);
		
		return array_merge($b,$c);
	}
	
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	public function getCreateDate(){
		return $this->createDate[0]
			   .'.'
			   .$this->createDate[1]
			   .'.'
			   .$this->createDate[2]
			   .' '
			   .$this->createDate[3]
			   .':'
			   .$this->createDate[4];
	}
	
	public function getCreateYear(){
		return $this->createDate[0];
	}
	
	public function getCreateMonth(){
		return $this->createDate[1];
	}
	
	public function getCreateDay(){
		return $this->createDate[2];
	}
	
	public function getCreateHour(){
		return $this->createDate[3];
	}
	
	public function getCreateMinute(){
		return $this->createDate[4];
	}
	
	public function getPublicDate(){
		return $this->publicDate[0]
			   .'.'
			   .$this->publicDate[1]
			   .'.'
			   .$this->publicDate[2]
			   .' '
			   .$this->publicDate[3]
			   .':'
			   .$this->publicDate[4];
	}
	
	public function getPublicYear(){
		return $this->publicDate[0];
	}
	
	public function getPublicMonth(){
		return $this->publicDate[1];
	}
	
	public function getPublicDay(){
		return $this->publicDate[2];
	}
	
	public function getPublicHour(){
		return $this->publicDate[3];
	}
	
	public function getPublicMinute(){
		return $this->publicDate[4];
	}
	
	/**
	 *
	 *
	 */
	
	public function getLink(){
		return 'beszamolok.php?id='.$this->id.'';
	}
	
	public function getShortContent(){
		return substr(strip_tags($this->content),0,500).'...';
	}
	
	public function getNextId(){
		global $connection;
		
		$query = 'SELECT id FROM news 
				  WHERE public <= NOW() AND hidden = 1
				  AND id = (SELECT min(id) FROM news 
				  WHERE id > \''.$this->id.'\')';
				  
		$result = $connection->database->query($query);
		$nextId = $result->fetchAll(PDO::FETCH_ASSOC);
		if(sizeof($nextId)>0){
			return $nextId[0]['id'];
		}
		return false;
	}
	
	public function getPrevId(){
		global $connection;
		
		$query = 'SELECT id FROM news 
			  	  WHERE  public <= NOW() AND hidden = 1
				  AND id = (SELECT max(id) FROM news 
			  	  WHERE id < \''.$this->id.'\')';
			  
		$result = $connection->database->query($query);
		$prevId = $result->fetchAll(PDO::FETCH_ASSOC);
		if(sizeof($prevId)>0){
			return $prevId[0]['id'];
		}
		return false;
	}
	
	public function getNextButton(){
		if($this->getNextId()){
			return '<li class="next">'
               	  .'<a href="beszamolok.php?id='.$this->getNextId().'">Következő <i class="icon-chevron-right"></i></a>'
              	  .'</li>';
		}else{
			return '<li class="next disabled">'
               	  .'<span>Következő <i class="icon-chevron-right"></i></span>'
              	  .'</li>';
		}
	}
	
	public function getPrevButton(){
		if($this->getPrevId()){
			return '<li class="previous">'
               	  .'<a href="beszamolok.php?id='.$this->getPrevId().'"><i class="icon-chevron-left"></i> Előző</a>'
              	  .'</li>';
		}else{
			return '<li class="previous disabled">'
               	  .'<span><i class="icon-chevron-left"></i> Előző</span>'
              	  .'</li>';
		}
	}
	
	
	
	/**
	 * Get article by id.
	 *
	 * @params $id 
	 * return new News object if there is an article with the same id, otherwise return null.
	 */
	
	public static function getArticle($id){
		global $connection;
		$query = $connection->database->query('SELECT * FROM news WHERE id=\''.$id.'\' LIMIT 1');
		if($query->columnCount() > 0){
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return new News($result[0]);
		} else {
			return NULL;
		}
	}

	


}

?>