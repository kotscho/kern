<?php
//material.php 
//(cathegory)related material
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\system\material($dbObj);

if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
    if($_POST['current_record'] > 0){//update existing record
         $ob->name = $_POST['materialname'].'.'.$_POST['append'];
         $ob->type = $_POST['type'];
         try{
            $ob->updateMaterial($_POST['current_record'] );
             header('location: material.php?completed=true');
        }
        catch (Exception $e){
            $ob->rollback($oldvalue, $_POST['current_record'] , 'material');
        }
    }
    else{ //add new record
        try{
            $ob->name = $_FILES['uploadedfile']['name'];
            $ob->type = $_FILES['uploadedfile']['type'];
            $ob->created = strtotime($_POST['created']);
            $ob->process($_FILES);
            header('location: material.php?completed=true');
        }
        catch (Exception $e){
            header('location: material.php?err=exception&type=upload&completed=false');
        }
    }
}

if(isset($_REQUEST['del']) && empty($isReadonly)){
     $action='del';
    try{
        $ob->deleteMaterial($_REQUEST['id']);
    }
    catch (Exception $e){ 
        ///print $e->getMessage();
        header('location: material.php?err=exception&type=delete');
    }
    header('location: material.php?completed=true');
}

if(isset($_REQUEST['edit'])){
	$ob->getMaterialDataById($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'edit';
}
if(isset($_GET['add']))
    $action='add';
if(!isset($_REQUEST['id']))
	$ob->getPagedMaterialData($pager, $_GET['ord']);
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
<h5>Διαχείρηση υλικού</h5>
<div class="sublinks">
<br />
<a href="material.php?add=true" title="Εισαγωγή νέου υλικού">Εισαγωγή νέου υλικού</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="material.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="post"  name="material_form" id="material_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία υλικού:</legend>
    <br />
    <label for="uploadedfile">Υλικό</label>
     <?php if(!isset($_GET['edit'])):?>
     <input name="uploadedfile" type="file" style="height: 25px;" id="uploadedfile"/><br /><br />
     <?php else: ?>
     <input name="materialname" readonly="readonly" class="input_material_name" value="<?php print  reset(explode('.',$ob->name)); ?>" type="text" id="materialname" >
     <input type="hidden" name="append" value="<?php print  end(explode('.',$ob->name)); ?>">
     <a href="javascript: void(null);" id="editlink" onclick="setEdit();" >edit</a>
     <br /><br />
     <?php endif;?>
     <?php if(isset($_GET['edit'])):?>
    <label for="type" >Τύπος</label>
    <input type="text" readonly="readonly" value="<?php print  $ob->type; ?>" size="50" name="type"  id="type" /><br /><br />
    <?php endif; ?>
    <label for="created">Created</label>
    <input type="text" name="created" readonly="readonly" value="<?php print  date('Y-m-d', time());?>"/>
    <br /><br />
    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Εισαγωγή" onclick="javascript: materialValidator('material_form');"/>
  </fieldset>
</form>
<?php
endif;
if(!empty($ob->materialdata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="matdatalist" cellspacing="0" >  
<tr class="imgrow">
<td><strong><a href="material.php?pager=<?php print  $_GET['pager'].'&ord=NAME'; ?>">Υλικό</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="material.php?pager=<?php print  $_GET['pager'].'&ord=TYPE'; ?>">Τύπος</a></strong></td>
<td><strong><a href="material.php?pager=<?php print  $_GET['pager'].'&ord=CREATED'; ?>">Created</a></strong></td>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->materialdata); $z++):
?>
<?php
if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->materialdata[$z]['name']; ?></td>
<td><?php print  $ob->materialdata[$z]['type']; ?></td>
<td><?php print  date('d-M-Y', $ob->materialdata[$z]['created']);?></td>
<td>&nbsp;</td>
<td><a href="material.php?edit=true&amp;id=<?php print  $ob->materialdata[$z]['ID']; ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('material',<?php print  $ob->materialdata[$z]['ID']; ?>);" >διαγραφή</a></td>
</tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->materialdata[$z]['name']; ?></td>
<td><?php print  $ob->materialdata[$z]['type']; ?></td>
<td><?php print  date('d-M-Y', $ob->materialdata[$z]['created']);?></td>
<td><a href="material.php?edit=true&amp;id=<?php print  $ob->materialdata[$z]['ID']; ?>">περιήγηση</a></td>
<td>&nbsp;</td>
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