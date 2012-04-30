<?php
//admin_installer.php
error_reporting(E_ALL);
require_once('functions/adminFunctions.inc.php');
require_once('functions/helperFunctions.inc.php');

$categs = new classes\system\categs($dbObj);
$u = new classes\system\user($dbObj);
$uc = new classes\system\user_categs($dbObj);
is_alive();
if(isset($_REQUEST['transfer'])){
	if($categs->insert($_POST['description'], $_POST['link'], $_POST['application'])){
		$uc->categ_id = $categs->lastInserted();
		$uc->read = 'Y';
		$uc->write = 'Y';
		$users = $u->getSuperUsers();
		for($z=0; $z<count($users); $z++){
			$uc->user_id = $users[$z]['ID'];
			if(!$uc->addUser_categs())//should be performed for all users
				header('location: admin.php?error=user_category_error');
		}
		header('location: admin.php?added_menu=true');
	}
	else{
		header('location: admin.php?error=menu_category_error');
	}
}
?>
<body>
<div id="wrapper">
<div class="logo"><h3><?php print  VENDOR; ?> - Manager<br /><span class="versioninf"><?php print  VERSION; ?></span></h3></div>
<div class="head">
<div class="formDiv">
</div>
</div>
<div class="menuDiv">
<div class="applicationLinks">
</div>
<div class="logoutDiv">
<a href="index.php?logout=true">logout</a>&nbsp;&nbsp;
</div>
</div>
<div class="content">
<h5>Add new admin menu item</h5>
<div class="sublinks">
<br />
<form enctype="multipart/form-data" method="post"  name="admin_menu_form" id="admin_menu_form" class="mainform" action="<?php print  $_SERVER['PHP_SELF']; ?>">
  <fieldset class="menu_item">
    <legend>New menu item:</legend>
    <br />
    <label for="name">Description</label>
    <input type="text" name="description" value="<?php print  $_POST['description']; ?>" />
    <br /><br />
    <label for="name">Link</label>
    <input type="text" name="link" value="<?php print  $_POST['link']; ?>" />
    <br /><br />
    <label for="user_id">Your user id</label>
    <input type="text" readonly name="user_id" value="<?php print  $_SESSION['ID']; ?>" />
    <br />
    <select name="application">
    <option value="native">native</option>
    <option value="external">external</option>
    </select>
    <br />
    <input type="submit" name="transfer" class="inout_button" value="submit" />
 </fieldset>
</form>
</div>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>