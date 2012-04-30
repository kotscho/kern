<?php
//material.php 
//(cathegory)related material
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\gallery($dbObj);

include('includes/header.php');
if(isset($_POST['transfer'])){
	
}
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

<!-- -->
<div class="content">
<h5>Διαχείρηση Galleries</h5>
<div class="sublinks">
<br />
<a href="gallery.php?add=true" title="Εισαγωγή νέου υλικού">Εισαγωγή νέου gallery</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="gallery.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="POST"  name="gallery_form" id="gallery_form" class="galleryform" action="upload.php">
  <fieldset class="userfieldset">
    <legend>Στοιχεία gallery:</legend>
    <label for="gallery_name">Ονομασία Gallery</label>
    <input name="gallery_name" type="text" value="<?php print  $ob->name; ?>"/>
    <br /> <br />
    <label for="uploadedfile">Φωτογραφία</label>
    <div id="fileUpload">You have a problem with your javascript</div>
    <br />
	<label for="upload_links">Λειτουργίες</label>
	<a href="javascript: $('#fileUpload').fileUploadStart()">Έναρξη Upload</a> |  <a href="javascript:$('#fileUpload').fileUploadClearQueue()">Διαγραφή Queue</a>
     <br /><br />
     <?php if(isset($_GET['edit'])):?>
    <label for="type" >MIME Τύπος</label>
    <input type="text" readonly="readonly" value="<?php print  $ob->mimetype; ?>" size="50" name="type"  id="type" /><br /><br />
    <?php endif; ?>
    <label for="created">Created</label>
    <input type="text" name="created" readonly="readonly" value="<?php print  date('Y-m-d', time());?>"/>
    <br /><br />
    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="submit" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Εισαγωγή" onclick="javascript: materialValidator('gallery_form');" />
  </fieldset>
</form>
<?php
endif;
if(!empty($ob->gallerydata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="gallerydatalist" cellspacing="0" >  
<tr class="imgrow">
<td><strong><a href="gallery.php?pager=<?php print  $_GET['pager'].'&ord=NAME'; ?>">Υλικό</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="gallery.php?pager=<?php print  $_GET['pager'].'&ord=CREATED_BY'; ?>">Created by</a></strong></td>
<td><strong><a href="gallery.php?pager=<?php print  $_GET['pager'].'&ord=CREATED'; ?>">Created</a></strong></td>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->gallerydata); $z++):
?>
<?php
if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->gallerydata[$z]['name']; ?></td>
<td><?php print  $ob->gallerydata[$z]['created_by']; ?></td>
<td><?php print  date('d-M-Y', $ob->gallerydata[$z]['created']);?></td>
<td>&nbsp;</td>
<td><a href="material.php?edit=true&amp;id=<?php print  $ob->materialdata[$z]['ID']; ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('gallery',<?php print  $ob->gallerydata[$z]['ID']; ?>);" >διαγραφή</a></td>
</tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->gallerydata[$z]['name']; ?></td>
<td><?php print  $ob->gallerydata[$z]['created_by']; ?></td>
<td><?php print  date('d-M-Y', $ob->gallerydata[$z]['created']);?></td>
<td><a href="gallery.php?edit=true&amp;id=<?php print  $ob->gallerydata[$z]['ID']; ?>">περιήγηση</a></td>
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
<!-- -->
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>