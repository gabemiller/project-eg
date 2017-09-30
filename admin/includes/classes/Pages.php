<?php

class Pages extends Object{

	private $id;
	private $name;
	private $content;
	private $hidden;
	
	public function __construct($id,$name,$content,$hidden){
		$this->id = $id;
		$this->name = $name;
		$this->content = $content;
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
	
	public function savePage(){
	  $connection = self::connectSQL();
	  $arrayRet = array();
	  
	  if($this->checkTitle()){
		  $arrayRet['message'] = 'Van már ilyen névvel rendelkező oldal!';
		  $arrayRet['type'] = 0;
		  $connection->close();
		  return $arrayRet;
	  }
	  
	  if($this->checkContent()){
		  $arrayRet['message'] = 'Van már ilyen tartalommal rendelkező oldal!';
		  $arrayRet['type'] = 0;
		  $connection->close();
		  return $arrayRet;
	  }
	  
	  $result = $connection->query('INSERT INTO pages 
									VALUES(NULL,\''
									.$connection->real_escape_string($this->name).'\',\''
									.$connection->real_escape_string($this->content).'\',\''
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
	 * Modify the page.
	 *
	 */
	
	public function modifyPage(){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM pages WHERE id=\''.$this->id.'\'');
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
		
		if($this->content != $result['content']){
			$modify[] = 'tartalom';
			$modifyQuery[] = 'content=\''.$connection->real_escape_string($this->content).'\'';
		}
		
		if($this->hidden != $result['hidden']){
			$modify[] = 'láthatóság';
			$modifyQuery[] = 'hidden=\''.$connection->real_escape_string($this->hidden).'\'';
		}
		
		
		$connection->query('UPDATE pages SET '.implode(', ',$modifyQuery).'WHERE id=\''.$this->id.'\'');
		
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
	 * Delete the page.
	 *
	 */
	
	public function deletePage(){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM pages WHERE id=\''.$this->id.'\'');
		
		if($query->num_rows>0){
			$connection->query('DELETE FROM pages WHERE id=\''.$this->id.'\'');
			
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
		$result = $connection->query('SHOW TABLE STATUS LIKE \'pages\'');
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
		$result = $connection->query('SELECT * FROM pages WHERE name =\''.$connection->real_escape_string($this->name).'\'');
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
		$result = $connection->query('SELECT * FROM pages WHERE content =\''.$connection->real_escape_string($this->content).'\'');
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
	
	public static function getPage($id){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM pages WHERE id=\''.$id.'\'');
		
		if($query->num_rows > 0){
			$result = $query->fetch_assoc();
			return new Pages($result['id'],
							$result['name'],
							$result['content'],
							$result['hidden']);
		} else {
			return NULL;
		}
	}


}

?>