<?php
//config.php for frontend

error_reporting(E_ALL);
include('../../common-globals.php');
require_once $_SERVER['DOCUMENT_ROOT'].APP_WITH_SLASH.'/classes/external/pear/MDB2.php';
$grdate = array('Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή', 'Σάββατο');
$DSN = "mysql://".DB_USER.":".DB_PASSWORD."@".DB_SERVER."/".DB_NAME;
$options = array('portability' => MDB2_PORTABILITY_NONE);
$dbObj = MDB2::connect($DSN, $options);

$dbObj->query("SET NAMES 'utf8'");
//tagcloud: minimal and maximal fontsize
define("_MIN_", 11);
define("_MAX_", 17);
