<?php
#=============== categs objects =============================#
#														   	 #
#============================================================#
namespace classes\system;

class categs{
	
	var $categdata = array();
	var $ID;
	var $descr;
	var $link;
    var $type;
    var $application;
	
	function __construct(&$dbobj){
		$this->dbObj = $dbobj;
	}
	
	function getCategData(){

		$sql ="SELECT * FROM categs";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($categs=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->categsdata[$c]['id']=$categs['ID'];
			$this->categsdata[$c]['descr']=$categs['DESCR'];
			$this->categsdata[$c]['link']=$categs['LINK'];
            $this->categsdata[$c]['type']=$categs['TYPE'];
            $this->categsdata[$c]['application']=$categs['APPLICATION'];
			$c++;
		}
		return(is_array($this->categsdata)?true:false);	
	}
	
	function insert($descr, $link, $application){
		$sql = "INSERT INTO categs SET DESCR = ".safe_sql($descr).", 
										 LINK = ".safe_sql($link).", TYPE='user', 
										 APPLICATION = ".safe_sql($application);

		$res = $this->dbObj->query($sql);
		return ($res)?true:false;
	}

	function lastInserted(){
		$sql = "SELECT MAX(`ID`) FROM categs";
		$res=$this->dbObj->query($sql);
		$result = (int)$res->fetchAll(MDB2_FETCHMODE_ASSOC);
		
		return(is_numeric($result[0]['ID'])?$result[0]['ID']:false);
	}
}
?>