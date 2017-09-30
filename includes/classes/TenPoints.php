<?php

class TenPoints {

	private $id;
	private $points;
	
	public function __construct($array){
		$this->id = $array['id'];
		$this->points = $array['points'];
	}

	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	public static function getTenPointsById($id){
		global $connection;
		$query = $connection->database->query('SELECT * FROM tenpoints WHERE id=\''.$id.'\' LIMIT 1');
		
		if($query->rowCount() > 0){
			$result = $query->fetchALL(PDO::FETCH_ASSOC);
			return new TenPoints($result[0]);
		} else {
			return NULL;
		}
	}


}
?>