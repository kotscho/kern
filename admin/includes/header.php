<?php
// common header
//the magick installer directive will redirect you to a form/script which you can use to add 
//new admin menu items
if($_REQUEST['magick_param'] == 'true' && ($_SESSION['group'] == 1)){
	header('location: admin_installer.php');
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="el">
<head>
<title><?php print  VENDOR; ?> - Manager</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="copyright" content="Copyright 2011 webtrigger, All rights reserved" />
<meta name="description" content="kern cms" />
<link rel="stylesheet" type="text/css" href="css/loader.css" />
<link rel="stylesheet" type="text/css" href="css/adminstyles.css" />
<link type="text/css" href="css/jquery-ui-1.7.1.custom.css" rel="stylesheet" />
<link rel="stylesheet" href="js/jquery-tooltip/jquery.tooltip.css" type="text/css" media="screen" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<script type="text/javascript" src="js/misc.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>

<script type="text/javascript" src="js/ui.core.js"></script>
<script type="text/javascript" src="js/ui.datepicker.js"></script>


<script type="text/javascript">
	$(function() {
		$("#datepicker").datepicker();
		$("#datepicker_to").datepicker();
		$("#datepicker_inv").datepicker();
		$("#datepicker_inv_list").datepicker();
        $("#datepicker_birthday").datepicker();
        $("#datepicker_member").datepicker();
        $("#datepicker_not_ordinary_open").datepicker();
        $("#datepicker_not_ordinary_close").datepicker();
        $("#datepicker_mts_open_date").datepicker(); //mts stands for manager team setup
        $("#datepicker_mts_close_date").datepicker();
        $("#datepicker_match_setup").datepicker();
        $("#datepicker_injured_from").datepicker();
        $("#datepicker_injured_to").datepicker();
     	
	});
</script>
<?php 
if(is_object( $_xajax)){
	//$_xajax->setFlag('debug',true);
	print $_xajax->printJavascript('xajax'); 
}
?>
<?php
if((basename($_SERVER['PHP_SELF']) == 'members.php') || (basename($_SERVER['PHP_SELF']) == 'mods.php')):
?>
<link rel="stylesheet" href="js/fancybox/jquery.fancybox-1.3.1.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/anytime.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/anytime.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<script type="text/javascript" src="js/anytimetz.js"></script>


<script type="text/javascript">
$(document).ready(function() {
	$("a#inline").fancybox({
		'padding': 3,
		'hideOnContentClick': false
	});
	$(".mod-config").fancybox({
		'padding': 3,
		'hideOnContentClick': false
	});
	
	
});
$(function() {
	 $("#not_ordinary_open_explicit_time").AnyTime_picker({ 
      	format: "%H:%i", labelTitle: "Non ordinary time",
        labelHour: "Our", labelMinute: "Minute" } );
        
});
</script>
<?php
endif;
if(basename($_SERVER['PHP_SELF']) == 'gallery.php'):
?>
<link rel="stylesheet" href="css/uploadify.css" type="text/css" />
<script type="text/javascript" src="js/jquery.uploadify.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#fileUpload").fileUpload({
		'uploader': 'js/uploadify/uploader.swf',
		'cancelImg': 'js/uploadify/cancel.png',
		'script': 'upload.php',
		'folder': '../galleries',
		'fileDesc': 'Image Files',
		'buttonText': 'browse files',
		'fileExt': '*.jpg;*.jpeg;*.gif;*.png',
		'multi': true,
		'auto': false
	});
});

</script>
<script type="text/javascript">
	function startUpload(id, additional){
	    $('#fileUpload').fileUploadSettings('scriptData','&galname='+additional);
	    $('#fileUpload').fileUploadStart();
		//startUpload('fileUpload', document.gallery_form.gallery_name.value)
	    
	}
</script> 
<?php
endif;
?>
</head>