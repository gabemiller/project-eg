<?php

class ConnectDataBase {

	private static $instance = NULL;
	public $database;
	
	private static $dns = 'mysql:host=localhost;dbname=egyutt';
	private static $login = 'forge';
	private static $password = 'Z3P3otFt6RWKsQ6u4hoK';
	private static $attribute =  array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    									PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
  										);
	
	
	private function __construct(){
		try{
			$this->database = new PDO(self::$dns,self::$login,self::$password,self::$attribute);
		}catch(PDOexception $e){
			echo $e->getMessage();
		}
	}
	
	public static function getInstance(){
		if(self::$instance == NULL){
			self::$instance = new ConnectDataBase();
		}
		return self::$instance;
	}


}

?>