<?php
error_reporting(0);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require_once(CLASS_PATH.SYS_FOLDER."class.paging.php");//again man...same as user
require("xajax.common.php");
is_alive();
$mod = new classes\system\mods($dbObj);
$an = new classes\system\application_nodes($dbObj);
if(isset($_POST)){
	foreach($_POST as $k => $v){
		if(strstr($k, 'mod_')!==FALSE)
			$modarray[]=$v;
	}
	$an->assignMods($_GET['id'], $modarray);
	
}
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
</div>
<div class="logoutDiv">
<a href="void(null);" onclick="window.close();">close</a>&nbsp;&nbsp;
</div>
</div>
<div class="content">
<h5>Διαχείρηση mods</h5>
<div class="sublinks">
<br />
</div>
<div class="article_selector_table_div">
<form method="post" id="modform" name="modform" action="mod-selector.php?id=<?php print  $_GET['id']; ?>">
<table class="datalist">  
<tr class="imgrow">
<td>Available Mods
</td>
</tr>
<tr>
<td><?php echo $mod->getAllMods($_GET['id']);?></td>
</tr>
<tr><td><input type="button" style="height: 25px;"  value="add" name="mysubmit" onclick="refParent('modform'); self.close();"/></td></tr>
</table>
</form>
</div>
</div>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>