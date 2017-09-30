<?php

class Events extends Object{

	private $id;
	private $name;
	private $content;
	private $startDate;
	private $endDate;
	private $hidden;
	
	public function __construct($id,$name,$content,$start,$end,$hidden){
		$this->id = $id;
		$this->name = $name;
		$this->content = stripslashes($content);
		$this->startDate = $start;
		$this->endDate = $end;
		$this->hidden = $hidden;
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	/**
	 * Save the event to the database.
	 *
	 */
	
	public function saveEvent(){
	  $connection = self::connectSQL();
	  $arrayRet = array();
	  
	  if($this->checkTitle()){
		  $arrayRet['message'] = 'Van már ilyen címmel rendelkező esemény!';
		  $arrayRet['type'] = 0;
		  $connection->close();
		  return $arrayRet;
	  }
	  
	  if($this->checkContent()){
		  $arrayRet['message'] = 'Van már ilyen tartalommal rendelkező esemény!';
		  $arrayRet['type'] = 0;
		  $connection->close();
		  return $arrayRet;
	  }
	  
	  $result = $connection->query('INSERT INTO events 
									VALUES(NULL,\''
									.$connection->real_escape_string($this->name).'\',\''
									.$connection->real_escape_string($this->content).'\',\''
									.$connection->real_escape_string($this->startDate).'\',\''
									.$connection->real_escape_string($this->endDate).'\',\''
									.$connection->real_escape_string($this->hidden).'\')');
	  
	  if($connection->affected_rows>0){
		  $arrayRet['message'] = 'Sikeres feltöltés!';
		  $arrayRet['type'] = 1;
		  $connection->close();
		  return $arrayRet;
	  } else {
		  $arrayRet['message'] = 'Sikertelen feltöltés!';
		  $arrayRet['type'] = 0;
		  $connection->close();
		  return $arrayRet;
	  }
	
	}
	
	/**
	 * Modify the  event.
	 *
	 */
	
	public function modifyEvent(){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM events WHERE id=\''.$this->id.'\'');
		$modify = array();
		$modifyQuery = array();
		
		if($query->num_rows > 0){
			$result = $query->fetch_assoc();
		} else {
			$connection->close();
			return array('message'=>'Sikertelen módosítás!',
						 'type' => 0);
		}
		
		if($this->name != $result['name']){
			$modify[] = 'cím';
			$modifyQuery[] = 'name=\''.$connection->real_escape_string($this->name).'\'';
		}
		
		if($this->startDate != $result['start']){
			$modify[] = 'indulás';
			$modifyQuery[] = 'start=\''.$connection->real_escape_string($this->startDate).'\'';
		}
		
		if($this->endDate != $result['end']){
			$modify[] = 'befejezés';
			$modifyQuery[] = 'end=\''.$connection->real_escape_string($this->endDate).'\'';
		}
		
		if($this->content != $result['content']){
			$modify[] = 'tartalom';
			$modifyQuery[] = 'content=\''.$connection->real_escape_string($this->content).'\'';
		}
		
		if($this->hidden != $result['hidden']){
			$modify[] = 'láthatóság';
			$modifyQuery[] = 'hidden=\''.$connection->real_escape_string($this->hidden).'\'';
		}
		
		
		$connection->query('UPDATE events SET '.implode(', ',$modifyQuery).'WHERE id=\''.$this->id.'\'');
		
		if($connection->affected_rows>0){
			$connection->close();
			return array('message'=>'Sikeres módosítás! A módosult elemek: '.implode(', ',$modify),
						 'type'=> 1);
		}else{
			$connection->close();
			return array('message'=>'Sikertelen módosítás!',
						 'type'=> 0);
		}
	
	}
	
	/**
	 * Delete the  event.
	 *
	 */
	
	public function deleteEvent(){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM events WHERE id=\''.$this->id.'\'');
		
		if($query->num_rows>0){
			$connection->query('DELETE FROM events WHERE id=\''.$this->id.'\'');
			
			if($connection->affected_rows>0){
				$connection->close();
				return array('message'=>'Sikeres törlés! A (#'.$this->id.') '.$this->name.' beszámoló törölve.',
							 'type'=>1);		
			}else{
				$connection->close();
				return array('message'=>'Sikertelen törlés!',
							 'type'=>0);
			}
			
		} else {
			$connection->close();
			return array('message'=>'Sikertelen törlés! Nincs ilyen esemény.',
						 'type'=>0);
		}
	}
	
	/**
	 * Get next id from the events table.
	 *
	 * return (integer) $id
	 */
	
	public static function getNextId(){
		$connection = self::connectSQL();
		$result = $connection->query('SHOW TABLE STATUS LIKE \'events\'');
		$data = $result->fetch_assoc();
		return $data['Auto_increment'];
	}
	
    /**
	 * Check the name.
	 *
	 * return true if there is the same name, otherwise return false
	 */
	
	private function checkTitle(){
		$connection = self::connectSQL();
		$result = $connection->query('SELECT * FROM events WHERE name =\''.$connection->real_escape_string($this->name).'\'');
		if($result->num_rows > 0){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Check the content.
	 *
	 * return true if there is the same content, otherwise return false.
	 */
	
	private function checkContent(){
		$connection = self::connectSQL();
		$result = $connection->query('SELECT * FROM events WHERE content =\''.$connection->real_escape_string($this->content).'\'');
		if($result->num_rows > 0){
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
	
	public static function getEvent($id){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM events WHERE id=\''.$id.'\'');
		
		if($query->num_rows > 0){
			$result = $query->fetch_assoc();
			return new Events($result['id'],
							$result['name'],
							$result['content'],
							$result['start'],
							$result['end'],
							$result['hidden']);
		} else {
			return NULL;
		}
	}

}


?>