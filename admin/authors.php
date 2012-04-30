<?php
//authors.php 
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\authors($dbObj);

if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
    if($_POST['current_record'] > 0){
         $ob->name = $_POST['name'];
         $ob->whoami = $_POST['whoami'];
         $ob->update($_POST['current_record']);
         header('location: authors.php?completed=true');
        
    }
    else{ //add new record
            $ob->name = $_POST['name'];
            $ob->whoami = $_POST['whoami'];
            $ob->insert();
            header('location: authors.php?completed=true');
    }
}

if(isset($_REQUEST['del']) && empty($isReadonly)){
    $action='del';
    $ob->deleteAuthor($_REQUEST['id']);
    header('location: authors.php?completed=true');
}

if(isset($_REQUEST['edit'])){
	$ob->getAuthorDataById($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'edit';
}
if(isset($_GET['add']))
    $action='add';
if(!isset($_REQUEST['id']))
	$ob->getPagedItems($pager, $_GET['ord']);
include('includes/header.php');
?>
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
<h5>Διαχείρηση συντάκτων</h5>
<div class="sublinks">
<br />
<a href="authors.php?add=true" title="Εισαγωγή νέου συντάκτη">Εισαγωγή νέου συντάκτη</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="authors.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="post"  name="authors_form" id="authors_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία συνταάκτη:</legend>
    <br />
    <label for="name">Όναμα</label>
    <input type="text" name="name" value="<?php print  $ob->name; ?>" />
    <br /><br />
    <label for="whoami" >Περιγραφή</label>
    <textarea name="whoami" id="whoami" cols="20" rows="8"><?php print  $ob->whoami; ?></textarea>
    <br /><br />
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Εισαγωγή" onclick="javascript: materialValidator('authors_form');"/>
  </fieldset>
</form>
<?php
endif;
if(!empty($ob->authordata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="matdatalist" cellspacing="0" >  
<tr class="imgrow">
<td><strong><a href="authors.php?pager=<?php print  $_GET['pager'].'&ord=NAME'; ?>">Όναμα</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="authors.php?pager=<?php print  $_GET['pager'].'&ord=WHOAMI'; ?>">Περιγραφή</a></strong></td>
<td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->authordata); $z++):
?>
<?php
$whoamiChunk = explode(' ', $ob->authordata[$z]['whoami']);
if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->authordata[$z]['name']; ?></td>
<td><?php print  $whoamiChunk[0].'&nbsp;'.$whoamiChunk[1].'&nbsp;'.$whoamiChunk[2].'&nbsp;'.$whoamiChunk[3].'...'; ?></td>
<td><a href="authors.php?edit=true&amp;id=<?php print  $ob->authordata[$z]['id']; ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('authors',<?php print  $ob->authordata[$z]['id']; ?>);" >διαγραφή</a></td>
</tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->authordata[$z]['name']; ?></td>
<td><?php print  $ob->authordata[$z]['whoami']; ?></td>
<td><a href="authors.php?edit=true&amp;id=<?php print  $ob->authordata[$z]['ID']; ?>">περιήγηση</a></td>
</tr>
<?php
endif;
endfor;
?>
</table>
</div>
<?php
endif;
?>
</div>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>