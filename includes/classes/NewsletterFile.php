<?php

class NewsletterFile{
	private $files = array();
	private $startYear = 2013;
	
	public function __construct(){
		self::readFiles();
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	private function readFiles(){
		$f = array();
		for($i = $this->startYear; $i <= (int)date('Y'); $i++){
			if($i == (int)date('Y')){
				$month = (int)date('m');
			}else {
				$month = 12;
			}
						
			for($j = 1; $j<= $month; $j++){
					$dir = 'newsletter/'.$i.'/'.$j;
					if(is_dir($dir)){
					  if ($handle = opendir($dir)) {
						while (false !== ($entry = readdir($handle))) {
							  if ($entry != "." && $entry != "..") {
								  $f[] = $entry;

							  }
						  }
						  rsort($f);
						  $this->files[] = new File($j
								  			,substr($f[0],0,strpos($f[0],'.'))
											,substr($f[0],strpos($f[0],'.'),strlen($f[0]))
											,$i);;
						  $this->files[] = new File($j
								  			,substr($f[1],0,strpos($f[1],'.'))
											,substr($f[1],strpos($f[1],'.'),strlen($f[1]))
											,$i);
						  $f = array();
						  closedir($handle);
					  }
					}
			}
		}
	}

}