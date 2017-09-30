<?php
include_once("includes/functions.php");

fejlec_megjelenitese();
if(isset($_GET['id']) && !empty($_GET['id'])){
egy_esemeny_megjelenitese();
}else{
esemenyek_megjelenitese();
}
lablec_megjelenitese();
?>