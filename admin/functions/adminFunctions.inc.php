<?php
#======== HELPERS/WRAPPERS ===============================================#
#																		  #
#=========================================================================#

session_start();
require_once 'config.php';

function is_alive(){
	if(!isset($_SESSION['logged'], $_SESSION['username'])){
		header('location: http://'.$_SERVER['HTTP_HOST'].APP_WITH_SLASH.'/admin/index.php');
		exit();
	}
	return TRUE;
}

#====== ACCESS/PERMISSION HANDLING ==============================#
#												 		                                                                                 #
#================================================================#

function getCatPermsByBasename($basename, $uid){
    
        global $dbObj;
		$sql = "SELECT uc._READ_ , uc._WRITE_ , c.LINK FROM user_categs uc, categs c 
				WHERE 
				c.LINK = ".safe_sql($basename)."
				AND
				uc.CATEG_ID=c.ID
				AND
				uc.USER_ID=".safe_sql($uid);
		$res=$dbObj->query($sql);
		while($perms=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$catPermissions['read'] =  $perms['_READ_'];
			$catPermissions['write'] =  $perms['_WRITE_'];
		}
		switch($catPermissions['write']){
            case 'Y':
            $readonly='';
            break;
            case 'N':
            $readonly='readonly';
        }
        return $readonly;
	}
   
$isReadonly = getCatPermsByBasename(basename($_SERVER['PHP_SELF']),$_SESSION['ID']);
?>
