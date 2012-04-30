<?php
//newsletter.php 
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
include("fckeditor/fckeditor.php");
require("xajax.common.php");
is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\newsletter($dbObj);
$oFCKeditor = new FCKeditor('FCKeditor1') ;
$oFCKeditor->BasePath	= 'fckeditor/';
if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
    $ob->title = $_POST['title'];
    $ob->content = $_POST['FCKeditor1'];
    $ob->sent = $_POST['sent'];
    if($_POST['current_record'] > 0){
         $ob->update($_POST['current_record']);
         header('location: newsletter.php?completed=true');
    }
    else{
            $ob->created = time();
            $ob->insert();
            header('location: newsletter.php?completed=true');
    }
}

if(isset($_REQUEST['del']) && empty($isReadonly)){
    $action='del';
    $ob->deleteNewsletter($_REQUEST['id']);
    header('location: newsletter.php?completed=true');
}

if(isset($_REQUEST['edit'])){
	$ob->getNewsletterDataById($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'edit';
}
if(isset($_GET['add']))
    $action='add';
if(!isset($_REQUEST['id']))
	$ob->getPagedItems($pager, $_GET['ord']);
if(isset($_REQUEST['sendmail'])){
   newsletterHelpers::sendNewsletter(4, 'tpl/newsletter.tpl.html');
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
<?php
include('includes/menu.php');
?>
</div>
<div class="logoutDiv">
<a href="index.php?logout=true">logout</a>&nbsp;&nbsp;
</div>
</div>
<div class="content">
<h5>Διαχείρηση newsletter</h5>
<div class="sublinks">
<br />
<a href="newsletter.php?add=true" title="Εισαγωγή νέου newsletter">Εισαγωγή νέου newsletter</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="newsletter.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="post"  name="newsletter_form" id="newsletter_form" class="fck_form" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="fck_fieldset">
    <legend>Στοιχεία newsletter:</legend>
    <br />
    <label for="title">Τίτλος</label>
    <input type="text" class="input_newslettertitle" name="title" value="<?php print  $ob->title; ?>" />
    <br /><br />
    <label for="content" >Άρθρο(e-mail)</label>
    <?php
    $oFCKeditor->Value = (!empty($_POST['FCKeditor1']) ? $_POST['FCKeditor1'] : !empty($ob->content)?$ob->content:'Your text here...') ;
    $oFCKeditor->Width = 600;
    $oFCKeditor->Height = 500;
    $oFCKeditor->Create();
    ?>
<br /><br />
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Εισαγωγή" onclick="javascript: materialValidator('newsletter_form');"/>
  </fieldset>
</form>
<?php
endif;
if(!empty($ob->newsletterdata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="matdatalist" cellspacing="0" >  
<tr class="imgrow">
<td><strong><a href="newsletter.php?pager=<?php print  $_GET['pager'].'&ord=TITLE'; ?>">Τίτλος</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="newsletter.php?pager=<?php print  $_GET['pager'].'&ord=CREATED'; ?>">Δημιουργήθηκε</a></strong></td>
<td><strong>Αποστολή</strong></td>
<td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->newsletterdata); $z++):
?>
<?php
if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->newsletterdata[$z]['title']; ?></td>
<td><?php print  date("d-m-Y", $ob->newsletterdata[$z]['created']); ?></td>
<td><?php print  ($ob->newsletterdata[$z]['sent'] == 'Y')?'<u>έχει πραγματοποιηθεί</u>' : '<a href="newsletter.php?sendmail=true&nid='.$ob->newsletterdata[$z]['id'].'">αποστολή</a>' ?></td>
<td><a href="newsletter.php?edit=true&amp;id=<?php print  $ob->newsletterdata[$z]['id']; ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('newsletter',<?php print  $ob->newsletterdata[$z]['id']; ?>);" >διαγραφή</a></td>
</tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->newsletterdata[$z]['title']; ?></td>
<td><?php print  $ob->newsletterdata[$z]['cerated']; ?></td>
<td><a href="newsletter.php?edit=true&amp;id=<?php print  $ob->newsletterdata[$z]['ID']; ?>">περιήγηση</a></td>
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