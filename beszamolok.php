<?php
include_once("includes/functions.php");

fejlec_megjelenitese();
if(isset($_GET['id']) && !empty($_GET['id'])){
egy_beszamolo_megjelenitese();
} else {
beszamolok_megjelenitese();
}
lablec_megjelenitese();

?>