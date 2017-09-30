<?php

class Events extends Object{

	private $id;
	private $name;
	private $content;
	private $startDate = array();
	private $endDate = array();
	private $hidden;
	private $pictureArray = array();
	
	public function __construct($array){
		$this->id = $array['id'];
		$this->name = $array['name'];
		$this->content = Misc::removeStyleAttributeExceptImg(str_replace('../img','img',$array['content']));
		$this->startDate = self::explodeDate($array['start']);
		$this->endDate = self::explodeDate($array['end']);
		$this->hidden = $array['hidden'];
		$this->pictureArray = $this->readPictures();
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
	
	private function readPictures(){
		$dir = 'img/events/'.$this->id.'/mini';
		$array = array();
		if(is_dir($dir)){
		  if ($handle = opendir($dir)) {
			while (false !== ($entry = readdir($handle))) {
				  if ($entry != "." && $entry != "..") {
					  
						 $array[] = '<img src="'.$dir.'/'.$entry.'"  alt="'.$this->name.'"/>';
				  }
			  }
			  closedir($handle);
		  }
		}
		return $array;
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	public function getPicture(){
	if(sizeof($this->pictureArray) > 0){
		return $this->pictureArray[0];	
		}
		return '<h2><i class="icon-picture"></i></h2>';
	}	
	
	public function getContent(){
		return Misc::removeStyleAttributeExceptImg(str_replace('../img','img',$this->content));
	}
	
	public function getLink(){
		return 'esemenyek.php?id='.$this->id.'';
	}
	
	public function getShortContent($charachter = 500){
		return substr(strip_tags($this->content),0,$charachter).'...';
	}
	
	public function getStartDate(){
		return $this->startDate[0]
			   .'.'
			   .$this->startDate[1]
			   .'.'
			   .$this->startDate[2]
			   .' '
			   .$this->startDate[3]
			   .':'
			   .$this->startDate[4];
	}
	
	public function getStartYear(){
		return $this->startDate[0];
	}
	
	public function getStartMonth(){
		return $this->startDate[1];
	}
	
	public function getStartDay(){
		return $this->startDate[2];
	}
	
	public function getStartHour(){
		return $this->startDate[3];
	}
	
	public function getStartMinute(){
		return $this->startDate[4];
	}
	
	public function getEndDate(){
		return $this->endDate[0]
			   .'.'
			   .$this->endDate[1]
			   .'.'
			   .$this->endDate[2]
			   .' '
			   .$this->endDate[3]
			   .':'
			   .$this->endDate[4];
	}
	
	public function getEndYear(){
		return $this->endDate[0];
	}
	
	public function getEndMonth(){
		return $this->endDate[1];
	}
	
	public function getEndDay(){
		return $this->endDate[2];
	}
	
	public function getEndHour(){
		return $this->endDate[3];
	}
	
	public function getEndMinute(){
		return $this->endDate[4];
	}
	
	/**
	 * Get event by id.
	 *
	 * @params $id 
	 * return new Events object if there is an article with the same id, otherwise return null.
	 */
	
	public static function getEvent($id){
		global $connection;
		$query = $connection->database->query('SELECT * FROM events WHERE id=\''.$id.'\' LIMIT 1');
		
		if($query->columnCount() > 0){
			$result = $query->fetchALL(PDO::FETCH_ASSOC);
			return new Events($result[0]);
		} else {
			return NULL;
		}
	}
	

}


?>