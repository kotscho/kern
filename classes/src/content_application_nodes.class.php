<?php
namespace classes\src;
class content_application_nodes{
    
    var $articles;//kdos due to "historical" reasons, the so called contents are...articles.Totally confuseing need to be changed asap.
    var $category_id;
    var $content_id;

    function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }
    
    function getByID($catid){
        $sql = "SELECT NC.CONTENT_ID, N.TITLE FROM content_application_nodes NC, content N
                    WHERE 
                    NC.CONTENT_ID = N.ID
                    AND 
                    NC.APPLICATION_NODES_ID=".safe_sql($catid);
        $res=$this->dbObj->query($sql);

        if(!$res)  
            return false;
        $c=0;
        while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->articles[$c]['id'] = $result['CONTENT_ID'];
            $this->articles[$c]['title'] = $result['TITLE'];
            $c++;
        }
    }
    
    function deleteAllByID($nid){
        $sql .= "DELETE FROM content_application_nodes WHERE APPLICATION_NODES_ID=".safe_sql($nid);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    function deleteSpecificByID($nid, $cid){
        $sql .= "DELETE FROM content_application_nodes WHERE CONTENT_ID=".safe_sql($nid)." AND ";
        $sql .= " CATEGORY_ID =".safe_sql($cid);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    
    function assignArticle($nid, $cid){
        $sql .= "INSERT INTO content_application_nodes (CONTENT_ID, APPLICATION_NODES_ID) values (".safe_sql($nid).",";
        $sql .=  " ".safe_sql($cid).")";
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
}
?>