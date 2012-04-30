<?php
error_reporting(0);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\content($dbObj);
$ob->getPagedItems($pager); 
include('includes/header.php');
?>
<body>
<div id="wrapper">
<div id="loading" class="loading-invisible">
  <p><img src="img/loading.gif" alt=""/></p>
</div>
<script type="text/javascript" src="js/loader.js"></script>
<div class="logo"><h3><?php print  VEMDOR; ?> - Manager<br /><span class="versioninf"><?php print  VERSION; ?></span></h3></div>
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
<h5>Διαχείρηση content</h5>
<div class="sublinks">
<br />
</div>
<?php
if(!empty($ob->contentdata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['contentlist'])?'matlist='.$_GET['contentlist'] :''));
?>
<div class="article_selector_table_div">
<table class="datalist">  
<tr class="imgrow">
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=TITLE'; ?>">Τίτλος Άρθρου</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=STATUS'; ?>">Κατάσταση</a></strong></td>
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=CREATED_BY'; ?>">Συντάκτης</a></strong></td>
<td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->contentdata); $z++):
if($ob->contentdata[$z]['status'] == (int)1)://only pubished articles are selectable
?>
<tr>
<td><?php print  $ob->contentdata[$z]['title']; ?><?php print  contentHelper::getAttachments($ob->contentdata[$z]['ID']); ?></td>
<td><?php print  $statusDescr[$ob->contentdata[$z]['status']]; ?></td>
<td><?php print  contentHelper::getAuthor($ob->contentdata[$z]['created_by']); ?></td>
<td><a href="javascript: void(null);" onclick="xajax___addContent(<?php print  $ob->contentdata[$z]['ID']; ?>, '<?php print  ($_GET['contentlist'])?$_GET['contentlist']:''; ?>');">προσθήκη</a></td>
</tr>
<?
endif;
endfor; ?>
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