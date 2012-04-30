<?php
    include('../../classes/system/class.search.php');
    $search = new search($dbObj);
    $searchresult = $search->fullSearch(trim($_REQUEST['searchstring']));
?>
