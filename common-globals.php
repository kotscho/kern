<?php
//common-globals.php
define('REVISION', '09-12-10');
define("VERSION", "v. 0.1. - rev.".REVISION);
define("DB_SERVER", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "kern");
define("APP", 'kern');

#======================== CHANGE THIS UPON DEPLOYMENT =======================================#
define("LOCAL", '127.0.0.1/'); //provide empty string for live version
define("APP_WITH_SLASH", 'kern-test');//provide empty string for live version
#===========================================================================================#

define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'].APP_WITH_SLASH);
define("HTDOCS", '/var/www/html/');//!!!CHANGE!!! FOR LIVE VERSION - SET TO REAL VALUE
define("NO_REPLY", 'noreply@'.APP.'.gr');
ini_set("SMTP","smtp.".APP );//do we really need this here?
ini_set('sendmail_from', 'noreply@'.APP); //change this to NO_REPLY after testing
//common sql injection protector(mysql specific)
//we also perform a general sanitization in /frontend/source/main.php
function safe_sql($value)
{
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}
?>
