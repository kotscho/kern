<?php
session_start();
require_once('functions/adminFunctions.inc.php');
require_once(CLASS_PATH.SYS_FOLDER."class.user.php");
require_once(CLASS_PATH."class.content.php");
require_once(CLASS_PATH."class.content_material.php");
include("fckeditor/fckeditor.php") ;
require_once(CLASS_PATH.SYS_FOLDER."class.paging.php");
require_once(CLASS_PATH.EXT_FOLDER."dompdf/dompdf_config.inc.php");
require("xajax.common.php");
is_alive();

$dompdf = new DOMPDF();
$pager = new paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new content($dbObj);
$nm = new content_material($dbObj);
if(isset($_GET['id']))
    $nm->getByID($_GET['id']);
$oFCKeditor = new FCKeditor('FCKeditor1') ;
$oFCKeditor->BasePath	= 'fckeditor/';
//die(var_dump($_POST['active_material']));
 
if(isset($_REQUEST['FCKeditor1']) && !isset($_REQUEST['new_record']) && !isset($_REQUEST['completed'])){
    $ob->created = time();
    $ob->updated_on = $ob->created;
    $ob->status = 1;
    $ob->title = trim($_POST['title']);
    $ob->created_by = $_SESSION['ID'];
    srand(microtime());
    $ob->name = ($_REQUEST['filename'] == 'default' ? md5(time().'-'.rand()) : $_REQUEST['filename']);
    $ob->content = $_POST['FCKeditor1'];
    $_html = '<html><body>'.str_replace('src="/'.APP_FOLDER.'/','src="'.getcwd().'/', $ob->content).'</body></html>';
    $dompdf->load_html($_html);
    $dompdf->render();
  
    if($_POST['current_record'] > 0){
        $ob->updated_on = time();
		$ob->update_item($_POST['current_record']);
        /**********************************************/
        if(is_array($_POST['active_material'])){
            if(is_array($nm->current_attachments)){
                for($z=0; $z<count($nm->current_attachments); $z++){
                    if(in_array($nm->current_attachments[$z]['id'], $_POST['active_material'])){
                        continue;
                    }
                    else{
                        $nm->deleteSpecificByID($_POST['current_record'], $nm->current_attachments[$z]['id']);
                    }
                }
            }//now insert what's not allready there...
            for($z=0; $z<count($_POST['active_material']); $z++){
                if(!in_array($_POST['active_material'][$z], $nm->current_attachments))
                    $nm->insertAttachment($_POST['current_record'], $_POST['active_material'][$z]);
            }
        }
        /**********************************************/
        if(is_file('pdf/'.$ob->name.'.pdf'))
            unlink('pdf/'.$ob->name.'.pdf') ; 
        if(is_file('swf/'.$ob->name.'.swf'))
            unlink('swf/'.$ob->name.'.swf');
        file_put_contents('pdf/'.$ob->name.'.pdf', $dompdf->output($ob->name.'.pdf'));
        if(is_file('pdf/'.$ob->name.'.pdf'))
            $ob->pdf2swfWrap($ob->name);
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
        //=======//
        if(!is_dir('pdf'))
            mkdir('pdf', 0777);
        elseif(is_dir('pdf'))
            file_put_contents('pdf/'.$ob->name.'.pdf', $dompdf->output($ob->name.'.pdf'));
        else
            header('location: content.php?pdf=false&id='.$ob->name);
    }
    if(is_file('pdf/'.$ob->name.'.pdf'))
        $ob->pdf2swfWrap($ob->name);
    else
        header('location: content.php?pdf=false&id='.$ob->name);
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
	$ob->getPagedItems($pager);
}
if(isset($_REQUEST['del'])){
	$ob->delete_item($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'del';
	$ob->getPagedItems($pager);
}
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
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="content.php" title="Επιστροφή">Επιστροφή</a>
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
    <!-- kdos: select checkbox ids here with js -->
    <input type="text" readonly="readonly" value="κλικ εδώ" 
    onclick="pop('material-selector.php<?php print  (($_GET['id']) ? ('?id='.$_GET['id']):('?id=0') );?>' + materialIDCollector() );" name="attachment" id="attachment"  />
    <br />
    <br />
    <div id="radios" style="display: <?php print  (is_array($nm->current_attachments)?'block':'none')?>; border: 1px solid #aaaaaa; width: 250px; height: 80px; overflow: scroll; margin-left: 216px;">
    <?php
    if(is_array($nm->current_attachments)):
        for($c=0; $c<count($nm->current_attachments); $c++):
    ?>
        <input type="checkbox" name="active_material[]" value="<?php print  $nm->current_attachments[$c]['id']; ?>" checked />&nbsp;<?php print  $nm->current_attachments[$c]['name']; ?><br />
    <?php 
        //if($c > 0 && (($c%4) == 0) )print'<br />';
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
    Τελευταία ενημέρωση&nbso;<?php print  $grdate[date('w', $ob->updated_on)].' ,'.date('d-m-Y', $ob->updated_on); ?>
    <?php
    endif;
    ?>
    <br /><br />
    <label for="created_by">Συντάκτης</label>
    <input readonly="readonly" type="text" value="<?php print  userHelpers::getUserFullName($ob->created_by);?>" name="created_by" id="created_by" />
    <br /><br />
    <?php 
    endif; ?>
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
<input type="button"  class="fck_button" value="submit" onclick="javascript: fckvalidator('content');" />
</fieldset>
</form>
<?php else:
if(!empty($ob->contentdata)):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($decoded)?'src=popup':''));
?>
<div class="table_div">
<table class="datalist">  
<tr class="imgrow">
<td><strong>Περιγραφή</strong>&nbsp;&nbsp;</td>
<td><strong>Status</strong></td>
<td><strong>Created by</strong></td>
<td><strong>Created</strong></td>
<td>&nbsp;</td><td>&nbsp;</td>
<td><strong>pdf</strong></td>
<td><strong>swf</strong></td>
</tr>
<?php
for($z=0; $z<count($ob->contentdata); $z++):
?>
<tr><td><?php print  $ob->contentdata[$z]['name']; ?></td><td><?php print  $ob->contentdata[$z]['status']; ?></td><td><?php print  userHelpers::getUserName($ob->contentdata[$z]['created_by']); ?></td><td><?php print  date('d-m-Y', $ob->contentdata[$z]['created'] ); ?></td><td><a href="content.php?edit=true&amp;id=<?php print  $ob->contentdata[$z]['ID']; ?>">αλλαγή</a></td>
<td><a  href="javascript: void(null);" onclick="deleteItem('content', <?php print  $ob->contentdata[$z]['ID']; ?>);">διαγραφή</a></td>
<td>
<a href="pdf/<?php print  $ob->contentdata[$z]['name'].'.pdf'; ?>"><img border="0" title="pdf" src="img/pdf_icon.jpg" /></a>
</td>
<td>
<a href="swf/<?php print  $ob->contentdata[$z]['name'].'.swf'; ?>"><img border="0" title="flash movie" src="img/swf_icon.jpeg" /></a>
</td>
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



