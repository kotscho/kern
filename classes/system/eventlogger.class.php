<?php
//class.logger.php
//logg everything
namespace classes\system;

class eventlogger{

var $defaultPath = '/var/www/html/kern/admin/log/';

function __construct(){
	
}


function logg($target, $content){
    $path = $this->defaultPath.$target;
    if(file_put_contents($path, date('d-m-Y', time()).'>>>>>----------------------<<<<<'.$content.NEWLINE, FILE_APPEND | LOCK_EX))
        return true;
    else 
        trigger_error('failed to log entry in '.$path, E_USER_ERROR);
}


}

?>