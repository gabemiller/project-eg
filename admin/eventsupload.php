<?php

require_once('includes/functions.php');

$admin->checkPermission();

showHeader();
showEventsUpload();
showFooter();

?>