<?php
//class.categ_units.php
namespace classes\system;

class categ_units {

    var $dbObj;
    var $id;
    var $name;
    var $info;
    var $created;
    var $updated; 


    function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }

    function get_items(){
		$sql ="SELECT * FROM categ_units";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($categ_units=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
                    $this->categ_units_data[$c]['id']=$categ_units['ID'];
                    $this->categ_units_data[$c]['name']=$categ_units['NAME'];
                    $this->categ_units_data[$c]['updated']=$categ_units['UPDATED'];
                    $this->categ_units_data[$c]['info']=$categ_units['INFO'];
                    $this->categ_units_data[$c]['created']=$categ_units['CREATED'];
                    $c++;
		}
		return(is_array($this->categ_units_data)?true:false);
	}

    function getPagedItems(&$_this, $ordering=''){//paging object
        $sql= "SELECT * FROM categ_units ORDER BY ".(empty($ordering)?'ID':$ordering);
        $c=0;
        $_this->turnPage($_GET['pager'], $sql);
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->categ_units_data[$c]['id']=$res['ID'];
            $this->categ_units_data[$c]['name']=$res['NAME'];
            $this->categ_units_data[$c]['updated']=$res['UPDATED'];
            $this->categ_units_data[$c]['info']=$res['INFO'];
            $this->categ_units_data[$c]['created']=$res['CREATED'];
            $c++;
        }
	    return(is_array($this->categ_units_data)?true:false);
	 }

    function getcateg_units_dataById($id){
        $sql = "SELECT * FROM categ_units WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        $c=0;
        while($categ_units=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->id = $categ_units['ID'];
            $this->name= $categ_units['NAME'];
            $this->updated = $categ_units['UPDATED'];
            $this->info = $categ_units['INFO'];
            $this->created = $categ_units['CREATED'];
        }
        return(!empty($this->id)?true:false);
     }

    function deleteLink($id){
        $sql = "DELETE FROM categ_units WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }

    function insert(){
		$sql = "INSERT categ_units SET
                NAME=".safe_sql($this->name).",
                INFO=".safe_sql($this->info).",
                CREATED=".safe_sql($this->created).",
                UPDATED=".safe_sql($this->updated);
		$res=$this->dbObj->query($sql);

		return($res==true?true:false);
	}

	function update($id){
		$sql = "UPDATE categ_units SET
                NAME=".safe_sql($this->name).",
                INFO=".safe_sql($this->info).",
                UPDATED=".safe_sql($this->updated)."
		WHERE ID=".safe_sql($id);
		$res=$this->dbObj->query($sql);

		return($res==true?true:false);
	}
}

class categ_units_helper {

    public static function hasEntries($categ_unit_id) {

        global $dbObj;
        
        $sql = "SELECT COUNT(category_unit) as `entries` FROM `application_nodes` WHERE category_unit=".safe_sql($categ_unit_id);
        $res = $dbObj->query($sql);
        
        if($res){
            while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
                $entries = $result['entries'];
            return(is_numeric($entries) && ($entries > (int)0)) ? true : false;

        }
        return false;

    }
}

?>


