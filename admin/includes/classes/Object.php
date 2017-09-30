<?php

/**
 * Object Class
 * The root class of all of my classes.
 *
 * @author Molnár Gábor
 */
class Object {

    private $obj_id;

    public function __construct() {
        $this->obj_id = spl_object_hash($this);
    }

    public function getObj_id() {
        return $this->obj_id;
    }

    public function __toString() {
        return (string)$this->obj_id;
    }

    static final function connectSQL() {

        try {

			$connection = new mysqli('localhost','egyutt', 'f3na7j','egyutt');
		
            if (!$connection) {
                throw new Exception('Nem sikerült csatlakozni az adatbázishoz!');
            }
			$connection->query("SET NAMES utf8");
        	$connection->query("SET collation_connection = 'utf8'");
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
		
		return $connection;
    }

    static final function removeSpecialCharacters($string,$small = false, $big = false) {
		if($small){
			$string = strtolower($string);
		}
		
		if($big){
			$string = strtoupper($string);
		}
		
        $search = array(' ', '/', '\\', '"', '\'', 'á', 'é', 'í', 'ü', 'ű', 'ú', 'ö', 'ő', 'ó', 'Á', 'É', 'Í', 'Ü', 'Ű', 'Ú', 'Ö', 'Ő', 'Ó');
        $replace = array('-', '', '', '', '', 'a', 'e', 'i', 'u', 'u', 'u', 'o', 'ó', 'o', 'A', 'E', 'I', 'U', 'U', 'U', 'O', 'O', 'O');
        $newstring = str_replace($search, $replace, $string);

        return $newstring;
    }
	
	static final function removeStyleAttributeExceptImg($html){
		return preg_replace('/(<(?!img)\w+[^>]+)(style="[^"]+")([^>]*)(>)/', '${1}${3}${4}', $html);
	}
	
	static final function getImgsFromFolder($folder){
		
		if(!file_exists($folder)){
			return NULL;
		}

		if ($handle = opendir($folder)) {
			
			$images = array();

			while (false !== ($entry = readdir($handle))) {
				  if ($entry != "." && $entry != "..") {
					$images[] = '<li class="span6">'
						 		.'<div class="thumbnail">'
						 		.'<img src="'.$folder.'/'.$entry.'" data-url="'.str_replace('mini','',$folder)
								.str_replace('_mini_','_',$entry).'"/>'
						 		.'</div>'
						 		.'</li>';
				  }
			  }
			  closedir($handle);
			  return $images;
		  }
		
	}

}

?>
