<?php
//members.php 
error_reporting(0);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');
require("xajax.common.php");

is_alive();
$pager = new classes\system\paging(PAGER_LIMIT, 'smallLinks', $dbObj);
$ob = new classes\src\members($dbObj);
$customsearcharray = array('status', 'ACTIVE', 'LOCKED', 'BANNED');
$search = new classes\system\search($dbObj, 'members', $customsearcharray);

if(isset($_POST['submit_search'])){
	if(($searchresults = $search->adminSearch($_POST)) !== FALSE )
		$search->triggerResultDisplay($searchresults);
		$search->resultInfo();
}
if(isset($_POST['transfer']) && !isset($_REQUEST['completed'])){
	
	foreach($_POST as $key => $value){
		if(strstr($key, 'noedit') === FALSE ){
			if(property_exists('members',$key))
				$ob->{$key} = $value;
		}
	}
    
    if($_POST['current_record'] > 0){
        if(!empty($_POST['olduserpassword'])){
            if(!$ob->isPassword($_POST['olduserpassword'], $_POST['current_record']))
                header('location: members.php?completed=false'.(!empty($_REQUEST['where'])?'&where='.$_REQUEST['where']:''));
            else
                $ob->password = $_POST['newuserpassword'];
        }  
        $ob->update($_POST['current_record']);
        header('location: members.php?completed=true'.(!empty($_REQUEST['where'])?'&where='.$_REQUEST['where']:''));
    }
    else{ //add new record
    	$ob->registrationDate = time();
        $ob->insert();
        header('location: members.php?completed=true'.(!empty($_REQUEST['where'])?'&where='.$_REQUEST['where']:''));
    }
}

if(isset($_REQUEST['del']) && empty($isReadonly)){
    $action='del';
    $ob->deleteMember($_REQUEST['id']);
    header('location: members.php?completed=true'.(!empty($_REQUEST['where'])?'&where='.$_REQUEST['where']:''));
}

if(isset($_REQUEST['edit'])){
	$ob->getMemberDataById($_REQUEST['id']);
	$record_id=$_REQUEST['id'];
	$action = 'edit';
}
if(isset($_GET['add']))
    $action='add';
if(!isset($_REQUEST['id']))
	$where_sql = (!empty($search->where_clause)?$search->where_clause:(!empty($_REQUEST['where'])?str_replace('\\','',urldecode($_REQUEST['where'])):''));
	$ob->getPagedItems($pager, $_GET['ord'], $where_sql);

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
<h5>Διαχείρηση μελών</h5>
<div class="sublinks">
<br />
<a href="members.php?add=true" title="Εισαγωγή νέου μέλος">Εισαγωγή νέου μέλος</a>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) || isset($_GET['del']) || (!empty($_REQUEST['where']) || !empty($search->where_clause))):
?>
&nbsp;|&nbsp;<a href="members.php" title="Επιστροφή">Επιστροφή</a>
<?php
endif;
if(!empty($_REQUEST['where']) && isset($_REQUEST['id'])):
?>
&nbsp;|&nbsp;<a href="members.php<?php print '?where='.urlencode($_REQUEST['where']); ?>" title="Επιστροφή">Επιστροφή στα αποτελέσματα</a>
<?php
endif;
?>
&nbsp;|&nbsp;<a id="inline" href="#search_box" title="Αναζήτηση member">Αναζήτηση</a>
<?php
	if(!empty($search->where_clause) || !empty($_REQUEST['where']))
		print $_SESSION['performance_info'];	
?>

</div>
<?php
if(isset($_GET['add']) || isset($_GET['edit']) ):
?>
<form enctype="multipart/form-data" method="post"  name="members_form" id="members_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="userfieldset">
    <legend>Στοιχεία μέλος:</legend>
    <br />
    <label for="email" >Email</label>
    <input type="text" onblur="<?php print  ((isset($_GET['add'])) ? 'xajax___emailExists(this.value);' : '');?> memberMailCheck(this.value);" class="input_email" name="email" value="<?php print  $ob->email; ?>" />
    <br /><br />
    <label for="username" >Όνομα χρήστη</label>
    <input class="input_username" <?php print  (isset($_GET['add']) ? 'onfocus="this.value = document.members_form.email.value;"':'' );?> type="text" name="username" value="<?php print  $ob->username; ?>" />
    <br />
    <label for="password" >Κωδικός</label>
    <input <?php print  ((isset($_GET['edit'])) ? 'readonly="readonly" ' : ''); ?>type="password" name="password" value="<?php print  $ob->password; ?>" />
    <?php
    if(isset($_GET['edit'])):
    ?>
    <a id="toggler" onclick="togglePasswordId('editpassword', this);" href="javascript: void(null);">Αλλαγή κωδικού</a>
    <div id="editpassword" style="display: none;">
    <br />
    <label for="olduserpassword" >Παλιός κωδικός</label>
    <input type="password" onblur="xajax___isCorrectPassword(document.members_form.olduserpassword.value, <?php print  $ob->id; ?>);" name="olduserpassword" value="" /><br />
    <label for="newuserpassword" >Νέος κωδικός</label>
    <input type="password" name="newuserpassword" value="" /><br />
    <label for="newuserpasswordrepeat" >Επ. νέου κωδικού</label>
    <input type="password" name="newuserpasswordrepeat" value="" /><br />
    </div>
    <br /><br />
    <label for="registrationDate_noedit">Δημιουργήθηκε</label>
    <input type="text" class="input_dateinfo" disabled readonly="readonly" value="<?php print  $grdate[date('w', $ob->registrationDate)].', '.date('m-d-Y', $ob->registrationDate);?>" name="registrationDate_noedit" id="registrationDate_noedit" />
    <br /><br />
    <label for="lastLogin_noedit">Τελευταίο login</label>
    <input type="text" class="input_dateinfo" disabled readonly="readonly" value="<?php print  $grdate[date('w', $ob->lastLogin)].', '.date('m-d-Y', $ob->lastLogin);?>" name="lastLogin_noedit" id="lastLogin_noedit" />
    <br /><br />
     <label for="latestViewedTransactions">Latest viewed transactions</label><!-- kdos: clarify pease -->
    <input type="text" class="input_dateinfo" disabled readonly="readonly" value="<?php print  $grdate[date('w', $ob->latestViewedTransactions)].', '.date('m-d-Y', $ob->latestViewedTransactions);?>" name="latestViewedTransactions_noedit" id="latestViewedTransactions_noedit" />
    <br /><br />
    <?php
    endif;
    ?>
    <br />
    <?php
    if(isset($_GET['add'])):
    ?>
    <label for="userpasswordrepeat" >Επ. Κωδικού</label>
    <input type="password" name="userpasswordrepeat" value="" />
    <br />
    <?php
    endif;
    ?>
   	
   	<label for="status" >Κατάσταση</label>
   	<?php print  membersHelpers::setMemberStatus($ob->status); ?> 
   	<br />
    <label for="teamName" >Όνομα ομάδας</label>
    <input type="text" onblur="xajax___isUniqueTeamName(this.value)" name="teamName" value="<?php print  $ob->teamName; ?>" /><br />
    <label for="gender" >Φύλο</label>
    <?php print  $ob->setGender(); ?><br />
    <label for="dateOfBirth" >Ημμ. Γέν. </label>
    <input type="text" id="datepicker_member" name="dateOfBirth" value="<?php print  date('d/m/Y', (empty($ob->dateOfBirth)?time():$ob->dateOfBirth)); ?>" /><br />
    <label for="location" >Περιοχή</label>
    <input type="text" name="location" value="<?php print  $ob->location; ?>" /><br />
    <input type="hidden" size="50" name="current_record" value="<?php print  $record_id; ?>" />
    <input type="hidden" size="50" name="action" value="<?php print  $action; ?>" />
    <input type="hidden" name="transfer" value="true" />
    <br /><br />
    <input type="button" <?php print  (!empty($isReadonly)?'disabled="disabled"':''); ?> name="transfer" class="input_button" value="Εισαγωγή" onclick="javascript: memberValidator('members_form', <?php print  (is_numeric($ob->id))?$ob->id:(int)0; ?>);"/>
  </fieldset>
</form>
<?php
endif;
if(!empty($ob->memberdata) && !isset($_GET['add']) && !isset($_GET['edit']) ):
	$decoded=urldecode($_REQUEST['src']);
	print $pager->displayPageNums(2, (!empty($_GET['ord'])?'ord='.$_GET['ord'].'':''));
?>
<div class="table_div">
<table class="matdatalist" cellspacing="0" >  
<tr class="imgrow">
<td><strong><a href="members.php?pager=<?php print  $_GET['pager'].'&ord=username'; ?>">Όναμα χρήστη</a></strong>&nbsp;&nbsp;</td>
<td><strong><a href="members.php?pager=<?php print  $_GET['pager'].'&ord=status'; ?>">Status</a></strong></td>
<td><strong><a href="members.php?pager=<?php print  $_GET['pager'].'&ord=registrationDate'; ?>">Δημιουργήθηκε</a></strong></td>
<td>&nbsp;</td>
</tr>
<?php
for($z=0; $z<count($ob->memberdata); $z++): 
?>
<?php

if(empty($isReadonly)):
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->memberdata[$z]['username']; ?></td>
<td><?php print  membersHelpers::getStatusName($ob->memberdata[$z]['status']); ?></td>
<td><?php print  $grdate[date('w',$ob->memberdata[$z]['registrationDate'])].' ,'.date('m-d-Y', $ob->memberdata[$z]['registrationDate']) ; ?></td>
<td><a href="members.php?edit=true&amp;id=<?php print  $ob->memberdata[$z]['id']; ?><?php print  ((!empty($where_sql))?'&amp;where='.urlencode($where_sql):''); ?>">αλλαγή</a>
&nbsp;&nbsp;<a  href="javascript: void(null);" onclick="deleteItem('members',<?php print  $ob->memberdata[$z]['id']; ?>);" >διαγραφή</a></td>
</tr>
<?php
else:
?>
<tr onMouseover="this.bgColor='#ffdf89'" onMouseout="this.bgColor='#ffffff'">
<td><?php print  $ob->memberdata[$z]['username']; ?></td>
<td><?php print  $ob->memberdata[$z]['status']; ?></td>
<td><?php print  $grdate[date('w',$ob->memberdata[$z]['registrationDate'])].' ,'.date('d-m-Y', $ob->memberdata[$z]['registrationDate']) ; ?></td>
<td><a href="members.php?edit=true&amp;id=<?php print  $ob->memberdata[$z]['id']; ?>">περιήγηση</a></td>
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
<div style="display: none;">
<div id="search_box">
<?php print  $search->renderSearchMask('searchfieldset'); ?>
</div>
</div>
</div>
</body>
</html>