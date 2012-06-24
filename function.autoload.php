<?php
/*
 * Simple autoload
 * kdos did this on the 24th of June 2012
 */

function classLoader($classname)
{
   
    $classname = str_replace("\\",'/',$classname);

    if (file_exists('../'.$classname.'.class.php')) {
	require_once '../'.$classname.'.class.php';
    }
   
}

spl_autoload_register('classLoader');
