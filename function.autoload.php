<?php
/*
 * Simple autoload
 * 
 */

function classLoader($classname)
{
   
    $classname = str_replace("\\",'/',$classname);

    if (file_exists('../'.$classname.'.class.php')) {
	require_once '../'.$classname.'.class.php';
    }
   
}

spl_autoload_register('classLoader');