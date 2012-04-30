<?php
session_start();
require_once('functions/adminFunctions.inc.php');
require_once(CLASS_PATH.SYS_FOLDER."class.user.php");//kdos: its all over the fucking place...beware
include("fckeditor/fckeditor.php");
require("xajax.common.php");
is_alive();

$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\content($dbObj);
$nm = new classes\src\content_material($dbObj);
if(isset($_GET['id']))
    $nm->getByID($_GET['id']);
$oFCKeditor = new FCKeditor('FCKeditor1') ;
$oFCKeditor->BasePath	= 'fckeditor/';

if(isset($_REQUEST['FCKeditor1']) && !isset($_REQUEST['new_record']) && !isset($_REQUEST['completed'])){
    
    $ob->created = time();
    $ob->updated_on = $ob->created;
    $ob->status = $_POST['statusselect'];
    $ob->title = trim($_POST['title']);
    $ob->created_by = $_POST['author'];
    $ob->commentable = $_POST['commentable'];
    srand(microtime());
    $ob->name = ($_REQUEST['filename'] == 'default' ? md5(time().'-'.rand()) : $_REQUEST['filename']);
    $ob->teaser = $_POST['teaser'];
    $ob->content = $_POST['FCKeditor1'];
      
    if($_POST['current_record'] > (int)0){
        $nm->getByID($_POST['current_record']);
        $ob->updated_on = time();
		$ob->update_item($_POST['current_record']);
        /**********************************************/
        if(is_array($_POST['active_material'])){
            if(is_array($nm->current_attachments)){
                for($z=0; $z<count($nm->current_attachments); $z++){
                    if(!in_array($nm->current_attachments[$z]['id'], $_POST['active_material']))
                        $nm->deleteSpecificByID($_POST['current_record'], $nm->current_attachments[$z]['id']);
                }
            }//now insert what's not allready there...
            for($z=0; $z<count($_POST['active_material']); $z++){
                if(!in_array($_POST['active_material'][$z], $nm->current_attachments))
                    $nm->insertAttachment($_POST['current_record'], $_POST['active_material'][$z]);
            }
        }
        if(!isset($_POST['active_material']))
            $nm->deleteAllByID($_POST['current_record']);
        /**********************************************/
        header('location: '.BASE_URL.'/'.APP_FOLDER.'/content.php?completed=true');
        exit();
	}
    if($ob->add_item() ){
         //attachments
        if(!empty($_POST['active_material'])){
            $lastNID=contentHelper::lastInsertedID();
            if(is_array($_POST['active_material'])){
                for($z=0; $z<count($_POST['active_material']); $z++)
                    $nm->insertAttachment($lastNID, $_POST['active_material'][$z]);//chech array with checked values...
            }
            else{
                $nm->insertAttachment($lastNID, $_POST['active_material']);
            }
        }
    }
    header('location: content.php?new_record=1');
}
if($_GET['edit']){
    $ob->get_itemByID($_GET['id']);
    $record_id=$_REQUEST['id'];
	$action = 'edit';
}
if(isset($_REQUEST['add'])){
	$record_id=0;
	$action='add';
}

#======= url options ===========================================#
#														                                                                                   #
#=========================================================#
if(!isset($_REQUEST['id'])){
	$ob->getPagedItems($pager,$_GET['ord']);
}
if(isset($_REQUEST['del']) && empty($isReadonly) ){
	$ob->delete_item($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'del';
	$ob->getPagedItems($pager, $_GET['ord']);
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
<h5>Content</h5>
<div class="sublinks">
<br />
<a href="content.php?add=true" title="Εισαγωγή νέου content">Εισαγωγή νέου content</a>
<?php
if( (isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del']) ) && !isset($_GET['sender']) ):
?>
&nbsp;|&nbsp;<a href="content.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
<?php
if(isset($_GET['sender'])):
?>
&nbsp;|&nbsp;<a href="<?php print  $_GET['sender']; ?>.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php if(isset($_GET['add']) || isset($_GET['edit']) ): ?>
<form name="content" id="content" class="fck_form" method="post" action="<?php print  $_SERVER['PHP_SELF']; ?>">
<fieldset class="fck_fieldset">
    <legend><?php print  (isset($_GET['add']) ? 'Νέο ':'Επεξεργασία ') ?>content</legend>
    <br />
    <label for="title">Τίτλος άρθρου</label>
    <input type="text" value="<?php print  $ob->title;?>" name="title" id="title" class="articletitle" />
    <br /><br />
    <label for="attachment">Προσθήκη download αρχείου</label>
    <input type="text" readonly="readonly" value="κλικ εδώ" 
    onclick="pop('material-selector.php<?php print  (($_GET['id']) ? ('?id='.$_GET['id']):('?id=0') );?>' + materialIDCollector() );" name="attachment" id="attachment"  />
    <br />
    <br />
    <div id="radios" style="display: <?php print  (is_array($nm->current_attachments)?'block':'none')?>; border: 1px solid #aaaaaa; width: 250px; height: 80px; overflow: scroll; margin-left: 216px;">
    <?php
    if(is_array($nm->current_attachments)):
        for($c=0; $c<count($nm->current_attachments); $c++):
    ?>
        <input style="width: 10px;" type="checkbox" name="active_material[]" value="<?php print  $nm->current_attachments[$c]['id']; ?>" checked />&nbsp;<?php print  $nm->current_attachments[$c]['name']; ?><br />
    <?php 
        endfor;
    endif;
    ?>
    </div>
    <br />
    <?php 
    if(!empty($ob->created) && !empty($ob->created_by)): ?>
    <label for="created">Δημιουργήθηκε</label>
    <input type="text" readonly="readonly" value="<?php print  $grdate[date('w', $ob->created)].', '.date('d-m-Y', $ob->created);?>" name="created" id="created" />
    <?php
    if(!empty($ob->updated_on)):
    ?>
    <strong>Τελευταία ενημέρωση:&nbsp;<?php print  $grdate[date('w', $ob->updated_on)].' ,'.date('d-m-Y \σ\τ\ι\ς h:i:s', $ob->updated_on); ?></strong>
    <?php
    endif;
    ?>
    <br /><br />
    <?php 
    endif; ?>
    <label for="created_by">Συντάκτης</label>
    <?php print  contentHelper::createAuthorsSelect((!empty($ob->created_by)?$ob->created_by:(int)0)); ?>
    <br /><br />
    <label for="commentable">Σχόλια</label>
    <?php print  contentHelper::createCommentableSelect((!empty($ob->commentable)?$ob->commentable:'YES')); ?>
    <br /><br />
    <label for="statusselect">Κατάσταση</label>
    <?php print  contentHelper::createSelect((!empty($ob->status))?$ob->status:1); ?>
     <br /><br />
      <label for="teaser">Πρόλογος</label>
    <textarea cols="72" rows="6" name="teaser" id="teaser">
<?php if(!empty($ob->teaser))print trim($ob->teaser); else '';?></textarea>
     <br /><br />
     <label for="FCKeditor1">Άρθρο</label>
<?php
$oFCKeditor->Value = (!empty($_POST['FCKeditor1']) ? $_POST['FCKeditor1'] : !empty($ob->content)?$ob->content:'Your text here...') ;
$oFCKeditor->Width = 600;
$oFCKeditor->Height = 500;
$oFCKeditor->Create();
?>
<br /><br />
<input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
<input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
<input type="hidden" size="50" name="filename" value="<?php print  !empty($ob->name)?$ob->name:'default'; ?>" />
<input type="button"  
<?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?>
class="fck_button" value="submit" onclick="javascript: fckvalidator('content');" />
</fieldset>
</form>
<?php else:
if(!empty($ob->contentdata)):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="article_table_div">
<table class="artdatalist" cellpadding="0" cellspacing="0">  
<tr class="imgrow">
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=TITLE'; ?>">Τίτλος Άρθρου</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=STATUS'; ?>">Κατάσταση</a></strong></td>
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=CREATED_BY'; ?>">Συντάκτης</a></strong></td>
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=CREATED'; ?>" >Δημιουργήθηκε</a></strong></td>
<td><strong><a href="content.php?pager=<?php print  $_GET['pager'].'&ord=UPDATED_ON'; ?>" >Τελευταία ενημέρωση</a></strong></td>
<td>&nbsp;</td>
<?php 
if(empty($isReadonly)):
?>
<td>&nbsp;</td>
<?php
endif;
?>
</tr>
<?php
for($z=0; $z<count($ob->contentdata); $z++):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><a class="title-ref" href="content.php?edit=true&amp;id=<?php print  $ob->contentdata[$z]['ID']; ?>"><?php print  $ob->contentdata[$z]['title']; ?><?php print  contentHelper::getAttachments($ob->contentdata[$z]['ID']); ?></a></td>
<td><?php print  $statusDescr[$ob->contentdata[$z]['status']]; ?></td>
<td><?php print  contentHelper::getAuthor($ob->contentdata[$z]['created_by']); ?></td>
<td><?php print  date('d-m-Y, h:i:s', $ob->contentdata[$z]['created'] ); ?></td>
<td><?php print  date('d-m-Y, h:i:s', $ob->contentdata[$z]['updated_on'] ); ?></td>
<td><a href="content.php?edit=true&amp;id=<?php print  $ob->contentdata[$z]['ID']; ?>">αλλαγή</a></td>
<?php 
if(empty($isReadonly)):
?>
<td><a  href="javascript: void(null);" onclick="deleteItem('content', <?php print  $ob->contentdata[$z]['ID']; ?>);">διαγραφή</a></td>
<?php 
endif;
?>
</tr>
<? endfor; ?>
</table>
</div>
<?php 
endif;
endif;?>
</div>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>



