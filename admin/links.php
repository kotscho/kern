<?php
//links.php 
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\links($dbObj);

if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
    if($_POST['current_record'] > 0){
         $ob->vendor = $_POST['vendor'];
         $ob->url = $_POST['url'];
         $ob->info = $_POST['info'];
         $ob->update($_POST['current_record']);
         header('location: links.php?completed=true');
        
    }
    else{ //add new record
            $ob->vendor = $_POST['vendor'];
            $ob->url = $_POST['url'];
            $ob->info = $_POST['info'];
            $ob->created = time();
            $ob->insert();
            header('location: links.php?completed=true');
    }
}

if(isset($_REQUEST['del']) && empty($isReadonly)){
    $action='del';
    $ob->deleteLink($_REQUEST['id']);
    header('location: links.php?completed=true');
}

if(isset($_REQUEST['edit'])){
	$ob->getLinkDataById($_REQUEST['id']);
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
<a href="links.php?add=true" title="Εισαγωγή νέου συντάκτη">Εισαγωγή νέου σύνδεσμου</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="links.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="post"  name="authors_form" id="authors_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία σύνδεσμου:</legend>
    <br />
    <label for="vendor">Όναμα/Οργάνωση</label>
    <input type="text" name="vendor" value="<?php print  $ob->vendor; ?>" />
    <br /><br />
    <label for="url" >Σύνδεσμος</label>
    <textarea name="url" id="url" cols="32" rows="4"><?php print  $ob->url; ?></textarea>
    <br /><br />
    <label for="url" >Πλυροφορίες</label>
    <textarea name="info" id="info" cols="32" rows="2"><?php print  $ob->info; ?></textarea>
    <br /><br />
    <?php 
    if(!empty($ob->created) ): 
    ?>
    <label for="created">Δημιουργήθηκε</label>
    <input type="text" readonly="readonly" value="<?php print  $grdate[date('w', $ob->created)].', '.date('d-m-Y', $ob->created);?>" name="created" id="created" />
    <br /><br />
    <?php
    endif;
    ?>
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Εισαγωγή" onclick="javascript: materialValidator('authors_form');"/>
  </fieldset>
</form>
<?php
endif;
if(!empty($ob->linkdata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="matdatalist" cellspacing="0" >  
<tr class="imgrow">
<td><strong><a href="links.php?pager=<?php print  $_GET['pager'].'&ord=VENDOR'; ?>">Όναμα/Οργάνωση</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="links.php?pager=<?php print  $_GET['pager'].'&ord=URL'; ?>">Σύνδεσμος</a></strong></td>
<td><strong><a href="links.php?pager=<?php print  $_GET['pager'].'&ord=CREATED'; ?>">Δημιουργήθηκε</a></strong></td>
<td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->linkdata); $z++):
?>
<?php

if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->linkdata[$z]['vendor']; ?></td>
<td><?php print  $ob->linkdata[$z]['url']; ?></td>
<td><?php print  $grdate[date('w',$ob->linkdata[$z]['created'])].' ,'.date('d-m-Y', $ob->linkdata[$z]['created']) ; ?></td>
<td><a href="links.php?edit=true&amp;id=<?php print  $ob->linkdata[$z]['id']; ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('links',<?php print  $ob->linkdata[$z]['id']; ?>);" >διαγραφή</a></td>
</tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->linkdata[$z]['vendor']; ?></td>
<td><?php print  $ob->linkdata[$z]['url']; ?></td>
<td><?php print  $ob->linkdata[$z]['created']; ?></td>
<td><a href="links.php?edit=true&amp;id=<?php print  $ob->linkdata[$z]['id']; ?>">περιήγηση</a></td>
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