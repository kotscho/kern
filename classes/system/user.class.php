<?php
namespace classes\system;
#=============== user objects =============================#
#														   #
#==========================================================#
//email validation regexp:
//if (!eregi("^[a-zA-Z0-9]+[_a-zA-Z0-9-]*(\.[_a-z0-9-]+)*@[a-z??????0-9]+(-[a-z??????0-9]+)*(\.[a-z??????0-9-]+)*(\.[a-z]{2,4})$", $aFormValues['email']))
class user{
	
	var $userdata = array();
	var $ID;
	var $username;
	var $password;
	var $created;
	var $firstname;
	var $lastname;
	var $newpassword;
    var $groupID;
	
	function __construct(&$dbobj){
		$this->dbObj = $dbobj;
	}
	
	
	function getUserlist(){

		$sql ="SELECT username, password FROM users";
		$res=$this->dbObj->query($sql);
		while($users=$res->fetchRow(MDB2_FETCHMODE_ASSOC))
			$_user[$users['username']]=(string)$users['password'];
		
		return $_user;
	}
	
	function getUserData(){

		$sql ="SELECT * FROM users";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($users=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->userdata[$c]['ID']=$users['ID'];
			$this->userdata[$c]['username']=$users['username'];
			$this->userdata[$c]['password']=$users['password'];
			$this->userdata[$c]['created']=$users['created'];
			$this->userdata[$c]['firstname']=$users['firstname'];
			$this->userdata[$c]['lastname']=$users['lastname'];
            $this->userdata[$c]['groupID']=$users['groupID'];
			$c++;
		}
		return(is_array($this->userdata)?true:false);	
	}
	
	 function getPagedUserData(&$_this, $ordering=''){//paging object
	      	$sql= "SELECT * FROM users ORDER BY ".(!empty($ordering)?$ordering:'lastname, firstname');
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);	
	        	
	      while($res=mysql_fetch_assoc($_this->resultSet)){
	        	$this->userdata[$c]['ID']=$res['ID'];
				$this->userdata[$c]['username']=$res['username'];
				$this->userdata[$c]['password']=$res['password'];
				$this->userdata[$c]['created']=$res['created'];
				$this->userdata[$c]['firstname']=$res['firstname'];
				$this->userdata[$c]['lastname']=$res['lastname'];
                $this->userdata[$c]['groupID']=$res['groupID'];
				$c++;
	        }
	        return(is_array($this->userdata)?true:false);	
	 }	
	
	function getUserDataByID($id){

		$sql ="SELECT * FROM users WHERE ID =".safe_sql($id);
		$res=$this->dbObj->query($sql);

		while($users=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->ID=$users['ID'];
			$this->username=$users['username'];
			$this->password=$users['password'];
			$this->created=$users['created'];
			$this->firstname=$users['firstname'];
			$this->lastname=$users['lastname'];
            $this->groupID=$users['groupID'];
		}
		return(!empty($this->ID)?true:false);	
	}
	
	function addUser(){
		$sql = "INSERT users SET
				username=".safe_sql($this->username).",
				password=".safe_sql($this->password).",
				created=".safe_sql($this->created).",
				firstname=".safe_sql($this->firstname).",
                groupID=".safe_sql($this->groupID).",
				lastname=".safe_sql($this->lastname)."
				";

		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function updateUser($id){
		$sql = "UPDATE users SET ";
		$sql.=" username=".safe_sql($this->username).",";
		$sql.="	created=".safe_sql($this->created).",";
		$sql.="	firstname=".safe_sql($this->firstname).",";
        $sql.="	groupID=".safe_sql($this->groupID).",";
		$sql.="	lastname=".safe_sql($this->lastname);
		$sql.=" WHERE ID = ".safe_sql($id);
		
		$res=$this->dbObj->query($sql);  
		
		return($res==true?true:false);
	}
	
	function updatePassword($userid){
		$sql="UPDATE users SET password=".safe_sql($this->password)." WHERE ID=".safe_sql($userid);
		$res=$this->dbObj->query($sql);  
		
		return($res==true?true:false);
	}
	
	function deleteUser($id){
        //die($id);
        if($this->getUserGroup($id) == (int)1)
            return false;
		$sql = "DELETE FROM user_categs WHERE USER_ID =".safe_sql($id);
		$del=$this->dbObj->query($sql);
		if($del == true){
			$sql = "DELETE FROM users WHERE ID=".safe_sql($id);
			$res=$this->dbObj->query($sql);
		}
		else{
				return false;
		}
		return($res==true?true:false);
	}
	
	function getUserID($user){//by username 

		$sql ="SELECT ID FROM users WHERE username = ".safe_sql($user);  
		$res=$this->dbObj->query($sql);  
		while($users=$res->fetchRow(MDB2_FETCHMODE_ASSOC))
			$_userID = (int)$users['ID'];

		return (is_numeric($_userID)?$_userID:false); 
	} 


	function getUserName($id){//by id  
	
		$sql ="SELECT username FROM users WHERE id = ".sqfe_sql($id); 
		$res=$this->dbObj->query($sql); 
		while($users=$res->fetchRow(MDB2_FETCHMODE_ASSOC))
			$_userName = $users['username'];
		
		return $_userName;  
	} 

    function getUserGroup($id){
        $sql = "SELECT groupID FROM users WHERE ID = ".safe_sql($id);
        $res=$this->dbObj->query($sql); 
        while($group = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $groupID = $group['groupID']; 
            
        return $groupID;
    }
	
    function getAllGroups(){
        $sql = "SELECT ID,NAME FROM groups ";
        $res=$this->dbObj->query($sql); 
        $c=0;
        while($group = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
                 $groups[$c]['ID'] = $group['ID']; 
                 $groups[$c]['NAME'] = $group['NAME']; 
                 $c++;
        }
           
        return is_array($groups)?$groups:FALSE;
    }
    
    
    
	function logon($curr_user, $curr_passwd, $err=''){
		foreach($this->getUserlist() as $key => $value){
			if(($key == $curr_user) && (trim($value) == trim(md5($curr_passwd))) ){
				$_SESSION['logged']=(int)1;
				$_SESSION['username']=$curr_user;
				$_SESSION['ID']=$this->getUserID($_SESSION['username']);
                $_SESSION['group'] = $this->getUserGroup($_SESSION['ID']);
		    	if(isset($_SESSION['username'], $_SESSION['logged'])){
			    	header('location:' .BASE_URL.'/'.APP_FOLDER.'/admin.php');
			    	exit();
		    	}
			}
		}	
		return $err;
	}
    
	function getMaxId(){
		$sql = "SELECT MAX(ID) as lastid FROM users";
		$res=$this->dbObj->query($sql);
		while($max=$res->fetchRow(MDB2_FETCHMODE_ASSOC))
			$id = $max['lastid'];
		return $id;
	}
	
	function getSuperUsers(){
		$sql = "SELECT ID FROM users WHERE groupID = 1";
		$res=$this->dbObj->query($sql);
		$result = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
		
		return(is_array($result)?$result:false);
	}
}

class userHelpers{
	
	public static function getUserName($id){
		global $dbObj;
		$sql = "SELECT username FROM users WHERE ID=".safe_sql($id);
		$res=$dbObj->query($sql);
		while($user = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
			$username =  $user['username'];
		return $username; 
	}
    
    public static function getUserFullName($id){
		global $dbObj;
		$sql = "SELECT firstname, lastname FROM users WHERE ID=".safe_sql($id);
		$res=$dbObj->query($sql);
		while($user = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$first =  $user['firstname'];
            $last =  $user['lastname'];
        }
		return $first.' '.$last; 
	}
	
}
?>