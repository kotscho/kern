<?php

require_once 'functions/adminFunctions.inc.php';

$ob = new classes\src\newsletter($dbObj);
if(is_object($ob))
    die('loaded with SPL');
die('no');
?>
