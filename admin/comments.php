<?php
//comments.php 
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require_once(CLASS_PATH.SYS_FOLDER."class.paging.php");
require_once(CLASS_PATH."class.members.php");
require_once(CLASS_PATH."class.comments.php");
require("xajax.common.php");
is_alive();

$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\comments($dbObj);

include('includes/header.php');

if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
    if($_POST['current_record'] > 0){
         $ob->toggleCommentStatus($_POST['current_record'], $_POST['statusselect']);
         header('location: comments.php?completed=true');
    }
}

if(isset($_POST['checkall']) && $_POST['statusselect'] >= 1 && !isset($_REQUEST['completed'])){
    $ob->massStatusChange($_POST['checkall'], $_POST['statusselect'] );
    header('location: comments.php?completed=true');
}
if(isset($_POST['checkall']) && $_POST['statusselect'] < 1 && !isset($_REQUEST['completed'])){
    $ob->massDelete($_POST['checkall']);
    header('location: comments.php?completed=true');
}

if(isset($_REQUEST['del']) && empty($isReadonly)){
    $action='del';
    $ob->deleteComment($_REQUEST['id']);
    header('location: comments.php?completed=true');
}
if(isset($_REQUEST['edit'])){
    $ob->getCommentsDataById($_REQUEST['id']);
    $record_id=$_REQUEST['id'];
    $action = 'edit';
}
if(isset($_GET['add']))
    $action='add';
if(!isset($_REQUEST['id']))
    $ob->getPagedItems($pager, $_GET['ord']);
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
<h5>Διαχείρηση σχολίον</h5>
<div class="sublinks">
<br />
<?php
if(isset($_GET['edit']) || isset($_GET['del'])):
?>
<a href="comments.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if( isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="post"  name="comments_form" id="comments_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία σχόλιου:</legend>
    <br />
    <label for="poster-input">Όναμα</label>
    <input readonly="readonly" type="text" name="poster-input"  id="poster-input" value="<?php print  $ob->posted_by; ?>" />
    <br /><br />
    <label for="comment" >Σχόλιο</label>
    <textarea readonly="readonly" name="comment" id="comment" cols="32" rows="4"><?php print  $ob->content; ?></textarea>
    <br /><br />
    <label for="comment-type" >Είδος</label>
    <input type="text" readonly="readonly" name="comment-type" id="comment-type" value="<?php print  $ob->type; ?>" />
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
    <label for="statusselect">Κατάσταση</label>
    <?php print  comments_helper::createSelect($ob->status); ?>
    <br /><br />
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Αλλαγή" onclick="javascript: materialValidator('comments_form');"/>
  </fieldset>
</form>
<?php
endif;
if(!empty($ob->commentsdata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="matdatalist" cellspacing="0" >
<tr class="imgrow">
<td>&nbsp;</td>
<td><strong><a href="comments.php?pager=<?php print  $_GET['pager'].'&amp;ord=POSTED_BY'; ?>">Όναμα</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="comments.php?pager=<?php print  $_GET['pager'].'&amp;ord=TYPE'; ?>">Είδος</a></strong></td>
<td><strong><a href="comments.php?pager=<?php print  $_GET['pager'].'&amp;ord=CREATED'; ?>">Δημιουργήθηκε</a></strong></td>
<td><strong><a href="comments.php?pager=<?php print  $_GET['pager'].'&amp;ord=STATUS'; ?>">Κατάσταση</a></strong></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<form id="checkfields" name="checkfields" method="post" action="<?php print  $_SERVER['PHP_SELF'] ?>">
</td>
</tr>
<?php
for($z=0; $z<count($ob->commentsdata); $z++):

if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td>
<?php print  comments_helper::ceateCheckFieldForm($ob->commentsdata[$z]['id']); ?></td>
<td><?php print  $ob->commentsdata[$z]['posted_by']; ?></td>
<td><a href="<?php print  $ob->commentsdata[$z]['type'].'.php?sender=comments&amp;edit=true&amp;id='.$ob->commentsdata[$z]['object_id']; ?>"><?php print  $ob->commentsdata[$z]['type'] ?></a></td>
<td><?php print  $grdate[date('w',$ob->commentsdata[$z]['created'])].', '.date('d-m-Y', $ob->commentsdata[$z]['created']) ; ?></td>
<td><?php print  $statusDescr[$ob->commentsdata[$z]['status']]; ?></td>
<td><a href="comments.php?edit=true&amp;id=<?php print  $ob->commentsdata[$z]['id']; ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('comments',<?php print  $ob->commentsdata[$z]['id']; ?>);" >διαγραφή</a></td>
</tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  comments_helper::ceateCheckFieldForm($ob->commentsdata[$z]['id']); ?></td>
<td><?php print  $ob->commentsdata[$z]['posted_by']; ?></td>
<td><?php print  $ob->commentsdata[$z]['type']; ?></td>
<td><?php print  $ob->commentsdata[$z]['created']; ?></td>
<td><a href="comments.php?edit=true&amp;id=<?php print  $ob->commentsdata[$z]['id']; ?>">αλλαγή</a></td>
</tr>
<?php
endif;
endfor;
?>
<tr>
<td colspan="5" style="padding-left: 0px;">
<?php print  comments_helper::drawSubMenu(!empty($ob->status)?$ob->status:(int)1, 'comments' ); ?>
</td>
</tr>
</form>
</table>
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