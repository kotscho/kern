<?php
//class.rendering.php
namespace classes\system;

class rendering{
    
    var $output;
    
    function __construct(&$dbobj, $type){
        $this->dbObj = $dbobj;
    }
}


?>