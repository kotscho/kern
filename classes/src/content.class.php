<?php
#=============== content objects =========================#
#														   		#
#===============================================================#
namespace classes\src;

class content{
	
	var $contentdata = array();
	var $ID;
    var $name;
    var $title;
	var $content;
	var $created;
	var $created_by;
	var $status;
    var $updated_on;
    var $teaser;
    var $dbObj;
    var $commentable;
	
	
	function __construct(&$dbobj){
		$this->dbObj = $dbobj;
	}
	
	function get_items(){
		$sql ="SELECT * FROM content";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($content=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->contentdata[$c]['ID']=$content['ID'];
            $this->contentdata[$c]['title']=$content['TITLE'];
            $this->contentdata[$c]['name']=$content['NAME'];
			$this->contentdata[$c]['content']=$content['CONTENT'];
			$this->contentdata[$c]['created']=$content['CREATED'];
            $this->contentdata[$c]['status']=$content['STATUS'];
			$this->contentdata[$c]['created_by']=$content['CREATED_BY'];
            $this->contentdata[$c]['updated_on']=$content['UPDATED_ON'];
            $this->contentdata[$c]['teaser']=$content['TEASER'];
            $this->contentdata[$c]['commentable']=$content['COMMENTABLE'];
			$c++;
		}
		return(is_array($this->contentdata)?true:false);
	}
	
    
     function getPagedItems(&$_this, $ordering=''){//paging object
	      	$sql= "SELECT * FROM content ORDER BY ".(empty($ordering)?'ID':$ordering); 
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);	
	        	
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->contentdata[$c]['ID']=$res['ID'];
             $this->contentdata[$c]['title']=$res['TITLE'];
            $this->contentdata[$c]['name']=$res['NAME'];
            $this->contentdata[$c]['content']=$res['CONTENT'];
            $this->contentdata[$c]['created']=$res['CREATED'];
            $this->contentdata[$c]['status']=$res['STATUS'];
            $this->contentdata[$c]['created_by']=$res['CREATED_BY'];
            $this->contentdata[$c]['updated_on']=$res['UPDATED_ON'];
            $this->contentdata[$c]['teaser']=$content['TEASER'];
            $this->contentdata[$c]['commentable']=$content['COMMENTABLE'];
            $c++;
        }
       
	    return(is_array($this->contentdata)?true:false);	
	 }	
    
    
	function get_itemByID($id){
		$sql ="SELECT * FROM content WHERE ID =".safe_sql($id);
		$res=$this->dbObj->query($sql);

		while($content=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->ID=$content['ID'];
            $this->title=$content['TITLE'];
            $this->name=$content['NAME'];
			$this->content=$content['CONTENT'];
			$this->created=$content['CREATED'];
			$this->created_by=$content['CREATED_BY'];
			$this->status=$content['STATUS'];
			$this->updated_on=$content['UPDATED_ON'];
            $this->teaser=$content['TEASER'];
            $this->commentable=$content['COMMENTABLE'];
		}
        
		return(!empty($this->ID)?true:false);	
	}
	
	function add_item(){
		$sql = "INSERT content SET
                TITLE=".safe_sql($this->title).",
                NAME=".safe_sql($this->name).",
				CONTENT=".safe_sql($this->content).",
				CREATED=".safe_sql($this->created).",
				CREATED_BY=".safe_sql($this->created_by).",
				STATUS=".safe_sql($this->status).",
                TEASER=".safe_sql($this->teaser).",
                COMMENTABLE=".safe_sql($this->commentable).",
				UPDATED_ON=".safe_sql($this->updated_on);
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function update_item($id){
		$sql = "UPDATE content SET 
                TITLE=".safe_sql($this->title).",
                NAME=".safe_sql($this->name).",
				CONTENT=".safe_sql($this->content).",
				CREATED_BY=".safe_sql($this->created_by).",
                UPDATED_ON=".safe_sql($this->updated_on).",
                COMMENTABLE=".safe_sql($this->commentable).",
                TEASER=".safe_sql($this->teaser).",
				STATUS=".safe_sql($this->status)." WHERE ID=".safe_sql($id);
				
		$res=$this->dbObj->query($sql);  
		
		return($res==true?true:false);
	}
		
	function delete_item($id){//name is unique 
        $sql = "SELECT NAME FROM content WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $name = $result['NAME'];
		$sql = "DELETE FROM content WHERE ID=".safe_sql($id);
		$res=$this->dbObj->query($sql);
		if($res){
               /* if(is_file('pdf/'.$name.'.pdf')){ //temporarily deactivated: current php version has issus with DOMPDF
                    if(!unlink('pdf/'.$name.'.pdf'))
                        return false;
                }
                if(is_file('swf/'.$name.'.swf')){
                    if(!unlink('swf/'.$name.'.swf'))
                        return false;
                }*/
                $sql = "DELETE FROM content_material WHERE CONTENT_ID =".safe_sql($id);
                $res=$this->dbObj->query($sql);
                return (($res)?true:false);
         }
	}
    
    function html2pdf($html){
        if(!is_dir('pdf')){
            if(!mkdir('pdf', 0777))
                return -1;
        }
    }
    //hint: proceed an installation check, to see whether swftools package is installed
    function pdf2swfWrap($name){
        if(!is_dir('swf')){
            if(!mkdir('swf', 0777))
                return -1;
        }
        //die('pdf2swf pdf/'.$name.'.pdf -o swf/'.$name.'.swf');
        $cmd = EXEC_PATH.'pdf2swf pdf/'.$name.'.pdf -o swf/'.$name.'.swf';
        exec(escapeshellcmd($cmd));
    }
    
    function addPageBreak($html){   
            return str_replace('<p>{pb}</p>','<div style="page-break-after: always;"></DIV>', $html);
    }
}

class contentHelper{
    
public static function lastInsertedID(){
            global $dbObj;
            $sql = "SELECT LAST_INSERT_ID() as MID FROM content";//unfortunately very mysql specific...
            $res=$dbObj->query($sql);
            while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
                $contentid = $result['MID']; 
            return $contentid;
}

public static function getContentTitleByID($id){
            global $dbObj;
            $sql = "SELECT TITLE FROM content WHERE ID=".safe_sql($id);//unfortunately very mysql specific...
            $res=$dbObj->query($sql);
            while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
                $contenttitle = $result['TITLE']; 
            return $contenttitle;
}


public static function createSelect($id, $active='true'){
    global $statusDescr;
		if($active == 'false')
			$disabled =' disabled="disabled" ';
		else
			$disabled = '';
		$out.="<select ".$disabled." id=\"statusselect\" name=\"statusselect\">";
		for($z=1; $z<=2; $z++){
            if($z == $id)
                $sel = "selected=\"selected\"";
            else
                $sel = '';
				$out.="<option ".$sel." value=\"".$z."\">" .$statusDescr[$z]."</option>"; 
		}
		$out.="</select>";	
		return $out;	
}

public static function getAttachments($id){
            global $dbObj;
            $sql  .= "SELECT COUNT(CONTENT_ID) AS attachments FROM ";
            $sql .="content_material WHERE CONTENT_ID=".safe_sql($id);
            
            $res=$dbObj->query($sql);
            while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
                $attachments = $result['attachments']; 
            return ($attachments >=1) ? '&nbsp;<strong>(συν'.$attachments.')</strong>':'';
}


public static function getAuthor($id){
    global $dbObj;
    $sql = "SELECT name FROM authors WHERE ID=".safe_sql($id);
    $res = $dbObj->query($sql);
    while($fullname = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
        $author = $fullname['name'];
    return (!empty($author))?$author:FALSE;
}


public static function getAuthorDescr($id){
    global $dbObj;
    $sql = "SELECT whoami FROM authors WHERE ID=".safe_sql($id);
    $res = $dbObj->query($sql);
    while($whoami = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
        $author = $whoami['whoami'];
    return (!empty($author))?$author:FALSE;
}

public static function createAuthorsSelect($id=0){
    global $dbObj;
    $sql = "SELECT ID, NAME FROM authors";
    $res=$dbObj->query($sql);
    $c=0;
    while($temp = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
        $allAuthors[$c]['id'] = $temp['ID'];
        $allAuthors[$c]['name'] = $temp['NAME']; 
        $c++;
    }
    $out = '<select name="author">';
    for($z=0; $z<count($allAuthors); $z++)
        $out.= '<option '.(($id == $allAuthors[$z]['id'] && $id > 0) ? 'selected="selected" ' : '' ).' value="'.$allAuthors[$z]['id'].'">'.$allAuthors[$z]['name'].'</option>';
    $out.= '</select>';

    return $out;
}


public static function createCommentableSelect($value){
    $options = array('YES' => 'Ναί', 'NO' => 'Όχι');
    $out = '<select name="commentable">';
    foreach($options as $k => $v)
        $out .= '<option value="'.$k.'" '.(($k == $value)?'selected="selected"':'' ).'>'.$v.'</option>';
     $out .= '</select>';
     
     return $out;
}


}

?>