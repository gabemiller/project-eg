<?php

class Misc{
	
	public static final function removeStyleAttributeExceptImg($html){
		return preg_replace('/(<(?!img|div)\w+[^>]+)(style="[^"]+")([^>]*)(>)/', '${1}${3}${4}', $html);
	}
	
	public static final function removeSpecialCharacters($string,$small = false, $big = false) {
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

}

?>