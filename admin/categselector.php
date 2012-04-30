<?php
error_reporting(0);
require_once('functions/adminFunctions.inc.php');
$ob = new classes\system\application_nodes($dbObj);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="el">
<head profile="http://gmpg.org/xfn/11">
<title>Categories</title>
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
        if(id == '<?php print "spid_".$_GET['nodeid']; ?>'){
            alert("Copying a node into itself is nonsense");
            return; 
        }
        
		if(action == "cancel")
			return;
        if(action == "choose"){
            
            self.close();
            opener.location.href = 'categ.php?action=choose&id='+id+
            <?php if(!empty($_GET['nodeid'])): ?>
            '&nodeid='+ <?php print $_GET['nodeid']; ?>; 
            <?php else: ?>
            '';
            <?php endif; ?>
           //kotscho here: next step
        }
            
		
       //location.href = 'categ.php?action='+action+'&id='+id;
	}
	</script>
<script type="text/javascript">
	$(function() {
		$("#datepicker").datepicker();
		$("#datepicker_to").datepicker();
	});
</script>
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
</div>
<div class="logoutDiv">
<a href="javascript: self.close(); window.opener.location.reload();" title="Επιστροφή">close</a>
&nbsp;
</div>
</div>
<div class="content">
<h5>Διαχείρηση Κατηγοριών <br />
<?php if(isset($_GET['nodeid'])):?>
[αλλαγη "parent" της κατηγορίας: <i><?php print  application_nodes_helper::getNameByID($_GET['nodeid'] );?></i>]
<?php endif; ?>
</h5>  
<div class="sublinks">
<br />
<br /><br />
<div id="sidetreecontrol"><a href="?#">Collapse All</a> | <a href="?#">Expand All</a></div>
<div id="main" style="height: 450px; overflow: scroll;">
<ul id="black"></ul>
 </div>
<ul id="myMenu" class="contextMenu">
    <li class="edit"><a href="#choose">Nέο "parent"</a></li>
    <li class="quit separator"><a href="#cancel">Ακύρωση</a></li>
 </ul>

</div>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>
