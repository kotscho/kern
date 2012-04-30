<?php
//class.material.php
namespace classes\system;

class material { //will extend from mother class
    
var $ID;
var $name;
var $type;//content MIME type
var $created;
var $materialdata = array();
    
function __construct(&$dbobj) {
    $this->dbObj = $dbobj;
}   
  
function getMateriallist(){

		$sql ="SELECT ID, NAME, TYPE, FROM material";
		$res=$this->dbObj->query($sql);
		while($material=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$_material[$material['ID']] = $material['ID'];
            $_material[$material['name']] = (string)$material['NAME'];
            $_material[$material['type']] = $material['TYPE'];
            $_material[$material['created']] = $material['CREATED'];
		}
		return $_material;
}

function getMaterialDataById($id){
        $sql ="SELECT ID, NAME, TYPE, CREATED FROM material WHERE ID=".safe_sql($id);
		$res=$this->dbObj->query($sql);
        while($material=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
                $this->ID = $material['ID'];
                $this->name = $material['NAME'];
                $this->type = $material['TYPE'];
                $this->created = $material['CREATED'];
        }
}

function getPagedMaterialData(&$_this, $ordering=''){//paging object
	      	$sql= "SELECT * FROM material ORDER BY ".(empty($ordering)?'ID':$ordering);
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);
	        	
	      while($res=mysql_fetch_assoc($_this->resultSet)){
	        	$this->materialdata[$c]['ID']=$res['ID'];
				$this->materialdata[$c]['name']=(string)$res['NAME'];
				$this->materialdata[$c]['type']=$res['TYPE'];
                $this->materialdata[$c]['created']=$res['CREATED'];
				$c++;
	        }
	        return(is_array($this->materialdata)?true:false);	
}	   


function insertMaterial(){
    $sql ="INSERT material  SET
                NAME =".safe_sql($this->name).",
                TYPE =".safe_sql($this->type).",
                CREATED =".safe_sql($this->created);
    $res=$this->dbObj->query($sql);
    return(($res==true)?true:false);
}

function deleteMaterial($id){
        $selectsql = "SELECT NAME, TYPE FROM material WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($selectsql);
        if($res){
            while($item = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
                $current['name'] = $item['NAME'];
                $current['type'] = $item['TYPE'];
            }
        }
        if(is_array($current)){ 
            $sql = "DELETE FROM material WHERE ID = ".safe_sql($id);
            $res=$this->dbObj->query($sql);
            if($res){ 
                if (!unlink('../material/'.$current['type'].'/'.$current['name']))
                   throw new Exception('couldn\'t delete file from filesystem: '.$current['name']);
                else
                     return true;
            }
            else{
                    return false;
            }
        } 
        else{
                return false;
        }
}

function updateMaterial($id){
    global  $oldvalue; //preselect tupel and hold values until update complete (for rollback) 
    
    $sql ="SELECT ID, NAME, TYPE FROM material WHERE ID = ".safe_sql($id);
    $original=$this->dbObj->query($sql);//loop only on error
    while($old = $original->fetchRow(MDB2_FETCHMODE_ASSOC)){
        $oldvalue['name'] = $old['NAME'];
        $oldvalue['type'] = $old['TYPE'];
    }
    $sql = "UPDATE material SET 
                NAME =".safe_sql($this->name).",
                TYPE =".safe_sql($this->type)."
                WHERE ID=".safe_sql($id);
    $res=$this->dbObj->query($sql);
    if($res){
        if(!rename('../material/'.$this->type.'/'.$oldvalue['name'], '../material/'.$this->type.'/'.$this->name ))
            throw new Exception('file renaming failed');// on catch: perform rollback with global oldvalue array
    }
    else{
        return false;
    }
}

function rollback($oldvalue, $id, $tablename){ //will be part of mother class
        global $oldvalue;
        
        $sql ="UPDATE {$tablename} SET ";
        foreach($oldvalue as $k => $v)
            $sql.= strtoupper($k)." =  ".safe_sql($v).(($v != end($oldvalue))?',':'');
        $sql.=" WHERE ID=".safe_sql($id);
        return($this->dbObj->query($sql)?true:false );
}


function process($file = array()){
    
    global $_MIME_TYPES;
     
    if(!in_array($file['uploadedfile']['type'], $_MIME_TYPES)){
        header('location: material.php?err=nomime');
        exit();
    }
    $path = '../material/'.basename($file['uploadedfile']['type']).'/';
    if(!is_dir($path) )
        mkdir($path, 0777);

    if(!move_uploaded_file($file['uploadedfile']['tmp_name'], $path.basename( $_FILES['uploadedfile']['name'])) ){
        throw new Exception("couldn't upload file");
    }
    else{
        $this->type = basename($file['uploadedfile']['type']); //plain, pdf ect.
        $res=$this->insertMaterial();
    }
    return ($res)? true:false;
}

}


#============HELPER ======================#
#                                                                                                           #
#=======================================#
class materialHelper{
    public static function getMaterialName($id){
        global $dbObj;
		$sql = "SELECT NAME FROM material WHERE ID=".safe_sql($id);
		$res=$dbObj->query($sql);
		while($material = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
			$materialname =  $material['NAME'];
		return $materialname; 
    }
}




?>