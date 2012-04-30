<?php
require_once('functions/adminFunctions.inc.php');

$__tree = new classes\system\application_nodes($dbObj);
if ($_REQUEST['root'] == "source"){
    $tree=$__tree->initTree($_GET['unit']);//unit determines which taxonomy/category unitis loaded
    print $tree;
}

?>

