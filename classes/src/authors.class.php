<?php
//class.authors.php
namespace classes\src;

class authors {
    
    var $dbObj;
    var $id;
    var $name;
    var $whoami;
    var $authordata;
    
    function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }

    function get_items(){
		$sql ="SELECT * FROM authors";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($author=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->authordata[$c]['id']=$author['ID'];
            $this->authordata[$c]['name']=$author['NAME'];
            $this->authordata[$c]['whoami']=$author['WHOAMI'];
			$c++;
		}
		return(is_array($this->authordata)?true:false);
	}
    
    function getPagedItems(&$_this, $ordering=''){//paging object
	      	$sql= "SELECT * FROM authors ORDER BY ".(empty($ordering)?'ID':$ordering); 
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->authordata[$c]['id']=$res['ID'];
            $this->authordata[$c]['name']=$res['NAME'];
            $this->authordata[$c]['whoami']=$res['WHOAMI'];
            $c++;
        }
	    return(is_array($this->authordata)?true:false);	
	 }
     
    function getAuthorDataById($id){
        $sql = "SELECT * FROM authors WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        $c=0;
        while($author=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->id = $author['ID'];
            $this->name= $author['NAME'];
            $this->whoami = $author['WHOAMI'];
        }
        return(!empty($this->id)?true:false);
     }
     
    function deleteAuthor($id){
        $sql = "DELETE FROM authors WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    function insert(){
		$sql = "INSERT authors SET
                NAME=".safe_sql($this->name).",
                WHOAMI=".safe_sql($this->whoami);
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function update($id){
		$sql = "UPDATE authors SET 
                NAME=".safe_sql($this->name).",
                WHOAMI=".safe_sql($this->whoami)."
				WHERE ID=".safe_sql($id);
		$res=$this->dbObj->query($sql);
		
		return($res==true?true:false);
	}
}

class authorsHelpers{
    
}



?>