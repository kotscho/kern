<?php
//searchbox.php
require_once('classes/system/class.search.php');
$sbo = new search($dbObj);
$resultArray = $sbo->fullSearch($_REQUEST['searchstring']);
var_dump($resultArray);
//now, a little bit more phantasy and design pattern please
?>