<?php
//menu.php
require_once 'functions/adminFunctions.inc.php';

$uc = new classes\system\user_categs($dbObj);
$uc->getUser_categsData();
$allowedURL = $uc->getAllowed($_SESSION['ID']);
$categs = new classes\system\categs($dbObj);
$categs->getCategData();

if(!in_array(basename($_SERVER['PHP_SELF']),  $allowedURL) && basename($_SERVER['PHP_SELF'] )!= 'admin.php'){//
    header('location: http://'.$_SERVER['HTTP_HOST'].'/'.APP_FOLDER.'/index.php');
    exit();
}
$menuitemcounter = 0;
for($c=0; $c<count($categs->categsdata); $c++){
	if(!$uc->hasAccess($_SESSION['ID'], $categs->categsdata[$c]['id']) || !in_array(trim($categs->categsdata[$c]['descr']), $_MODULES) || $categs->categsdata[$c]['type'] == 'hidden')
		continue;
	print '<a href="'.$categs->categsdata[$c]['link'].'?syscat='.$categs->categsdata[$c]['id'].'" title="'.$categs->categsdata[$c]['descr'].'" >'.$categs->categsdata[$c]['descr'].'</a>';
	$menuitemcounter++;
    if($menuitemcounter < count($_MODULES))
        print '&nbsp;|&nbsp;';
}

?>