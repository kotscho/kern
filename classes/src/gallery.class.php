<?php
namespace classes\src;

class gallery {
	
	var $dbObj;
	var $id;
	var $name;
	var $created;
	var $created_by;
	var $fullname;
	var $mimetype;
	var $gallerydata;
	
	
	 function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }

     function get_items(){
		$sql ="SELECT * FROM gallery";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($gallery=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->gallerydata[$c]['id']=$gallery['ID'];
            $this->gallerydata[$c]['name']=$gallery['NAME'];
            $this->gallerydata[$c]['created']=$gallery['CREATED'];
            $this->gallerydata[$c]['created_by']=$gallery['CREATED_BY'];
			$c++;
		}
		return(is_array($this->gallerydata)?true:false);
	}
    
    function getPagedItems(&$_this, $ordering=''){//paging object
	      	$sql= "SELECT * FROM gallery ORDER BY ".(empty($ordering)?'ID':$ordering); 
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->gallerydata[$c]['id']=$res['ID'];
            $this->gallerydata[$c]['name']=$res['NAME'];
            $this->gallerydata[$c]['created']=$res['CREATED'];
            $this->gallerydata[$c]['created_BY']=$res['CREATED_BY'];
            $c++;
        }
	    return(is_array($this->gallerydata)?true:false);	
	 }
     
    function getGalleryDataById($id){
        $sql = "SELECT * FROM gallery g, gallery_content gc, gallery_gallery_content ggc 
        		WHERE
        		g.ID=".safe_sql($id)."
        		AND 
        		ggc.GALLERY_ID = g.ID
        		AND
        		gc.ID = ggc.GALLERY_CONTENT_ID";
        		
        		
        $res=$this->dbObj->query($sql);
        while($gallery=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->id = $gallery['ID'];
            $this->name= $gallery['NAME'];
            $this->created = $gallery['CREATED'];
            $this->created_by = $gallery['CREATED_BY'];
            $this->fullname = $gallery['FULLNAME'];
            $this->mimetype = $gallery['MINE'];
        }
        return(!empty($this->id)?true:false);
     }
     
    function deleteGallery($id){
        $sql = "DELETE FROM gallery WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    function insert(){//the actuall gallery creation
		$sql = "INSERT gallery SET
                NAME=".safe_sql($this->name).",
                CREATED=".safe_sql($this->created).",
                CREATED_BY=".safe_sql($this->created_by);
                
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function update($id){
		$sql = "UPDATE galley SET 
                NAME=".safe_sql($this->name).",
                CREATED=".safe_sql($this->created).",
                CREATED_BY=".safe_sql($this->created_by)."
				WHERE ID=".safe_sql($id);
		$res=$this->dbObj->query($sql);
		
		return($res==true?true:false);
	}
	
	//handler called upon upload
	function insert_gallery_content(){
		$sql = "INSERT gallery_content SET
                FULLNAME=".safe_sql($this->fullname).",
                MIME=".safe_sql($this->mimetype).",
                CREATED=".safe_sql($this->created).",
                CREATED_BY=".safe_sql($this->created_by);
                
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	
	function assign_gallery_content($galleryid, $gallery_content_id, $mode="add"){
		if($mode == "add"){
			$sql = "INSERT gallery_gallery_content SET
            GALLERY_ID=".safe_sql($galleryid).",
            GALLERY_CONTENT_ID=".safe_sql($gallery_content_id);
		}
		elseif($mode == "del"){//unassign
			$sql = "DELETE FROM gallery_gallery_content WHERE 
			GALLERY_ID=".safe_sql($galleryid).",
            GALLERY_CONTENT_ID=".safe_sql($gallery_content_id);
		}
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function delete_gallery($id){
		$sql = "DELETE FROM gallery WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
	}
	
	function delete_gallery_content($id){
		$sql = "DELETE FROM gallery_gallery_content WHERE GALLERY_CONTENT_ID=".safe_sql($id);
		if($this->dbObj->query($sql)){
			$sql = "DELETE FROM gallery_content WHERE ID=".safe_sql($id);
        	$res=$this->dbObj->query($sql);
        	return(($res)?true:false);
		}
		else{
			return false;
		}
	}
	
}


class galleryHelpers{
    
}

?>