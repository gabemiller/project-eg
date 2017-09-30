<?php

class Employee {
	
	private $name;
	private $email;
	private $county;
	private $city;
	
	public function __construct($array){
		$this->name = $array['name'];
		$this->email = $array['email'];
		$this->county = $array['county'];
		$this->city  = $array['city'];
	}

	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	public static function getAllEmployee(){
		global $connection;
		$query = $connection->database->query('SELECT * FROM employee ORDER BY county ASC');
		
		if($query->rowCount() > 0){
			$result = $query->fetchALL(PDO::FETCH_ASSOC);
			$employees = array();
			foreach($result as $key=>$value){
				$employees[] = new Employee($value);
			}
			return $employees;
		} else {
			return NULL;
		}
	}

}


?>