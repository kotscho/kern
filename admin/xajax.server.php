<?php
session_start();
require_once('functions/adminFunctions.inc.php');
require_once(CLASS_PATH.SYS_FOLDER."class.user.php");
require_once(CLASS_PATH.SYS_FOLDER."class.material.php");
require_once(CLASS_PATH.SYS_FOLDER."class.mods.php");
require_once(CLASS_PATH."class.content_material.php");
require_once(CLASS_PATH."class.content_application_nodes.php");
require_once(CLASS_PATH."class.content.php");
require_once(CLASS_PATH."class.members.php");

require("xajax.common.php");

function __userExists($name, $id){

	global $dbObj;
	$__objResponse = new xajaxResponse();
	$object = new classes\system\user($dbObj);
	if($object->getUserID($name) !== false ){
		if($object->getUserID($name) == $id)//user updating with his username...allowed
			$__objResponse->addScript('document.user_form.submit();');
		else
			$__objResponse->addAlert('Το όνομα χρήστη που επιλέξατε είναι ήδη κατοχυρωμένο...');
	}
	else 
		$__objResponse->addScript('document.user_form.submit();');
		
	return $__objResponse;
}
//kotscho here...
function __setTempSession($name, $descr, $from, $to){
    $__objResponse = new xajaxResponse();
    $_SESSION['name'] = $name;
    $_SESSION['description'] = $descr;
    $_SESSION['datepicker'] = $from;
    $_SESSION['datepicker_to'] = $to;
    
    return $__objResponse;
}

function __indistructable($id, $uid){
    
	$__objResponse = new xajaxResponse();
	if($id < 2)//core user
		$__objResponse->addAlert('Αυτός ο χρήστης δεν διαγράφεται...');
	else
		$__objResponse->addScript("document.location.href=' user.php?del=true&id=".$uid."'");
		//addScript("document.location.href=' user.php?del=true&amp;id=".$id."'");
	return $__objResponse;
}

function __addAttachment($id, $oldids='' ){
    global $dbObj;
    //loop with radio button(selected) for attachments(material) allready stored and for attachments 
    //which are selected in current session but not stored yet 
    $__objResponse = new xajaxResponse();
    $nm = new classes\src\content_material($dbObj);
    $mat = new classes\system\material($dbObj);
    $mat->getMaterialDataById((int)$id);
    if(!empty($oldids)){
        $currentids = explode(':',$oldids);
        for($z=0; $z<count($currentids); $z++)
            $html .= '<input style="width: 10px;" type="checkbox" name="active_material[]" value="'.$currentids[$z].'" checked />&nbsp;'.materialHelper::getMaterialName($currentids[$z]).'<br />';
    }
    if(!in_array($mat->ID, $currentids))//new attachment only if not allready part of list
        $html .= '<input style="width: 10px;" type="checkbox" name="active_material[]" value="'.$mat->ID.'" checked />&nbsp;'.$mat->name.'<br />';
    $__objResponse->addScript("opener.document.getElementById(\"radios\").innerHTML ='".$html."';");
    $__objResponse->addScript("opener.document.getElementById(\"radios\").style.display='block';");
    $__objResponse->addScript("self.close();");

    return $__objResponse;
}

function __addContent($id, $oldids='' ){
    global $dbObj;
    //loop with radio button(selected) for attachments(material) allready stored and for attachments 
    //which are selected in current session but not stored yet 
    $__objResponse = new xajaxResponse();
    $nc = new classes\src\content_application_nodes($dbObj);
    $news = new classes\src\content($dbObj);
    $news->get_itemByID((int)$id);
    if(!empty($oldids)){
        $currentids = explode(':',$oldids);
        for($z=0; $z<count($currentids); $z++)
            $html .= '<input style="width: 10px;" type="checkbox" name="active_articles[]" value="'.$currentids[$z].'" checked />&nbsp;<a class="attached-articles" href="content.php?edit=true&amp;id='.$currentids[$z].'">'.contentHelper::getContentTitleByID($currentids[$z]).'</a><br />';
    }
    if(!in_array($news->ID, $currentids))//new attachment only if not allready part of list
        $html .= '<input style="width: 10px;" type="checkbox" name="active_articles[]" value="'.$news->ID.'" checked />&nbsp;<a class="attached-articles" href="content.php?edit=true&amp;id='.$news->ID.'">'.$news->title.'</a><br />';
    $__objResponse->addScript("opener.document.getElementById(\"articleradios\").innerHTML ='".$html."';");
    $__objResponse->addScript("opener.document.getElementById(\"articleradios\").style.display='block';");
    $__objResponse->addScript("self.close();");

    return $__objResponse;
}

function __dnslookup($string){
    
    $__objResponse = new xajaxResponse();
    $host = end(explode('@',$string));
    $ip = gethostbyname($host);
    if($ip == $host)
        $__objResponse->addAlert('Το domain ('.$host.') δεν υπάρχει');
   
    return $__objResponse;
}

function __emailExists($string){
    global $dbObj;
    $ob=new classes\src\members($dbObj);
    $__objResponse = new xajaxResponse();
    if($ob->useremailexists($string))
        $__objResponse->addAlert('Το email ('.$string.') είναι κατοχυρομένω');
        
    return $__objResponse;
}

function __isCorrectPassword($string, $id){
    global $dbObj;
    $ob=new classes\system\members($dbObj);
    $__objResponse = new xajaxResponse();
    if(empty($string))
        return $__objResponse;
    if(!$ob->isPassword($string, $id)){
        $__objResponse->addAlert('Ο κωδικός είναι λάθος'.md5($string));
        $__objResponse->addScript("document.members_form.olduserpassword.focus();");
    }
     return $__objResponse;
}

function __renderAdminSection($modname){
	global $dbObj;
	$ob = new classes\system\mods($dbObj);
	$__objResponse = new xajaxResponse();
	$__objResponse->addAssign('mod', 'innerHTML', $ob->renderAdminSection($modname));
	return $__objResponse; 
}

function __renderDescription($modname){
	global $dbObj;
	$ob = new classes\system\mods($dbObj);
	$__objResponse = new xajaxResponse();
	$__objResponse->addAssign('mod', 'innerHTML', '<strong>'.$modname.'</strong><br /><br />'.$ob->showModDescr($modname));
	return $__objResponse; 
}

function __deleteMod($url, $modname) {
	$__objResponse = new xajaxResponse();
	$__objResponse->addAssign('mod', 'innerHTML', '<strong>'.$modname.'</strong><br /><br /><a href="'.$url.'">Press to delete current module</a>');
	return $__objResponse; 
}

$_xajax->processRequests();
?>
