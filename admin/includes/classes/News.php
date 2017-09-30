<?php

class News extends Object{

	private $id;
	private $title;
	private $author;
	private $content;
	private $createDate;
	private $publicDate;
	private $hidden;

	public function __construct($id,$title,$author,$content,$publicDate,$createDate,$hidden){
		parent::__construct();
		$this->id = $id;
		$this->title = $title;
		$this->author = $author;
		$this->content = stripslashes($content);
		$this->createDate = $createDate;
		$this->publicDate = $publicDate;
		$this->hidden = $hidden;
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	/**
	 * Save the articles to the database.
	 *
	 */
	 
	public function saveArticle(){
		$connection = self::connectSQL();
		$arrayRet = array();
		
		if($this->checkTitle()){
			$arrayRet['message'] = 'Van már ilyen címmel rendelkező beszámoló!';
			$arrayRet['type'] = 0;
			$connection->close();
			return $arrayRet;
		}
		
		if($this->checkContent()){
			$arrayRet['message'] = 'Van már ilyen tartalommal rendelkező beszámoló!';
			$arrayRet['type'] = 0;
			$connection->close();
			return $arrayRet;
		}
		
		$result = $connection->query('INSERT INTO news 
									  VALUES(NULL,\''
									  .$connection->real_escape_string($this->title).'\',\''
									  .$connection->real_escape_string($this->author).'\',\''
									  .$connection->real_escape_string($this->createDate).'\',\''
									  .$connection->real_escape_string($this->publicDate).'\',\''
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
	 * Modify the article.
	 *
	 */
	
	public function modifyArticle(){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM news WHERE id=\''.$this->id.'\'');
		$modify = array();
		$modifyQuery = array();
		
		if($query->num_rows > 0){
			$result = $query->fetch_assoc();
		} else {
			$connection->close();
			return array('message'=>'Sikertelen módosítás!',
						 'type' => 0);
		}
		
		if($this->title != $result['title']){
			$modify[] = 'cím';
			$modifyQuery[] = 'title=\''.$connection->real_escape_string($this->title).'\'';
		}
		
		if($this->author != $result['author']){
			$modify[] = 'szerző';
			$modifyQuery[] = 'author=\''.$connection->real_escape_string($this->author).'\'';
		}
		
		if($this->publicDate != $result['public']){
			$modify[] = 'publikálás';
			$modifyQuery[] = 'public=\''.$connection->real_escape_string($this->publicDate).'\'';
		}
		
		if($this->content != $result['content']){
			$modify[] = 'tartalom';
			$modifyQuery[] = 'content=\''.$connection->real_escape_string($this->content).'\'';
		}
		
		if($this->hidden != $result['hidden']){
			$modify[] = 'láthatóság';
			$modifyQuery[] = 'hidden=\''.$connection->real_escape_string($this->hidden).'\'';
		}
		
		
		$connection->query('UPDATE news SET '.implode(', ',$modifyQuery).'WHERE id=\''.$this->id.'\'');
		
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
	 * Delete the article.
	 *
	 */
	 
	 public function deleteArticle(){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM news WHERE id=\''.$this->id.'\'');
		
		if($query->num_rows>0){
			$connection->query('DELETE FROM news WHERE id=\''.$this->id.'\'');
			
			if($connection->affected_rows>0){
				$connection->close();
				return array('message'=>'Sikeres törlés! A (#'.$this->id.') '.$this->title.' beszámoló törölve.',
							 'type'=>1);		
			}else{
				$connection->close();
				return array('message'=>'Sikertelen törlés!',
							 'type'=>0);
			}
			
		} else {
			$connection->close();
			return array('message'=>'Sikertelen törlés! Nincs ilyen beszámoló.',
						 'type'=>0);
		}
	 }
	
	/**
	 * Get article by id.
	 *
	 * @params $id 
	 * return new News object if there is an article with the same id, otherwise return null.
	 */
	
	public static function getArticle($id){
		$connection = self::connectSQL();
		$query = $connection->query('SELECT * FROM news WHERE id=\''.$id.'\'');
		$connection->close();
		if($query->num_rows > 0){
			$result = $query->fetch_assoc();
			return new News($result['id'],
							$result['title'],
							$result['author'],
							$result['content'],
							$result['public'],
							$result['date'],
							$result['hidden']);
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get next id from the news table.
	 *
	 * return (integer) $id
	 */
	
	public static function getNextId(){
		$connection = self::connectSQL();
		$result = $connection->query('SHOW TABLE STATUS LIKE \'news\'');
		$data = $result->fetch_assoc();
		$connection->close();
		return $data['Auto_increment'];
	}
	
	/**
	 * Check the title.
	 *
	 * return true if there is the same title, otherwise return false
	 */
	
	private function checkTitle(){
		$connection = self::connectSQL();
		$result = $connection->query('SELECT * FROM news WHERE title =\''.$connection->real_escape_string($this->title).'\'');
		$connection->close();
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
		$result = $connection->query('SELECT * FROM news WHERE content =\''.$connection->real_escape_string($this->content).'\'');
		$connection->close();
		if($result->num_rows > 0){
			return true;
		} else {
			return false;
		}
	}


}

?>