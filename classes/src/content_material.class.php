<?php
//class.content_material
namespace classes\src;

class content_material{
    
    var $current_attachments=null;
    var $material_id;
    var $content_id;

    function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }
    
    function getByID($contentid){
        $sql = "SELECT NM.MATERIAL_ID, M.NAME, M.TYPE FROM content_material NM, material M
                    WHERE
                    NM.MATERIAL_ID = M.ID
                    AND
                    NM.CONTENT_ID=".safe_sql($contentid);
        $res=$this->dbObj->query($sql);
        if(!$res)
            return false;
        $c=0;
        while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->current_attachments[$c]['id'] = $result['MATERIAL_ID'];
            $this->current_attachments[$c]['name'] = $result['NAME'];
            $this->current_attachments[$c]['type'] = $result['TYPE'];
            $c++;
        }
    }
    
    function deleteAllByID($nid){
        $sql .= "DELETE FROM content_material WHERE CONTENT_ID=".safe_sql($nid);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    function deleteSpecificByID($nid, $mid){
        $sql .= "DELETE FROM content_material WHERE CONTENT_ID=".safe_sql($nid)." AND ";
        $sql .= " MATERIAL_ID =".safe_sql($mid);
        //echo $sql."<br />";
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    
    function insertAttachment($nid, $mid){
        $sql .= "INSERT INTO content_material (CONTENT_ID, MATERIAL_ID) values (".safe_sql($nid).",";
        $sql .=  " ".safe_sql($mid).")";
        //echo $sql."<br />";
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
}
?>