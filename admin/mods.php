<?php
//mods.php 
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\system\mods($dbObj);
$ob->renderAdminSection('searchbox');
if(isset($_POST['transfer']))
	$ob->install($_FILES);
	
if(isset($_REQUEST['action']) && !isset($_REQUEST['complete']))
	$ob->{$_REQUEST['action']}($_REQUEST['params']);
	
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
<h5>Διαχείρηση mods</h5>
<div class="sublinks">
<br />
</div>
<form enctype="multipart/form-data" method="post"  name="mods_form" id="mods_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="userfieldset">
    <legend>Upload new mod</legend>
    <br />
    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
    <input type="file" style="height: 25px; margin-left: 5px;" name="uploadedfile" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Εισαγωγή" onclick="javascript: materialValidator('mods_form');"/>
  </fieldset>
</form>
</div>
<?php 
if(!empty($ob->modsdata)):
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="mod_table_div">
<table class="matdatalist" cellspacing="0" id="mod_data">  
<tr class="imgrow">
<td><strong><a href="mods.php?pager=<?php print  $_GET['pager'].'&ord=name'; ?>">Name</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="mods.php?pager=<?php print  $_GET['pager'].'&ord=status'; ?>">Status</a></strong></td>
<td><strong><a href="mods.php?pager=<?php print  $_GET['pager'].'&ord=author'; ?>">Author</a></strong></td>
<td><strong><a href="mods.php?pager=<?php print  $_GET['pager'].'&ord=version'; ?>">Version</a></strong></td>
<td><strong><a href="javascript: void(null);">Config</a></strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->modsdata); $z++):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->modsdata[$z]['name']; ?></td>
<td><?php print  $ob->modsdata[$z]['status']; ?></td>
<td><?php print  $ob->modsdata[$z]['author']; ?></td>
<td><?php print  $ob->modsdata[$z]['version']; ?></td>
<td><?php print  (!empty($ob->modsdata[$z]['conf'])?'<a class="mod-config" href="#mod" onclick="xajax___renderAdminSection(\''.$ob->modsdata[$z]['name'].'\')" title="edit the configuration">config</a>':'none'); ?></td>
<td style="text-align: right;">
<a class="mod-config" href="#mod" onclick="xajax___renderDescription(<?php print  '\''.$ob->modsdata[$z]['name'].'\''?> ) ">about&nbsp;<?php print  $ob->modsdata[$z]['name'];?></a>
&nbsp;&nbsp;
<a class="mod-config" href="#mod" onclick="xajax___deleteMod('mods.php?action=delete&amp;params=<?php print   $ob->modsdata[$z]['name']; ?>', <?php print  '\''.$ob->modsdata[$z]['name'].'\''?>)"><img align="top" border=0 src="img/delete.png" title="delete" /></a>

<?php if($ob->modsdata[$z]['status'] == 'installed'): ?>
&nbsp;&nbsp;
<a href="mods.php?action=uninstall&amp;params=<?php print   $ob->modsdata[$z]['name']; ?>"><img align="top" border=0 src="img/bin.png" title="uninstall" /></a>
<?php else: ?> 
&nbsp;&nbsp;
<a href="mods.php?action=reinstall&amp;params=<?php print   $ob->modsdata[$z]['name']; ?>"><img align="top" border=0 src="img/install.png" title="reinstall" /></a>
<?php endif; ?>

&nbsp;&nbsp;
<?php print  (!empty($ob->modsdata[$z]['conf'])?'<a class="mod-config" href="#mod" onclick="xajax___renderAdminSection(\''.$ob->modsdata[$z]['name'].'\')"><img border=0 align="top" src="img/edit.png" title="edit the configuration" /></a>':'none'); ?></td>
</td>
<td>&nbsp;</td>
</tr>
<?php 
endfor;
endif;
?>
</table>
</div>
<?php
include('includes/footer.php');
?>
<div style="display: none;">
<div id="mod"></div>
</div>
</div>
</body>
</html>