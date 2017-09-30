<?php

class File {
	
	private $id;
	private $name;
	private $ext;
	private $year;
	
	public function __construct($id,$name,$ext,$year){
		$this->id = $id;
		$this->name = $name;
		$this->ext = $ext;
		$this->year = $year;
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	public function getLink(){
		return 'newsletter/'.$this->year.'/'.$this->id.'/'.$this->name.$this->ext;
	}
	
	public function getMonth(){
		switch($this->id){
			case 1:
				return 'Január';
			case 2:
				return 'Február';
			case 3:
				return 'Március';
			case 4:
				return 'Április';
			case 5:
				return 'Május';
			case 6:
				return 'Június';
			case 7:
				return 'Július';
			case 8:
				return 'Augusztus';
			case 9:
				return 'Szeptember';
			case 10:
				return 'Október';
			case 11:
				return 'November';
			case 12:
				return 'December';
		}
	}
	
	public function __toString(){
		return '#'.$this->id . ' ' .$this->name.$this->ext . ' ' .$this->year;
	}
	

}
?>