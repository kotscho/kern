<?php
//links.php
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\system\categ_units($dbObj);

if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
    if($_POST['current_record'] > 0){
         $ob->name = $_POST['name'];
         $ob->updated = time();
         $ob->info = $_POST['info'];
         $ob->update($_POST['current_record']);
         header('location: categ-units.php?completed=true');

    }
    else{ //add new record
            $ob->name = $_POST['name'];
            $ob->updated = time();
            $ob->info = $_POST['info'];
            $ob->created = time();
            $ob->insert();
            header('location: categ-units.php?completed=true');
    }
}

if(isset($_REQUEST['del']) && empty($isReadonly)){
    $action='del';
    $ob->deleteLink($_REQUEST['id']);
    header('location: categ-units.php?completed=true');
}

if(isset($_REQUEST['edit'])){
	$ob->getcateg_units_dataById($_REQUEST['id']);
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
<h5>Διαχείρηση Categ Units</h5>
<div class="sublinks">
<br />
<a href="categ-units.php?add=true" title="Εισαγωγή νέου unit">Εισαγωγή νέου unit</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="categ-units.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="post"  name="authors_form" id="authors_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία unit:</legend>
    <br />
    <label for="name">Όναμα</label>
    <input type="text" name="name" value="<?php print  $ob->name; ?>" />
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
if(!empty($ob->categ_units_data) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="matdatalist" cellspacing="0" >
<tr class="imgrow">
<td><strong><a href="categ-units.php?pager=<?php print  $_GET['pager'].'&ord=NAME'; ?>">Όναμαη</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="categ-units.php?pager=<?php print  $_GET['pager'].'&ord=INFO'; ?>">Πληροφορία</a></strong></td>
<td><strong><a href="categ-units.php?pager=<?php print  $_GET['pager'].'&ord=CREATED'; ?>">Δημιουργήθηκε</a></strong></td>
<td>&nbsp;</td>

</tr>
<?php
for($z=0; $z<count($ob->categ_units_data); $z++):
?>
<?php

if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->categ_units_data[$z]['name']; ?></td>
<td><?php print  $ob->categ_units_data[$z]['info']; ?></td>
<td><?php print  $grdate[date('w',$ob->categ_units_data[$z]['created'])].' ,'.date('d-m-Y', $ob->categ_units_data[$z]['created']) ; ?></td>
<td><a href="categ-units.php?edit=true&amp;id=<?php print  $ob->categ_units_data[$z]['id']; ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('categ-unit',<?php print  $ob->categ_units_data[$z]['id']; ?>);" >διαγραφή</a></td>

<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->categ_units_data[$z]['name']; ?></td>
<td><?php print  $ob->categ_units_data[$z]['info']; ?></td>
<td><?php print  $ob->categ_units_data[$z]['created']; ?></td>
<td><a href="categ-units.php?edit=true&amp;id=<?php print  $ob->categ_units_data[$z]['id']; ?>">περιήγηση</a></td>

<?php
endif;
?>
<td>
<?php
if(categ_units_helper::hasEntries($ob->categ_units_data[$z]['id'])):
?>
<a href="categ.php?unit_id=<?php print $ob->categ_units_data[$z]['id']; ?>">edit entries</a>
<?php
else:
?>
<a href="categ.php?new_unit_id=<?php print $ob->categ_units_data[$z]['id']; ?>">add entries</a>
<?php
endif;
?>

</td>
<?php

endfor;
?>

</tr>
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