<?php
//material-selector.php
//popup used for adding attachments to an article
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\system\material($dbObj);
$ob->getPagedMaterialData($pager); //kdos: url doesn't transport $_GET['matlist']
include('includes/header.php');
?>
<body>
<div id="wrapper">
<div id="loading" class="loading-invisible">
  <p><img src="img/loading.gif" alt=""/></p>
</div>
<script type="text/javascript" src="js/loader.js"></script>
<div class="logo"><h3>MSLeague - Manager<br /><span class="versioninf"><?php print  VERSION; ?></span></h3></div>
<div class="head">
<div class="formDiv">
</div>
</div>
<div class="menuDiv">
<div class="applicationLinks">
</div>
<div class="logoutDiv">
<a href="void(null);" onclick="window.close();">close</a>&nbsp;&nbsp;
</div>
</div>
<div class="content">
<h5>Διαχείρηση υλικού</h5>
<div class="sublinks">
<br />
</div>
<?php
if(!empty($ob->materialdata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['matlist'])?'matlist='.$_GET['matlist'] :''));
?>
<div class="table_div">
<table class="datalist">  
<tr class="imgrow">
<td><strong>Υλικό</strong>&nbsp;&nbsp;</td>
<td><strong>Τύπος</strong></td>
<td><strong>Status</strong></td>
<td><strong>Created</strong></td>
<td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->materialdata); $z++):
?>
<?php
if(empty($isReadonly)):
?>
<tr>
<td><?php print  $ob->materialdata[$z]['name']; ?></td>
<td><?php print  $ob->materialdata[$z]['type']; ?></td>
<td><?php print  $ob->materialdata[$z]['status']; ?></td>
<td><?php print  date('d-M-Y', $ob->materialdata[$z]['created']);?></td>
<td><a href="javascript: void(null);" onclick="xajax___addAttachment(<?php print  $ob->materialdata[$z]['ID']; ?>, '<?php print  ($_GET['matlist'])?$_GET['matlist']:''; ?>');">προσθήκη</a></td>
</tr>
<?php
else:
?>
<tr><td><?php print  $ob->materialdata[$z]['name']; ?></td>
<td><?php print  $ob->materialdata[$z]['type']; ?></td>
<td><?php print  $ob->materialdata[$z]['status'];  ?></td>
<td><?php print  date('d-M-Y', $ob->materialdata[$z]['created']);?></td>
<td><a href="javascript: void(null);" onclick="xajax___addAttachment(<?php print  $ob->materialdata[$z]['ID']; ?>, '<?php print  ($_GET['matlist'])?$_GET['matlist']:''; ?>');">προσθήκη</a></td>
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