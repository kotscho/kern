<?php
require_once(BASE_PATH.'/classes/system/class.application_nodes.php');
$menuObj = new application_nodes($dbObj);
ob_start();
?>
<div id="mydroplinemenu" class="droplinebar">
<?php print  $menuObj->renderMenu();  ?>
</div>
<?php
$menu = ob_get_contents();
ob_end_clean();
?>
  
