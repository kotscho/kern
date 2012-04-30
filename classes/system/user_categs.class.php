<?php
namespace classes\system;
#=============== user_categs objects =============================#
#														          #
#=================================================================#
class user_categs{
	
	var $user_categsdata = array();
	var $ID;
	var $user_id;
	var $categ_id;
	var $read;
	var $write;
	var $catPermissions = array();

	
	function __construct(&$dbobj){
		$this->dbObj = $dbobj;
	}
	
	
	function getUser_categsData(){

		$sql ="SELECT * FROM user_categs";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($user_categs=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->user_categsdata[$c]['ID']=$user_categs['ID'];
			$this->user_categsdata[$c]['user_id']=$user_categs['USER_ID'];
			$this->user_categsdata[$c]['categ_id']=$user_categs['CATEG_ID'];
			$this->user_categsdata[$c]['read']=$user_categs['_READ_'];
			$this->user_categsdata[$c]['write']=$user_categs['_WRITE_'];
			$c++;
		}
		return(is_array($this->user_categsdata)?true:false);
	}
	
	function getUser_categsDataByID($id){

		$sql ="SELECT * FROM user_categs WHERE ID =".safe_sql($id);
		$res=$this->dbObj->query($sql);

		while($user_categs=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->ID=$user_categs['ID'];
			$this->user_id=$user_categs['USER_ID'];
			$this->categ_id=$user_categs['CATEG_ID'];
			$this->read=$user_categs['_READ_'];
			$this->write=$user_categs['_WRITE_'];
		}
		return(!empty($this->ID)?true:false);	
	}
	
	function addUser_categs(){
		$sql = "INSERT user_categs SET
				user_id=".safe_sql($this->user_id).",
				categ_id=".safe_sql($this->categ_id).",
				_read_=".safe_sql($this->read).",
				_write_=".safe_sql($this->write)."
				";

		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function updateUser_categs($id){
		$sql = "UPDATE user_categs SET ";
		$sql.=" user_id=".safe_sql($this->user_id).",";
		$sql.="	categ_id=".safe_sql($this->categ_id).",";
		$sql.="	_read_=".safe_sql($this->read).",";
		$sql.="	_write_=".safe_sql($this->write);
		$sql.=" WHERE ID = ".safe_sql($id);
		
		$res=$this->dbObj->query($sql);  
		
		return($res==true?true:false);
	}
	
	function hasAccess($uid, $catid){
		$sql = "SELECT _READ_ FROM user_categs WHERE USER_ID= ".safe_sql($uid)." AND CATEG_ID=".safe_sql($catid);
		$res=$this->dbObj->query($sql);
		while($read_permission=$res->fetchRow(MDB2_FETCHMODE_ASSOC) )
			$read = $read_permission['_READ_'];
		return ($read == 'Y'?true:false);
	}
	
    function getAllowed($uid){
		$sql = "SELECT C.LINK FROM categs C, user_categs UC
                    WHERE 
                    UC.USER_ID= ".safe_sql($uid)." 
                    AND 
                    UC._WRITE_ = 'Y' 
                    AND
                    C.ID = UC.CATEG_ID";
                    
		$res=$this->dbObj->query($sql);
        $c=0;
		while($allowed=$res->fetchRow(MDB2_FETCHMODE_ASSOC) )
			$allowedURL[$c++] = $allowed['LINK'];
        return $allowedURL;
	}
    
	function getUserCategInfo($uid, $catid){
		$sql = "SELECT _READ_ , _WRITE_ FROM user_categs WHERE USER_ID= ".safe_sql($uid)." AND CATEG_ID=".safe_sql($catid);
		$res=$this->dbObj->query($sql);
		while($permissions=$res->fetchRow(MDB2_FETCHMODE_ASSOC) ){
            if(!in_array())
			$permarray['read'] = $permissions['_READ_'];
			$permarray['write'] = $permissions['_WRITE_'];
		}
		if($permarray['write'] == 'Y'){//limited access
			$currentPermarray[0]='';
			$currentPermarray[1]='';
			$currentPermarray[2]='checked="checked"';
		}
		elseif($permarray['read'] == 'Y' && $permarray['write'] == 'N'){ //full read/write permissions
			$currentPermarray[0]='';
			$currentPermarray[1]='checked="checked"';
			$currentPermarray[2]='';
		}
		else{//no access
			$currentPermarray[0]='checked="checked"';
			$currentPermarray[1]='';
			$currentPermarray[2]='';
		}
		return(is_array($currentPermarray)?$currentPermarray:null); 
	}
	
	function insertPermissions($postarray, $uid){
		foreach($postarray as $k => $v){
			if(mb_strpos($k,'cat_') !== false ){ //postvar of type 'cat_' 
				$arr=explode('_',$k);
				switch($v){
					case 'read_edit':
					$sql="INSERT user_categs SET user_id=".safe_sql($uid).",categ_id=".safe_sql($arr[1]).",_read_= 'Y',_write_='Y'";
					break;
					case 'read':
					$sql="INSERT user_categs SET user_id=".safe_sql($uid).",categ_id=".safe_sql($arr[1]).",_read_= 'Y',_write_='N'";
					break;
					case 'noaccess':
					$sql="INSERT user_categs SET user_id=".safe_sql($uid).",categ_id=".safe_sql($arr[1]).",_read_= 'N',_write_='N'";
					break;
					default:
					break;
				}
				if(!empty($sql))
					$res=$this->dbObj->query($sql);
				if($res === false)
					return false;
			}
			else{
				continue;
			}
		}
		return true;
	}
	
	function updatePermissions($postarray, $uid){
		foreach($postarray as $k => $v){
			$query_type = '';
			if(mb_strpos($k,'cat_') !== false ){ //postvar of type 'cat_' 
				$arr=explode('_',$k);
				
				switch($v){
					case 'read_edit':
						$sql="UPDATE user_categs SET _read_= 'Y',_write_='Y' WHERE user_id=".safe_sql($uid)." AND categ_id=".safe_sql($arr[1]);
					break;
					case 'read':
						$sql="UPDATE user_categs SET _read_= 'Y',_write_='N' WHERE user_id=".safe_sql($uid)." AND categ_id=".safe_sql($arr[1]);
					break;
					case 'noaccess':
						$sql="UPDATE user_categs SET _read_= 'N',_write_='N' WHERE user_id=".safe_sql($uid)." AND categ_id=".safe_sql($arr[1]);
					break;
					default:
					break;
				}
				if(!empty($sql))
					$res=$this->dbObj->query($sql);
				if($res === false)
					return false;
			}
			else{
				continue;
			}
		}
		return true;
	}
	
	function recordExists($categid, $uid, $v){
		//check this kotscho...
		switch($v){
			case 'read_edit':
				$perms[0] = 'Y';
				$perms[1] = 'Y';
			break;
			case 'read':
				$perms[0] = 'Y';
				$perms[1] = 'N';
			break;
			case 'noaccess':
				$perms[0] = 'N';
				$perms[1] = 'N';
			break;
			
			default:
			break;
		}
		
		$sql = "SELECT ID FROM user_categs 
					WHERE 
					CATEG_ID =".safe_sql($categid)." AND USER_ID = ".safe_sql($uid)."
					AND _read_ = ".safe_sql($perms[0])." AND _write_ = ".safe_sql($perms[1]);
					//die($sql);
		$res=$this->dbObj->query($sql);
		while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC) )
			$ID = $result['ID'];
		return (!empty($ID) ? true : false);
	}
}
?>
