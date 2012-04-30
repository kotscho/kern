<?php
//class.comments.php
//everything is theoretically commentable
namespace classes\src;
//kdos: will autoload detect this dependency...or what
use classes\src\members;

class comments extends members {
    
var $ID;
var $type;
var $object_id;
var $content;
var $created;
var $posted_by;
var $dbObj;
var $isCommentable;
var $status;
var $commentsdata;
    
function  __construct($dbobj){
    $this->dbObj = $dbobj;
}    
    
function createCommentObj($type, $referingObjectId){
    $this->type = $type;
    $this->object_id = $referingfObjectId;
}

function lookup($id){
    $sql = "SELECT COMMENTABLE FROM ".$this->type. " WHERE ID= ".$id;
    $res=$this->dbObj->query($sql);
    while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
        $this->isCommentable = $result['COMMENTABLE'];
}
 
function deleteComment($commentId){
    $sql = "DELETE FROM comments WHERE ID=".safe_sql($commentId);
    $res=$this->dbObj->query($sql); 
    return(($res==true)?true:false);
}
 
 function toggleCommentStatus($commentId, $statusid){
    $sql = "UPDATE comments SET STATUS=".safe_sql($statusid)." WHERE ID=".safe_sql($commentId);
    $res=$this->dbObj->query($sql); 
    return(($res==true)?true:false);
}
 
 function getCommentsDataById($commentid){
     $sql = "SELECT * FROM comments WHERE ID=".safe_sql($commentid);
        $res=$this->dbObj->query($sql);
        $c=0;
        while($comment=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->id = $comment['ID'];
            $this->content= $comment['CONTENT'];
            $this->created = date('d-m-Y, h:i:s', $comment['CREATED']);
            $this->posted_by = $this->getMemberName($comment['POSTED_BY']);
            $this->type = $comment['TYPE'];
            $this->status = $comment['STATUS'];
        }
        return(!empty($this->id)?true:false);
     
}

function getPagedItems(&$_this, $ordering=''){//paging object
	      	$sql= "SELECT * FROM comments ORDER BY ".(empty($ordering)?'ID':$ordering); 
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->commentsdata[$c]['id']=$res['ID'];
            $this->commentsdata[$c]['content']=$res['CONTENT'];
            $this->commentsdata[$c]['created']=$res['CREATED'];
            $this->commentsdata[$c]['posted_by']=$this->getMemberName($res['POSTED_BY']);
            $this->commentsdata[$c]['type']=$res['TYPE'];
            $this->commentsdata[$c]['status']=$res['STATUS'];
            $this->commentsdata[$c]['object_id']=$res['OBJECT_ID'];
            $c++;
        }
	    return(is_array($this->commentsdata)?true:false);	
	 }
 
  
function renderAllComments($referingObjectId) {
    $sql = "SELECT * FROM comments WHERE OBJECT_ID =".safe_sql($referingObjectId).' AND STATUS = 1 ORDER BY CREATED DESC';
    $res=$this->dbObj->query($sql); 
    $c=0;
    //die($sql);
    while($allcomments = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
        $relatedComments[$c]['content'] = $allcomments['CONTENT'];
        $relatedComments[$c]['member'] = $this->getMemberName($allcomments['POSTED_BY']);
        $relatedComments[$c]['created'] = $allcomments['CREATED'];
        $c++;
    }
    //var_dump($relatedComments);
    //die();
    return(is_array($relatedComments)?$relatedComments:false);
}

function hasComments($referingObjectId){
    $sql = "SELECT COUNT(ID) as ttl FROM comments WHERE OBJECT_ID = ".safe_sql($referingObjectId). ' AND STATUS = 1';
    $res=$this->dbObj->query($sql);
    
    while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
        $total_rows=$result['ttl'];
    //ie($total_rows);
    return(($total_rows > 0)? $total_rows :false);
}

function massStatusChange($array, $id){
    foreach($array as $k => $v)
        $this->toggleCommentStatus($v, $id);
}

function massDelete($array){
    foreach($array as $k => $v)
        $this->deleteComment($v);
}

function hideComments(){
    
}

}//end of class comments

class comments_helper{
    
public static function addComment($memberid, $type, $object_id, $content ){
    global $dbObj;
    $sql  = "INSERT INTO comments SET "; 
    $sql .= " TYPE=".safe_sql($type).",";
    $sql .= " OBJECT_ID=".safe_sql($object_id).",";
    $sql .= " CONTENT=".safe_sql($content).",";
    $sql .= " CREATED=".safe_sql(time()).",";
    $sql .= " POSTED_BY=".safe_sql($memberid);
    
    $res=$dbObj->query($sql); 
    return(($res==true)?true:false);
}

public static function createSelect($id=0, $active='true' , $mode=1, $fire){
    global $statusDescr;
		if($active == 'false')
			$disabled =' disabled="disabled" ';
		else
			$disabled = '';
		$out.="<select ".$disabled." id=\"statusselect\" name=\"statusselect\" onChange=\"".$fire."\">";
        if($mode == 2)
           $out.= '<option selected="selected" value="default" >Αλλαγή κατάστασης</option>';
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

public static function ceateCheckFieldForm($id){
    $form = '<input style="width: 12px;" name="checkall[]" type="checkbox" value="'.$id.'" />';
    return $form;
}

public static function drawSubMenu($id, $type){
    $out.='<div class="admin-submenu"><img border="0" src="img/arrow_ltr.png"  /></div>';
    $out.='<div class="admin-submenu-inner-div" >';
    $out.='<a href="javascript: void(null);" onclick="__checkall(this);">επιλογή όλων</a>';
    $out.='&nbsp;και&nbsp;';
    $out.='<a href="javascript: void(null);" onclick="massDeleteItem(\''.$type.'\');">διαγραφή</a>&nbsp;/&nbsp;';
    $out.= comments_helper::createSelect(0, 'true',2, 'this.form.submit()');
    $out.="</div>";
    $out.="</div>";
    
    return $out;
}

}


?>