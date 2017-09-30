<?php
include_once("includes/functions.php");

fejlec_megjelenitese();
if(strcmp($_GET['oldal'],'folyo-projektek')==0){
	folyo_projektek_megjelenitese();
}else{
	indulo_projektek_megjelenitese();
}
lablec_megjelenitese();
?>