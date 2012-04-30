<?php
//class.links.php
namespace classes\src;

class links {
    
    var $dbObj;
    var $id;
    var $vendor;
    var $url;
    var $info;
    var $created;
    var $linkdata;
    
    function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }

    function get_items(){
		$sql ="SELECT * FROM links";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($link=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->linkdata[$c]['id']=$link['ID'];
            $this->linkdata[$c]['vendor']=$link['VENDOR'];
            $this->linkdata[$c]['url']=$link['URL'];
            $this->linkdata[$c]['info']=$link['INFO'];
            $this->linkdata[$c]['created']=$link['CREATED'];
			$c++;
		}
		return(is_array($this->linkdata)?true:false);
	}
    
    function getPagedItems(&$_this, $ordering=''){//paging object
        $sql= "SELECT * FROM links ORDER BY ".(empty($ordering)?'ID':$ordering); 
        $c=0;
        $_this->turnPage($_GET['pager'], $sql);	
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->linkdata[$c]['id']=$res['ID'];
            $this->linkdata[$c]['vendor']=$res['VENDOR'];
            $this->linkdata[$c]['url']=$res['URL'];
            $this->linkdata[$c]['info']=$res['INFO'];
            $this->linkdata[$c]['created']=$res['CREATED'];
            $c++;
        }
	    return(is_array($this->linkdata)?true:false);	
	 }	
     
    function getLinkDataById($id){
        $sql = "SELECT * FROM links WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        $c=0;
        while($link=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->id = $link['ID'];
            $this->vendor= $link['VENDOR'];
            $this->url = $link['URL'];
            $this->info = $link['INFO'];
            $this->created = $link['CREATED'];
        }
        return(!empty($this->id)?true:false);
     }
     
    function deleteLink($id){
        $sql = "DELETE FROM links WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    function insert(){
		$sql = "INSERT links SET
                VENDOR=".safe_sql($this->vendor).",
                INFO=".safe_sql($this->info).",
                CREATED=".safe_sql($this->created).",
                URL=".safe_sql($this->url);
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function update($id){
		$sql = "UPDATE links SET 
                VENDOR=".safe_sql($this->vendor).",
                INFO=".safe_sql($this->info).",
                URL=".safe_sql($this->url)."
				WHERE ID=".safe_sql($id);
		$res=$this->dbObj->query($sql);  
		
		return($res==true?true:false);
	}
    
    function renderTagCloud($css=''){
        $this->get_items();
        for($z=0; $z<count($this->linkdata); $z++){
            $randFontSize = rand(_MIN_ , _MAX_);
            $out .= '<a class="'.$css.'" href="'.$this->linkdata[$z]['url'].'" target="_blank" style="font-size: '.$randFontSize.'px;" title="'.$this->linkdata[$z]['info'].'">'.$this->linkdata[$z]['vendor'].'</a>&nbsp;';
            if($z > 0 && ($z%2) || (($randFontSize >= 12) && (strlen($this->linkdata[$z]['vendor']) >= 10) ) )
                $out .= '<br />';
        }
        return $out;
    }
}


class linksHelpers{
    
}
?>