<?php
#============== constantvar-definition and db-connection ==============#
#																	   #   																#
#======================================================================#
//codename: kaisarianh
error_reporting(E_ALL);

require_once '../common-globals.php';
require_once $_SERVER['DOCUMENT_ROOT'].APP_WITH_SLASH.'/classes/external/pear/MDB2.php';
require_once $_SERVER['DOCUMENT_ROOT'].APP_WITH_SLASH.'/classes/external/GphpChart.class.php';
require_once '../function.autoload.php';
//require_once $_SERVER['DOCUMENT_ROOT'].APP_WITH_SLASH.'/classes/class.chartmod.php';
//require_once $_SERVER['DOCUMENT_ROOT'].APP_WITH_SLASH.'/classes/system/class.eventlogger.php';
define('CHECKSUM', '');//checkssum of the project(.zip compressed)
define("BASE_URL", 'http://'.$_SERVER['HTTP_HOST'].APP_WITH_SLASH);
define("APP_FOLDER", 'admin');//ut stands for "UNTOUCHEABLE"
define("VENDOR", 'Kern');
define("SYS_FOLDER", 'system/');
define("EXT_FOLDER", 'external/');
define("CLASS_PATH", "../classes/src");
define("EXEC_PATH", "/usr/local/bin/");
define("PAGER_LIMIT", "5");// paging limit, records displayed per page (admin-panel)
define("OS", (stristr(PHP_OS, 'win') !== FALSE)?'WIN':'UNIX');//determine os: for testing purposes
define("NEWLINE", PHP_EOL);
//set SEO_FRIENDLY to ON will render category items used in menus, based on their descriptions
//like: manager/profile/change-profile ect.
//Set OFF, you will access category items like: frontend/source/main.php?itemid=2 ect.
define("SEO_FRIENDLY", 'ON'); 

//add active modules
$_MODULES = array("Διαχείριση χρηστών", "Categories by Unit" ,"Category-Units", "Content", "Application-nodes", "Υλικό", "Συντάκτες", "Σύνδεσμοι", "Μέλη",
				 "Newsletter", "Σχόλια", "Gallery", "Mods");
 				
$grdate = array('Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή', 'Σάββατο');
$statusDescr[1]='-ενεργό-';
$statusDescr[2]='-draft-';
$DSN = "mysql://".DB_USER.":".DB_PASSWORD."@".DB_SERVER."/".DB_NAME;
$options = array('debug' => 1, 
				'log_line_break' => "\n\t", 
				'portability' => MDB2_PORTABILITY_NONE
				);
$dbObj = MDB2::connect($DSN, $options);
$dbObj->query("SET NAMES 'utf8'");//patches the known mysql client bug...if it occurs
//$dbObj->getDebugOutput();
//allowed MIME TYPES
//!!! THERES IS A KNOWN BUG IN FIREFOX MIMETYPE DETERMINATION - READ THE FOLLOWING
//http://techblog.procurios.nl/k/news/view/15872/14863/Mimetype-corruption-in-Firefox.html
//the next version of the CMS should implement a wokaround for this problem
//you may get an 'application/force-download' mimetype for pdf and other files!
//NEVER ADD THIS PARTICULAR MIMETYPE TO THE LIST BELOW, ITS A SECURITY WHOLE 

$_MIME_TYPES = array('image/png', 
                                    'image/gif', 
                                    'image/jpg',
                                    'image/jpeg',
                                    'application/zip',
                                    'application/x-tex', 
                                    'application/x-latex',
                                    'application/x-shockwave-flash',
                                    'application/x-gtar',
                                    'application/xml',
                                    'application/x-msdownload',
                                    'application/mspowerpoint',
                                    'application/msword',
                                    'application/rtf',
                                    'text/plain',
                                    'application/pdf',
                                    'application/postscript');
                                    
$gphpchart=new GphpChart('lc');//test the class 

?>