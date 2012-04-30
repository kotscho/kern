<?php
error_reporting(E_ALL);
session_start();
require_once('functions/adminFunctions.inc.php');

$ob=new classes\system\user($dbObj);

if(isset($_POST['user'], $_POST['passwd']))
    $ob->logon($_POST['user'], $_POST['passwd'], 'login failure');
    
if($_GET['logout']){
	foreach($_SESSION as $k=>$v){
        if(($k !== 'FULL_NAME') && ($k !== 'MEMBER_ID'))//those are the frontend session vars...keep them alive of course...
            unset($_SESSION[$k]);
    }
}
include('includes/header.php');
?>
<body>
<div id="wrapper">
<div id="loading" class="loading-invisible">
  <p><img src="img/loading.gif" alt=""/></p>
</div>
<script type="text/javascript" src="js/loader.js"></script>
<div class="logo"><h3><?php print  VENDOR; ?> - Manager<span class="versioninf"><br /><?php print  VERSION; ?></span></h3></div>
<div class="head">
<div class="formDiv">
<form method="post" name="validate" id="validate" action="<?php print  $_SERVER['PHP_SELF'];?>">
Όνομα Χρήστη:&nbsp;<input type="text" id="user" name="user" class="login_input" value="username" onclick="this.value='';"/>&nbsp;<br />
Κωδικός:&nbsp;<input type="password" id="passwd" name="passwd" class="login_input" value="password" onfocus="this.value='';" />&nbsp;<br />&nbsp;
</form>
<a href="#" class="login" onclick="go();">login</a>&nbsp;&nbsp;
</div>
</div>
<div class="menuDiv"></div>
<div class="content">
</div>
<?php
include('includes/footer.php');
?>
</div>
</body>
</html>





