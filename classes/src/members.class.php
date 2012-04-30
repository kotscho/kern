<?php
//class.members.php
namespace classes\src;
//!!!!!!!!!!!!!!!!!! ADD THE GETNEWSLETTER PARAM !!!!!!!!!!!!!!!!!!!!!!
class members {
    
    var $id;
    var $role; //why do we need a role system here? (besides the status a user may have, he/she can be a creator of a league, or am i missing something?)
    var $status;//like banned or whatever
    var $session; //the session string
    var $lastLogin;
    var $email;
    var $username;
    var $password;
    var $registrationDate;
    var $gender;
    var $dateOfBirth;
    var $location;
    var $photo;
    var $genderArray = array('MALE'=>'ΑΡΡΕΝ', 'FEMALE' => 'ΘΗΛΥ');
    
    function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }
    
     function get_items(){
		$sql ="SELECT * FROM members";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($member=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->memberdata[$c]['id']=$member['id'];
            $this->memberdata[$c]['role']=$member['role'];
            $this->memberdata[$c]['status']=$member['status'];
            $this->memberdata[$c]['session']=$member['session'];
            $this->memberdata[$c]['lastLogin']=$member['lastLogin'];
            $this->memberdata[$c]['email']=$member['email'];
            $this->memberdata[$c]['username']=$member['username'];
            $this->memberdata[$c]['password']=$member['password'];
            $this->memberdata[$c]['registrationDate']=$member['registrationDate'];
            $this->memberdata[$c]['gender']=$member['gender'];
            $this->memberdata[$c]['dateOfBirth']=$member['dateOfBirth'];
            $this->memberdata[$c]['location']=$member['location'];
            $this->memberdata[$c]['photo']=$member['photo'];
			$c++;
		}
		return(is_array($this->memberdata)?true:false);
	}
    
    function getPagedItems(&$_this, $ordering='',$where=''){//paging object
	      	$sql= "SELECT * FROM members ".$where." ORDER BY ".(empty($ordering)?'id':$ordering); 
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->memberdata[$c]['id']=$res['id'];
            $this->memberdata[$c]['role']=$res['role'];
            $this->memberdata[$c]['status']=$res['status'];
            $this->memberdata[$c]['session']=$res['session'];
            $this->memberdata[$c]['lastLogin']=$res['lastLogin'];
            $this->memberdata[$c]['email']=$res['email'];
            $this->memberdata[$c]['username']=$res['username'];
            $this->memberdata[$c]['password']=$res['password'];
            $this->memberdata[$c]['registrationDate']=$res['registrationDate'];
            $this->memberdata[$c]['gender']=$res['gender'];
            $this->memberdata[$c]['dateOfBirth']=$res['dateOfBirth'];
            $this->memberdata[$c]['location']=$res['location'];
            $this->memberdata[$c]['photo']=$res['photo'];
            $c++;
        }
       
	    return(is_array($this->memberdata)?true:false);	
	 }
    
    
     function getMemberDataById($id){
        $sql = "SELECT * FROM members WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        $c=0;
        while($member=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->id=$member['id'];
            $this->role=$member['role'];
            $this->status=$member['status'];
            $this->session=$member['session'];
            $this->lastLogin=$member['lastLogin'];
            $this->email=$member['email'];
            $this->username=$member['username'];
            $this->password=$member['password'];
            $this->registrationDate=$member['registrationDate'];
            $this->gender=$member['gender'];
            $this->dateOfBirth=$member['dateOfBirth'];
            $this->location=$member['location'];
            $this->photo=$member['photo'];
        }
        return(!empty($this->id)?true:false);
     }
     
    function deleteMember($id){
        $sql = "DELETE FROM members WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
     function insert(){
		$sql = "INSERT members SET
                role=".safe_sql($this->role).",
                status=".safe_sql($this->status).",
                session=".safe_sql($this->session).",
                lastLogin=".safe_sql($this->lastLogin).",
                email=".safe_sql($this->email).",
                username=".safe_sql($this->username).",
                password=".safe_sql(md5($this->password)).",
                registrationDate=".safe_sql($this->registrationDate).",
                gender=".safe_sql($this->gender).",
                dateOfBirth=".safe_sql($this->treatDate($this->dateOfBirth)).",
                location=".safe_sql($this->location).",
                photo=".safe_sql($this->photo);
                
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
    
    function update($id){
		$sql = "UPDATE members SET
                role=".safe_sql($this->role).",
                status=".safe_sql($this->status).",
                session=".safe_sql($this->session).",
                lastLogin=".safe_sql($this->lastLogin).",
                email=".safe_sql($this->email).",
                username=".safe_sql($this->username).",
                password=".safe_sql($this->password).",
                registrationDate=".safe_sql($this->registrationDate).",
                gender=".safe_sql($this->gender).",
                dateOfBirth=".safe_sql($this->treatDate($this->dateOfBirth)).",
                location=".safe_sql($this->location).",
                photo=".safe_sql($this->photo)."
				WHERE id=".safe_sql($id);
                
		$res=$this->dbObj->query($sql); 
		
		return(($res==true)?true:false);
	}
    
    function useremailexists($name){
        $sql = "SELECT email FROM members WHERE email = ".safe_sql($name);
        $res=$this->dbObj->query($sql); 
        while($email = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $useremail = $email['email'];
        return (!empty($useremail)?true:false);
    }

    function isPassword($passwd, $id){
       $sql = "SELECT password FROM members WHERE password = ".safe_sql(md5($passwd))." AND ID=".safe_sql($id);
       $res=$this->dbObj->query($sql); 
        while($userpassword = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $currPassword = $userpassword['password'];
        return (!empty($currPassword)?true:false);
    }
    
    function isUniqueTeamName($team){
    	$sql = "SELECT teamName from members WHERE teamName=".safe_sql($team);
    	$res = $this->dbObj->query($sql);
    	$result = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
    	
    	return(!empty($result[0]['teamName'])?FALSE:TRUE);
    }
    
    function getMemberName($id){
        $sql = "SELECT username FROM members WHERE ID = ".safe_sql($id);
        $res=$this->dbObj->query($sql); 
        while($userdata = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $userinfo = $userdata['username'];
        return(!empty($userinfo)?$userinfo:false);
    }
    
    function setGender(){
     	$out.='<select name="gender">';
     	foreach($this->genderArray as $k => $v )
     		$out.='<option value="'.$k.'" '.(($this->gender == $k)?' selected="selected" ':'').' >'.$v.'</option>';
     	$out.='</select>';
     	
     	return $out;
    }
    
    function treatDate($date){
    	$arr=explode('/', $date);
    	return(strtotime($arr[1].'/'.$arr[0].'/'.$arr[2]));
    }
    //registrate new member( process newly received hotlink )
    function registrateMember(){}
    
    //send hotlink
    function sendHotlink(){}
    
    //lastlogin
    function lastMemberLogin(){}
    
    //last user entered transacton area
    function lastTransactionView(){}
    
}

class membersHelpers{
    public static function getAllActiveMembers(){
        global $dbObj;
        
        $sql = "SELECT EMAIL FROM members WHERE GETNEWSLETTER = 'YES' ";
        die($sql);
        $res = $dbObj->query($sql);
        while($members = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $membermails[] = $members['EMAIL']; 
        return (is_array($membermails) ? $membermails:false);
    }
    
    public static function memberLogin($user, $passwd){
        global $dbObj;
        $sql = "SELECT id, username FROM members WHERE username=".safe_sql($user)." AND password = ".safe_sql(md5($passwd));
        $res = $dbObj->query($sql);
        //die($sql);
         while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
                $_SESSION['MEMBER_ID'] = $result['ID'];
                $_SESSION['FULL_NAME'] = $result['username'];//session is called fullname, due to historical reasons
        }
        return(is_numeric($_SESSION['MEMBER_ID'] )?true:false);
    }
    
    public static function setMemberStatus($id=0){ //lock, ban do wharever
    	global $dbObj;
    	$sql = "SELECT id, status FROM memberstatus";
    	$res = $dbObj->query($sql);
    	if(!$res)
			return false;
		
		$out.='<select name="status">';
		while($result=$res->fetchRow(MDB2_FETCHMODE_ASSOC))
			$out.= '<option value="'.$result['id'].'" '.(($result['id'] == $id)?' selected="selected" ':'').'>'.$result['status'].'</option>';
		$out.='</select>';
		
		return $out;
    }

    public static function getStatusName($id){
    	global $dbObj;
    	$sql = "SELECT status FROM memberstatus WHERE id=".safe_sql($id);
    	$res=$dbObj->query($sql);
    	if(!$res)
    		return false;
    	$out = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
    	
    	return $out[0]['status'];
    }
    
    //add general mailer class...
    
    //impement the search code..
}
?>