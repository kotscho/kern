<?php
session_start();
error_reporting(0);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");

is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\system\user($dbObj);
$categs = new classes\system\categs($dbObj);
$categs->getCategData();
$uc = new classes\system\user_categs($dbObj);
$allgroups = $ob->getAllGroups();

#====== form handling ===================================#
#														 #
#========================================================#

if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
	
	if(!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username'])){
		$ob->username = trim($_POST['username']);
		$ob->password = md5($_POST['passwd']);
		$ob->created = time();
		$ob->firstname = trim($_POST['firstname']);
		$ob->lastname = trim($_POST['lastname']);
        $ob->groupID = $_POST['gid'];
		if($_POST['passwd'] == $_POST['passwd_repeat'] && !empty($_POST['passwd']) && $_POST['current_record'] == (int)0){//add here: func passwdExists()
			$ob->addUser();
			$uc->insertPermissions($_POST, $ob->getMaxId());
		}
		elseif($_POST['current_record'] > 0){
			$ob->updateUser($_POST['current_record']);
			$uc->updatePermissions($_POST, $_POST['current_record']); 
		}
		header('location: '.BASE_URL.'/'.APP_FOLDER.'/user.php?completed=true');	
	}
}	
	elseif(isset($_POST['transferpasswd']) && !isset($_REQUEST['completed'])) { //add here func userPasswdExists()
	$ob->password = md5($_POST['passwd']);
	if($_POST['passwd'] == $_POST['passwd_repeat'])
		$ob->updatePassword($_POST['current_record']);
	header('location: '.BASE_URL.'/'.APP_FOLDER.'/user.php?completed=true');
}

#======= url options =====================================#
#														  #
#=========================================================#

if(!isset($_REQUEST['id'])){
	$ob->getPagedUserData($pager, $_GET['ord']);
}
if(isset($_REQUEST['edit'])){
	$ob->getUserDataByID($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'edit';
}
if(isset($_REQUEST['passwd'])){
	$record_id=$_REQUEST['id'];
	$action = 'passwd';
}
if(isset($_REQUEST['add'])){
	$record_id=0;
	$action='';
}
if(isset($_REQUEST['del']) && empty($isReadonly) ){
	$ob->deleteUser($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'del';
	$ob->getPagedUserData($pager, $_GET['ord']);
}

#====== output ==========================================#
#														 #
#========================================================#
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
<h5>Διαχείρηση χρηστών</h5>
<div class="sublinks">
<br />
<a href="user.php?add=true" title="Εισαγωγή νέου χρήστη">Εισαγωγή νέου χρήστη</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del'])):
?>
&nbsp;|&nbsp;<a href="user.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
?>
</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form method="post"  name="user_form" id="user_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF'];?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία χρήστη:</legend>
    <br />
    <label for="firstname">Όνομα</label>
    <input type="text" value="<?php print  $ob->firstname; ?>" size="50" name="firstname" id="firstname"/><br /><br />
    <label for="lastname">Επίθετο</label>
    <input type="text" value="<?php print  $ob->lastname; ?>" size="50" name="lastname"  id="lastname" /><br /><br />
    <label for="username">Όνομα χρήστη</label>
    <input type="text" value="<?php print  $ob->username; ?>" size="50" name="username" id="username"/><br /><br />
    <label for="username">Ομάδα χρήστη</label>
    <select name="gid" <?php print  (( $_SESSION['group'] > 1)?' disabled="disabled" ':'' ); ?> >
    <?php 
    for($z=0; $z<count($allgroups); $z++):?>
    <option value="<?php print  $allgroups[$z]['ID']; ?>" <?php print  ($ob->groupID == $allgroups[$z]['ID'])?' selected="selected" ':'';?> ><?php print  $allgroups[$z]['NAME']; ?></option>
    <?php 
    endfor; ?>
    </select>
    <br /><br />
    <?php 
    if(isset($_GET['add'])):
    ?>
    <label for="passwd">Κωδικός</label>
    <input type="password" size="50" name="passwd" id="passwd" /><br /><br />
    <label for="passwd_repeat">Επανάληφη κωδικόυ</label>
    <input type="password" size="50" name="passwd_repeat" id="passwd_repeat" /><br /><br />
    <?php
    endif;
    ?>
    <?php 
    if(isset($_GET['edit'])):
    ?>
    <label for="created">Created</label>
    <input type="text" disabled="disabled" value="<?php print  date('d-m-Y',$ob->created); ?>" size="50" name="created" id="created" />
    <?php
    endif;
    ?>
    <br /><br /><legend>Πρόσβαση και Προνόμια</legend><br /><br />
    <?php 
    for($c=0; $c<count($categs->categsdata); $c++):
        if(($_SESSION['group'] > 1 && $categs->categsdata[$c]['type'] == 'admin') || !in_array($categs->categsdata[$c]['descr'], $_MODULES)) 
            continue; //meaning that: the group is not admin, but the category type we are about to render needs admin prvilleges, OR current module is set inactive so compute the next tupel 
		$currentOptions = $uc->getUserCategInfo($_REQUEST['id'], $categs->categsdata[$c]['id']);
    ?>
    &nbsp;
    <strong><?php print  $categs->categsdata[$c]['descr']; ?>:</strong><br />
    <span class="permission_span">
    <input style="width:10px;" type="radio" <?php print  $currentOptions[0]; ?> value="noaccess" name="cat_<?php print  $categs->categsdata[$c]['id']; ?>" />&nbsp;Κρυμμένο&nbsp;
    <input style="width:10px;" type="radio" <?php print  $currentOptions[1]; ?> value="read" name="cat_<?php print  $categs->categsdata[$c]['id']; ?>" />&nbsp;Πρόσβαση&nbsp;
    <input style="width:10px;" type="radio" <?php print  $currentOptions[2]; ?> value="read_edit"  name="cat_<?php print  $categs->categsdata[$c]['id']; ?>" />&nbsp;Δυνατ. επεξεργασίας
    </span>
    <br />
	<?php
	endfor;
	?>
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" name="transfer" <?php print(!empty($isReadonly) && $_GET['overruled'] != 'true' ? ' disabled="disabled" ':''); ?> 
<?php
if(isset($_GET['edit'])): ?>
 	onclick="javascript: uservalidator('user_form', <?php print  (int)$_REQUEST['id']; ?> );"  
<?php endif; ?>
<?php
if(isset($_GET['add'])): ?>
 	onclick="javascript: uservalidatornew('user_form', <?php print  (int)$_REQUEST['id']; ?> );"  
<?php endif; ?>
class="input_button" value="Εισαγωγή" />
  </fieldset>
</form>
<?php
elseif(isset($_GET['passwd']) && empty($isReadonly)):
?>
<form method="post" name="user_form_np" id="user_form_np" class="mainform" action="<?php print  $_SERVER['PHP_SELF'];?>">
	<fieldset class="userfieldset">
	<legend>Κωδικός χρήστη (<?php print  userHelpers::getUserName($_REQUEST['id']); ?>)</legend>
    <br />
	<label for="passwd">Νέος κωδικός</label>
    <input type="password" size="50" name="passwd" id="passwd" /><br /><br />
    <label for="passwd_repeat">Επανάληφη κωδικόυ</label>
    <input type="password" size="50" name="passwd_repeat" id="passwd_repeat" /><br /><br />
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transferpasswd" value="true" />
    <input type="button" name="transferpasswd" onclick="javascript: passwdvalidator('user_form_np');" class="input_button" value="Εισαγωγή" />
    </fieldset>
</form>
<?php
else:
if(!empty($ob->userdata)):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="userdatalist" cellspacing="0">  
<tr class="imgrow">
<td><strong><a href="user.php?pager=<?php print  $_GET['pager'].'&ord=firstname'; ?>">Όνομα</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="user.php?pager=<?php print  $_GET['pager'].'&ord=lastname'; ?>">Επίθετο</a></strong></td>
<td><strong><a href="user.php?pager=<?php print  $_GET['pager'].'&ord=username'; ?>">Όνομα χρήστη</a></strong></td>
<td><strong><a href="user.php?pager=<?php print  $_GET['pager'].'&ord=created'; ?>">Created</strong></td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->userdata); $z++):
?>
<?php
if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->userdata[$z]['firstname']; ?></td><td><?php print  $ob->userdata[$z]['lastname']; ?></td>
<td><?php print  $ob->userdata[$z]['username']; ?></td><td><?php print  date('d-M-Y', $ob->userdata[$z]['created']);?></td>
<td><a href="user.php?edit=true&amp;id=<?php print  $ob->userdata[$z]['ID']; ?>">αλλαγή</a></td>
<td><a href="user.php?passwd=true&amp;id=<?php print  $ob->userdata[$z]['ID']; ?>">αλλαγή κωδικού</a></td>
<td><a  href="javascript: void(null);" onclick="xajax___indistructable(<?php print  $ob->userdata[$z]['groupID']; ?>, <?php print  $ob->userdata[$z]['ID']; ?>);" >διαγραφή</a></td></tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->userdata[$z]['firstname']; ?></td><td><?php print  $ob->userdata[$z]['lastname']; ?></td>
<td><?php print  $ob->userdata[$z]['username']; ?></td><td><?php print  date('d-M-Y', $ob->userdata[$z]['created']);?></td>
<td><a href="user.php?edit=true&amp;id=<?php print  $ob->userdata[$z]['ID']; ?>">περιήγηση</a></td>
<td>&nbsp;</td><td>&nbsp;</td></tr>
<?php
endif;
endfor;
?>
</table>
</div>
<?php
endif;
endif;
?>
</div>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>
