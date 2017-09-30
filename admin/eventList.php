<?php

require_once('includes/functions.php');

$admin->checkPermission();

showHeader();
showEventList();
showFooter();

?>