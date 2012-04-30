<?php
#=============== aplication_nodes_types objects =============================#
#														   	 #
#============================================================#
namespace classes\system;

class application_nodes_types{
	
	var $application_nodes_typesdata = array();
	var $ID;
	var $name;
	
	function __construct(&$dbobj){
		$this->dbObj = $dbobj;
	}
	
	function getApplicationNodesTypesData(){

		$sql ="SELECT * FROM application_nodes_types";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($application_nodes_types=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->application_nodes_typesdata[$c]['id']=$application_nodes_types['ID'];
			$this->application_nodes_typesdata[$c]['name']=$application_nodes_types['NAME'];
			$c++;
		}
		return(is_array($this->application_nodes_typesdata)?true:false);	
	}
}
?>