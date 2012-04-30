<?php
session_start();
require_once ("xajax/xajax.inc.php");
$_xajax = new xajax("xajax.server.php");
$_xajax->registerFunction("__userExists");
$_xajax->registerFunction("__indistructable");
$_xajax->registerFunction("__setTempSession");
$_xajax->registerFunction("__addAttachment");
$_xajax->registerFunction("__addContent");
$_xajax->registerFunction("__dnslookup");
$_xajax->registerFunction("__emailExists");
$_xajax->registerFunction("__isCorrectPassword");
$_xajax->registerFunction("__renderAdminSection");
$_xajax->registerFunction("__renderDescription");
$_xajax->registerFunction("__deleteMod");
?>
