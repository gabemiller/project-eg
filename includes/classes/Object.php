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
