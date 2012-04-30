<?php
error_reporting(0);
require_once('functions/adminFunctions.inc.php');
require("xajax.common.php");
is_alive();
$ob = new classes\system\application_nodes($dbObj);
$obs = new classes\system\application_nodes_stages();

switch($_GET['action']){
	case 'edit': 
		$current_id = explode('_', $_GET['id']);
        header('location: application_nodes.php?edit=true&id='.$current_id[1]);
		break;
	case 'delete':
        if(empty($isReadonly))
            $ob->deleteNode($_GET['id']);
		break;
    case 'choose':
        $new_parent = explode('_', $_GET['id']);
        //die($new_parent[1]);
        if(isset($_GET['nodeid'])){
            $ob->changeRelation($_GET['nodeid'], $new_parent[1]);
            $ob->getNodeByID($_GET['nodeid']);
        }
        else{
        }
        
    break;
	default:
		break;
}

if(isset($_GET['edit']))
    $ob->getNodeByID($_GET['id']);
    
if(isset($_GET['edit']) || isset($_POST['passed_id']) || isset($_GET['nodeid'])){
    $nc = new classes\src\content_application_nodes($dbObj);
    if(isset($_GET['nodeid']))
    	$nc->getByID($_GET['nodeid']);
    else
    	$nc->getByID(($_GET['id'])?$_GET['id']:$_POST['passed_id']);
}

if(isset($_REQUEST['transfer']) ){
    $ob->ID = $_POST['passed_id'];
	$ob->status = 'pub';
	$ob->parent_ID = $_POST['passed_parentid'];
    $start=explode('/',$_POST['datepicker']);
    $end=explode('/',$_POST['datepicker_to']);
	$ob->startdate = strtotime($start[2].'/'.$start[1].'/'.$start[0]);
	$ob->enddate = strtotime($end[2].'/'.$end[1].'/'.$end[0]);
	$ob->name = $_POST['name'];
    $oldDescription = $ob->description;
	$ob->description = $_POST['description'];
	$ob->type = $_POST['type'];
	$ob->stagename = $_POST['stagename'];
    if(isset($_POST['apply_to_childs']))
    	$ob->assignAccesstype($_POST['passed_id'], $_POST['type']);
	
    if(!empty($_REQUEST['passed_id']) ){
        $_SESSION['datepicker']=$start[2].'/'.$start[1].'/'.$start[0];
        $_SESSION['datepicker_to']=$end[2].'/'.$end[1].'/'.$end[0];
        if($ob->updateNode($_POST['passed_id'])){ 
            if($oldDescription != $_POST['description'])
                include('mod_rewrite.php');//if update succeeed, and new description available: write new rewrite rule/category description to .htaccess
            if(is_array($_POST['active_articles'])){
                if(is_array($nc->articles)){
                    for($z=0; $z<count($nc->articles); $z++){
                        if(!in_array($nc->articles[$z]['id'], $_POST['active_articles']))
                            $nc->deleteSpecificByID($nc->articles[$z]['id'], $_POST['passed_id']);
                    }
                }
                for($z=0; $z<count($_POST['active_articles']); $z++){
                    if(!in_array($_POST['active_articles'][$z], $nc->articles)) 
                        $nc->assignArticle($_POST['active_articles'][$z], $_POST['passed_id']);
                }
            }
            if(!isset($_POST['active_articles']))
                $nc->deleteAllByID($_POST['passed_id']);
        }
    }
    else{
        if($ob->insertNode() ){
            include('mod_rewrite.php');//if insert succeeded: write new rewrite rule/category description to .htaccess
            if(!empty($_POST['active_articles'])){
                $lastCID=application_nodes_helper::lastInsertedID();
                if(is_array($_POST['active_articles'])){
                    for($z=0; $z<count($_POST['active_articles']); $z++)
                        $nc->assignArticle($_POST['active_articles'][$z], $lastCID);
                }
                else{
                    $nc->assignArticle($_POST['active_articles'], $lastCID);
                }
            }
        }
    }
}

if(isset($_REQUEST['add']) || isset($_REQUEST['transfer']))
    unset($_SESSION['name'], $_SESSION['description'], $_SESSION['datepicker'] , $_SESSION['datepicker_to'] );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="el">
<head profile="http://gmpg.org/xfn/11">
<title>Application Nodes</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="el" />
<meta name="language" content="el" />
<meta name="author" content="kern.gr" />
<meta name="copyright" content="Copyright 2009, kern, All rights reserved" />
<meta name="owner" content="kern.gr" />
<meta name="description" content="menu test" />
<link rel="stylesheet" href="css/jquery.treeview.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/loader.css" />
<link rel="stylesheet" type="text/css" href="css/adminstyles.css" />
<link type="text/css" href="css/jquery-ui-1.7.1.custom.css" rel="stylesheet" />
<script src="js/jquery-1.4.2.min.js" type="text/javascript"/></script>
<script src="js/jquery.cookie.js" type="text/javascript"/></script>
<script src="js/jquery.treeview.js" type="text/javascript"/></script>
<script src="js/jquery.treeview.async.js" type="text/javascript"/></script>
<script type="text/javascript" src="js/ui.core.js"></script>
<script type="text/javascript" src="js/ui.datepicker.js"></script>
<script src="js/misc.js" type="text/javascript"></script>
<script src="js/jquery.contextMenu.js" type="text/javascript"></script>
<script src="js/jquery.livequery.js" type="text/javascript"></script>
<link href="css/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	$(document).ready(function(){
       
		$("#black").treeview({
			animated: "fast",
			control:"#sidetreecontrol",
			url: "source.php"
		});
		$('ul li').livequery(function(){
			$('span').contextMenu({
				menu: 'myMenu'
			}, 
			function(action, el, pos) {triggerAction(action, $(el).attr('id'), $(el).text()); });
        });
		
	});
	function triggerAction(action, id, txt){
		var answer;
		if(action == "cancel")
			return;
		if(action == "delete"){
			answer = confirm('Διαγραφή κατηγορίας -'+ txt +'- και των παιδιών της... \nΕίσαστε σιγουρός;');
			if(!answer)
				return;
		}
		location.href = 'application_nodes.php?action='+action+'&id='+id;
	}
	</script>
<script type="text/javascript">
	$(function() {
		$("#datepicker").datepicker();
		$("#datepicker_to").datepicker();
	});
</script>
<script type="text/javascript">
$(function(){
$('#infobox').tooltip();
});
</script>
<?php 
if(is_object( $_xajax)){
	print $_xajax->printJavascript('xajax'); 
}
?>
</head>
<body>
<div id="wrapper">
<div id="loading" class="loading-invisible">
  <p><img src="img/loading.gif" alt=""/></p>
</div>
<script type="text/javascript" src="js/loader.js"></script>
<div class="logo"><h3><?php print  VENDOR; ?> - Manager<br /><span class="versioninf"><?php print  VERSION; ?></span></h3></div>
<div class="head">
<div class="formDiv">
</div>
</div>
<div class="menuDiv">
<div class="applicationLinks">
<?php
include('includes/menu.php');
?>
</div>
<div class="logoutDiv">
<a href="index.php?logout=true">logout</a>&nbsp;&nbsp;
</div>
</div>
<div class="content">
<h5>Διαχείρηση Κατηγοριών</h5>
<div class="sublinks">
<br />
<a href="application_nodes.php?add=true" title="Εισαγωγή νέας κατηγορίας">Εισαγωγή νέας κατηγορίας</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):

?>
&nbsp;|&nbsp;<a href="application_nodes.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
<br /><br />
<!-- -->
<?php
if(!isset($_GET['add']) && !isset($_GET['edit']) && !isset($_GET['del']) && !isset($_GET['nodeid']) && $_GET['action'] != 'choose'):
?>
<div id="sidetreecontrol"><a href="?#">Collapse All</a> | <a href="?#">Expand All</a></div>
<div id="main">
<ul id="black"></ul>
</div>
<ul id="myMenu" class="contextMenu">
    <li class="edit"><a href="#edit">Επεξεργασία</a></li>
    <?php
    if(empty($isReadonly)):
    ?>
    <li class="delete"><a href="#delete">Διαγραφή</a></li>
    <?php
    endif;
    ?>
    <li class="quit separator"><a href="#cancel">Ακύρωση</a></li>
 </ul>
<?php
endif;
?>
<!-- -->
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['nodeid']) || $_GET['action'] == 'choose'):
	$parent=application_nodes_helper::getNameByID((empty($new_parent[1]))?$ob->parent_ID:$new_parent[1])
?>
<form method="post"  name="application_nodes_form" id="application_nodes" class="mainform" action="<?php print  $_SERVER['PHP_SELF'];?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία κατηγορίας:</legend>
    <br />
    <label for="firstname">Όνομα</label>
    <input type="text" value="<?php print  (!empty($ob->name))?$ob->name:$_SESSION['name']; ?>" size="50" name="name" id="name"/><br /><br />
    <label for="description">Περιγραφή</label>
    <input type="text" value="<?php print  (!empty($ob->description))?$ob->description:$_SESSION['description']; ?>" size="50" name="description"  id="description" /><br /><br />  
    <label for="username">Parent</label>
    <input type="text" id="parenttype" readonly="readonly" value="<?php print  (!empty($parent)?$parent:'no parent'); ?>" size="50" name="parenttype" />
    <a href="javascript: void(0);" 
   onclick="xajax___setTempSession(document.application_nodes_form.name.value, document.application_nodes_form.description.value, document.application_nodes_form.datepicker.value, document.application_nodes_form.datepicker_to.value); pop(<?php if(!empty($ob->ID) ):?>'application_nodes-selector.php?nodeid=<?php print  (isset($_GET['nodeid'])?$_GET['nodeid']:$_GET['id']); ?>'); 
    <?php else: ?> 'application_nodes-selector.php');<?php endif; ?>">
   αλλαγή</a>&nbsp;η&nbsp;<a href="javascript: void();"  onclick="document.getElementById('parenttype').value = 'no parent'; document.getElementById('passed_parentid').value = 0";>"no parent" </a>
    <br /><br/>
    <label for="articleselector">Επιλογή άρθρου</label>
    <input type="text" readonly="readonly" id="articleselector" name="articleselector" 
    onclick="pop('content-selector.php<?php print  (($_GET['id']) ? ('?id='.$_GET['id']):('?id=0') );?>' + contentIDCollector() );" value="κάντε κλικ εδώ" />
    <br /><br />
     <div id="articleradios" style="display: <?php print  (is_array($nc->articles)?'block':'none')?>; border: 1px solid #aaaaaa; width: 250px; height: 80px; overflow: scroll; margin-left: 106px;">
    <?php
    if(is_array($nc->articles)):
        for($c=0; $c<count($nc->articles); $c++):
    ?>
        <input style="width: 10px;" type="checkbox" name="active_articles[]" value="<?php print  $nc->articles[$c]['id']; ?>" checked />&nbsp;<?php print  '<a href="content.php?edit=true&amp;id='.$nc->articles[$c]['id'].'" class="attached-articles">'.$nc->articles[$c]['title'].'</a>'; ?><br />
    <?php 
        endfor;
    endif;
    ?>
    </div>
    <label for="stagename">Html stage</label>
    <?php print  $obs->stagesSelect($ob->stagename);  ?>
    <br />
    <label for="mods"><a href="javascript: void(null);" onclick="pop('mod-selector.php?id=<?php print  $_GET['id']; ?>');">Assigned Modules</a></label>
    <div style="height: 13px; text-align: left;  margin-top: 3px;"><?php print  $ob->showModNames($_GET['id']);  ?></div>
    <br />
    <br />
     <label for="type">Accessabillity type</label>
    <?php print  application_nodes_helper::setNodeType($ob->type);?>
    <br />
    <br />
    <label for="apply_to_childs">Apply type to childs</label>
    <input style="width: 12px;" name ="apply_to_childs" type="checkbox" checked />
      <br />
    <br />
    <label for="datepicker">Από</label>
    <input id="datepicker" name="datepicker" value="<?php print  (empty($_SESSION['datepicker']))?date('d/m/Y', (empty($ob->startdate)?time():$ob->startdate)):$_SESSION['datepicker']; ?>" type="text" /><br /><br />
    <label for="datepicker_to">Εώς</label>
    <input id="datepicker_to" name="datepicker_to" value="<?php print  (empty($_SESSION['datepicker_to']))?date('d/m/Y', (empty($ob->enddate)?time():$ob->enddate)):$_SESSION['datepicker_to']; ?>" type="text" /><br /><br />
    <input type="hidden" name="passed_id" value="<?php print  $ob->ID; ?>" />
    <input type="hidden" id="passed_parentid" name="passed_parentid" value="<?php print  (empty($new_parent[1]))?$ob->parent_ID:$new_parent[1]; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <input type="button" <?php print  (!empty($isReadonly))?'disabled="disabled"':''; ?> name="submitbutton" class="input_button" onclick="javascript: validator('application_nodes');" value="Εισαγωγή" />
   </fieldset>
</form>
<?php
endif;
?>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>