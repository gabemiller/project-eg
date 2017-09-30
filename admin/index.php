<?php

require_once('includes/functions.php');

if(!$admin->isLoggedIn()){
showHTMLHeader(true);
showLogin();
}else {
showHeader();
showIndex();
}

showFooter();

?>