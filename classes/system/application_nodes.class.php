<?php
namespace classes\system;
error_reporting(E_ERROR);
#=============== application_nodes objects =============================#
#							     		#
#=======================================================================#
class application_nodes{
	
	var $nodedata = array();
	var $ID;
	var $status;
	var $parent_ID;
	var $startdate;
	var $enddate;
	var $name;
	var $description;
    var $type;
    var $stagename;
    var $mods;//mods are added comma-seperated
    var $category_unit;//kdos added on 18/12/2011
	function __construct(&$dbobj){
		$this->dbObj = $dbobj;
	}
	
	function getNodeByID($id){
		$sql = "SELECT * FROM application_nodes WHERE ID = ".safe_sql($id);
		$res=$this->dbObj->query($sql);
		while($nodes = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->ID = $nodes['ID'];
			$this->status = $nodes['status'];
			$this->parent_ID = $nodes['parent_ID'];
			$this->startdate = $nodes['startdate'];
			$this->enddate = $nodes['enddate'];
			$this->name = $nodes['name'];
			$this->description = $nodes['description'];
            $this->type = $nodes['type'];
            $this->stagename = $nodes['stagename'];
            $this->category_unit = $nodes['category_unit'];
		}
	}
    //kotscho here...
    function getNodeByType($id){
		$sql = "SELECT * FROM application_nodes WHERE ID = ".safe_sql($id);
		$res=$this->dbObj->query($sql);
		while($nodes = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->ID = $nodes['ID'];
			$this->status = $nodes['status'];
			$this->parent_ID = $nodes['parent_ID'];
			$this->startdate = $nodes['startdate'];
			$this->enddate = $nodes['enddate'];
			$this->name = $nodes['name'];
			$this->description = $nodes['description'];
            $this->type = $nodes['type'];
            $this->stagename = $nodes['stagename'];
            $this->category_unit = $nodes['category_unit'];
		}
	}


	function insertNode(){
		$sql .= "INSERT application_nodes SET ";
		$sql .= " status = ".safe_sql($this->status).",";
		$sql .= " parent_ID = ".safe_sql($this->parent_ID).",";
		$sql .= " startdate = ".safe_sql($this->startdate).",";
		$sql .= " enddate = ".safe_sql($this->enddate).",";
		$sql .= " name = ".safe_sql($this->name).",";
		$sql .= " type = ".safe_sql($this->type).",";
		$sql .= " stagename = ".safe_sql($this->stagename).",";
		$sql .= " description = ".safe_sql($this->description).",";
                $sql .= " category_unit = ".$this->category_unit;
		$res=$this->dbObj->query($sql);
		return($res?true:false);
	}
	
	function deleteNode($id, $unit){
        $current = explode('_', $id);
		$sql = "DELETE FROM application_nodes
                        WHERE parent_ID=".safe_sql($current[1]). 'AND category_unit='.safe_sql($unit);
		$res=$this->dbObj->query($sql);
		if(!$res){
                    return false;
		}
		else{
                    $sql = "DELETE FROM application_nodes
                            WHERE
                            (ID=".safe_sql($current[1])." OR parent_ID=".safe_sql($current[1]).') AND category_unit='.safe_sql($unit) ;
                    $res=$this->dbObj->query($sql);
		}
		return($res?true:false);
	}
	//here
	function deleteRelatedNodes($parentID){
		$sql = "";
	
	}
	
	function toggleAccessabillity($type){//set a node  to member of public
		$sql = "UPDATE application_nodes SET type=".safe_sql($type)." WHERE ID=".safe_sql($this->ID);
		$res=$this->dbObj->query($sql);
		return($res?true:false);
	}
	
	function updateNode($id){
		$sql .= "UPDATE application_nodes SET ";
		$sql .= " status = ".safe_sql($this->status).",";
		$sql .= " parent_ID = ".safe_sql($this->parent_ID).",";
		$sql .= " startdate = ".safe_sql($this->startdate).",";
		$sql .= " enddate = ".safe_sql($this->enddate).",";
		$sql .= " name = ".safe_sql($this->name).",";
		$sql .= " type = ".safe_sql($this->type).",";
		$sql .= " stagename = ".safe_sql($this->stagename).",";
		$sql .= " description = ".safe_sql($this->description).",";
                $sql .= " category_unit = ".$this->category_unit;
		$sql.=" WHERE ID = ".safe_sql($id);
		$res=$this->dbObj->query($sql);
		return($res?true:false);
	}
	
    
    function changeRelation($nodeid, $newparent){
        
        $sql = "SELECT parent_ID FROM application_nodes WHERE ID = ".safe_sql($newparent);
        $res = $this->dbObj->query($sql);
        while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $parents_parentid = $result['parent_ID'];
        if($nodeid == $parents_parentid)//parent/child swap...shouldn't be allowed should it?
            return false;
        if($nodeid == $newparent)//can't copy a node into itself...
             return false;
        $sql = "UPDATE application_nodes SET parent_ID=".safe_sql($newparent)." WHERE ID=".safe_sql($nodeid);
        $res = $this->dbObj->query($sql);
        return($res?true:false);
    }
    
	function getAllNodes($parentid=0, $unit_id){ 
		
		$sql = "SELECT 
                    COUNT(NC.CATEGORY_ID) AS articles,
                    AP.ID, AP.status,AP.parent_ID,AP.startdate,AP.enddate,AP.name,AP.description,AP.type
                    FROM
                    application_nodes AP
                    LEFT JOIN content_category NC
                    ON
                    AP.ID = NC.CATEGORY_ID
                    WHERE 
                    AP.parent_ID = ". safe_sql($parentid)."
                    AND
                    category_unit = ".safe_sql($unit_id)."
                    GROUP BY AP.ID";
                   
		$res=$this->dbObj->query($sql);
		$c=0;
		while($nodes = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$nodedata[$c]['ID'] = $nodes['ID'];
			$nodedata[$c]['status'] = $nodes['status'];
			$nodedata[$c]['parent_ID'] = $nodes['parent_ID'];
			$nodedata[$c]['startdate'] = $nodes['startdate'];
			$nodedata[$c]['enddate'] = $nodes['enddate'];
			$nodedata[$c]['name'] = $nodes['name'];
			$nodedata[$c]['description'] = $nodes['description'];
                        $nodedata[$c]['articles'] = $nodes['articles'];
                        $nodedata[$c]['type'] = $nodes['type'];
                        $nodedata[$c]['stagename'] = $nodes['stagename'];
                        $nodedata[$c]['category_unit'] = $nodes['category_unit'];
			$c++;
		}
		
		return(!empty($nodedata)?$nodedata:FALSE);		
	}

function renderTree($unit_id, $parent=0){
	$arr=$this->getAllNodes($parent, $unit_id);
	if(!empty($arr)){
       
		for($b=0; $b<count($arr); $b++){
             if($arr[$b]['articles'] > 1)
                $artnum='άρθρα';
            else
                $artnum='άρθρο';
        	$t.='{';
         	$t.='"text": "'.$arr[$b]['name'].' (<i>'.$arr[$b]['type'].'</i>)'. (($arr[$b]['articles'] > 0)?' ('.$arr[$b]['articles'].' '.$artnum.')':'').'"';
         	$t.=',"id":"'.$arr[$b]['ID'].'"';
         	if($this->getAllNodes($arr[$b]['ID'],$unit_id) !== FALSE){
            	$t.=',"children":['.$this->renderTree($unit_id, $arr[$b]['ID']).']';
         	}
         	else{
         		$t.=$this->renderTree($unit_id, $arr[$b]['ID']);
         	}
         	$t.='}';
         	$t.=',';
		}
	}
	
	$t=str_replace(',]',']',$t);
	$t=str_replace(',}','}',$t);
	
	return $t;
}

function initTree($unit_id){
	$tree=$this->renderTree($unit_id);
	//further cleanup
	$tree=str_replace('},]','}]',$tree);
	$tree=str_replace(',"children":[]','', $tree);
	$tree=substr_replace($tree,'',strpos($tree,',',mb_strlen($tree)-1));
	
	return '['.($tree).']';
}
//kdos: unit_id
function renderMenu($fullprefix='', $previousIDS=null,$parent=0, $class=''){
   global $style;
    $arr=$this->getAllNodes($parent);
    $styleclass=$class;
    if(!empty($styleclass) ){//only used for the sitemap
        $style='class="'.$styleclass.'"';
    }
    else{
        '';
    }
    if(!empty($arr)){
        $menu.='<ul>';
        for($b=0; $b<count($arr); $b++){
            if($this->getAllNodes($arr[$b]['ID']) !== FALSE){
                $menu.='<li><a '.$style.' href="'.APP_WITH_SLASH.'/'.$fullprefix.$arr[$b]['description'].'">'.$arr[$b]['name'].'</a>';
                $fullprefix .=  $arr[$b]['description'].'/';
                $previousIDS[$arr[$b]['ID']] = $arr[$b]['description'];
                $menu.= $this->renderMenu($fullprefix, $previousIDS,$arr[$b]['ID'], 0, 'more'); 
		$menu.='</li>';
            }//main.php?itemid='.$arr[$b]['ID'].'
            else{
                $menu.=  '<li><a '.$style.' href="'.APP_WITH_SLASH.'/'.$fullprefix.$arr[$b]['description'].'">'.$arr[$b]['name'].'</a></li>';
                $menu.= $this->renderMenu($fullprefix, $previousIDS,$arr[$b]['ID'], 0, 'more');
            }
            if($arr[$b]['parent_ID'] == (int)0){
                $fullprefix = '';
                unset($previousIDS);
            }
            if(array_key_exists($arr[$b]['parent_ID'], $previousIDS)){
                $keys=array_keys($previousIDS);
                $fullprefix = '';
                for($z=0; $z<count($keys); $z++){
                    $fullprefix .= $previousIDS[$keys[$z]].'/';
                    if($keys[$z] == $arr[$b]['parent_ID'])
                        break;
                }
            }
        }
	
        $menu.='</ul>';
    }
    return $menu;
}

function assignAccesstype($parent, $type){//kdos: recursively assign accessabillity types to childs - 20122011 - yes, but the corresponding table is full of "menu names" and not types...
	
	$childs = $this->getChilds($parent);
	if(is_array($childs)){
		for($x=0; $x<count($childs); $x++){
			$res=$this->dbObj->query('UPDATE application_nodes SET `type`='.safe_sql($type).' WHERE parent_ID='.safe_sql($parent));
			if($this->getChilds($childs[$x]['ID']) !== FALSE)
				$this->assignAccesstype($childs[$x]['ID'], $type);
		}
	}
	else{
		return TRUE;
	}	
}


function getChilds($parent){
	$sql = "SELECT ID, parent_ID FROM application_nodes WHERE parent_ID=".safe_sql($parent);
	$res=$this->dbObj->query($sql);
	$result=$res->fetchAll(MDB2_FETCHMODE_ASSOC);


	return(is_array($result)?$result:FALSE);
}

function createRewriteRules($fullprefix='', $previousIDS=null, $parent=0){
    
    $arr=$this->getAllNodes($parent);
    if(!empty($arr)){
        for($b=0; $b<count($arr); $b++){
            $rules .= 'RewriteRule ^'.$fullprefix.$arr[$b]['description'].'$ frontend/source/main.php?itemid='.$arr[$b]['ID']."\n";
            $rules .= 'RewriteRule ^'.$fullprefix.$arr[$b]['description'].'/([0-9]+).htm$ frontend/source/main.php?art=$1&itemid='.$arr[$b]['ID']."\n";
            $rules .= 'RewriteRule ^'.$fullprefix.$arr[$b]['description'].'/posted/([0-9]+).htm$ frontend/source/main.php?itemid='.$arr[$b]['ID'].'&art=$1&posted=true'."\n";
            $fullprefix .=  $arr[$b]['description'].'/';
            $previousIDS[$arr[$b]['ID']] = $arr[$b]['description'];
            $rules .= $this->createRewriteRules($fullprefix, $previousIDS, $arr[$b]['ID']);
            
            if($arr[$b]['parent_ID'] == (int)0){
                $fullprefix = '';
                unset($previousIDS);
            }
            if(array_key_exists($arr[$b]['parent_ID'], $previousIDS)){
                $keys=array_keys($previousIDS);
                $fullprefix = '';
                for($z=0; $z<count($keys); $z++){
                    $fullprefix .= $previousIDS[$keys[$z]].'/';
                    if($keys[$z] == $arr[$b]['parent_ID'])
                        break;
                }
            }
        }
    }
    return $rules;
}

function buildFull($pid, &$fullpath='', &$arr=null){
   
    $sql = "SELECT parent_ID, description FROM application_nodes WHERE ID=".safe_sql($pid);
    $res=$this->dbObj->query($sql);
    
    while($upperLevel = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
        $fullpath .= '@'.$upperLevel['description'];
        $parent = $upperLevel['parent_ID'];
    
        if($upperLevel['parent_ID'] > 0){
            $this->buildFull($parent, $fullpath, $arr);
        }
        else{
            $arr = explode('@', $fullpath);
        }
    }
    $readyPath = implode('/', array_reverse($arr));
    return $readyPath;
}

function __getParent($id, $css, $init=0, $search=false, $sitemap=false){//pathway builder
    
    $sql = "SELECT ID,parent_ID,name, description FROM application_nodes WHERE ID=".safe_sql($id);
    $res=$this->dbObj->query($sql);
    if($search !== true){
        while($current = $res->fetchRow(MDB2_FETCHMODE_ASSOC) ){
            if($id == (int)1)
                continue;
            $nodeParentID=$current['parent_ID'];
            $nodeName=$current['name'];
            $nodeID=$current['ID'];
            $nodeDescr=$current['description'];
            if(is_numeric($nodeID)){
                if($id != $init){
                    $pathway[] = '<a class="'.$css.'" href="'.APP_WITH_SLASH.'/'.$this->buildFull($nodeParentID).''.$nodeDescr.' ">'.$nodeName.'</a>|';//modifying here
                }    
                else{
                    $pathway[] = $nodeName;
                }
                if(is_numeric($nodeParentID)){
                    $pathway[] = $this->__getParent($nodeParentID, $css, $init, $search);
                }
            }
        }
    }
    $pathway = array_reverse($pathway);
    $path = implode(' ', $pathway);
    if(!is_numeric($nodeParentID) && $id != (int)1 ){
       $start = '<a class="'.$css.'" href="'.APP_WITH_SLASH.'/home">Αρχική</a> &gt;';
    }
    if($search == true)
        return $start.'Αναζήτηση';
    if($sitemap == true)
        return $start.'Sitemap';
    return $start.str_replace('|', '&gt;', $path);//ID 1 == start page here!!!
}

function getDescriptionByID($id){
    $sql = "SELECT description FROM application_nodes WHERE ID=".safe_sql($id);
    $res = $this->dbObj->query($sql);
    while($descr = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
        $description = $descr['description'];
    return (!empty($description)?$description:FALSE);
}


function showModNames($id){
	$sql = "SELECT mods FROM application_nodes WHERE ID=".safe_sql($id);
	$res = $this->dbObj->query($sql);
	while($mods = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
		$modules = $mods['mods'];
	return(!empty($modules)?$modules:false);
}

function assignMods($application_node_id, $mods=array()){//assigns mods to a specific category
	$sql = "UPDATE application_nodes SET mods=".safe_sql(implode(',',$mods))." WHERE ID=".safe_sql($application_node_id);
	$res=$this->dbObj->query($sql);
	return(($res)?true:false);
}


}//end of class

class application_nodes_helper{
    public static function getNameByID($id){
            global $dbObj;
            $sql = "SELECT name FROM application_nodes WHERE ID=".safe_sql($id);
            $res = $dbObj->query($sql);
            while($name = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
                $result = $name['name'];
            return (!empty($result)?$result:'');
    }
    
    public static function lastInsertedID(){
            global $dbObj;
            $sql = "SELECT LAST_INSERT_ID() as CID FROM application_nodes";//unfortunately very mysql specific...
            $res=$dbObj->query($sql);
            while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
                $categoryid = $result['CID']; 
            return $categoryid;
     }       
            
    public static function menuTypeSelect($id=0){
        global $dbObj;
        $sql = "SELECT ID, name FROM menus";
        $res=$dbObj->query($sql);
        $c=0;
        while($temp = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $allMenus[$c]['id'] = $temp['ID'];
            $allMenus[$c]['name'] = $temp['name']; 
            $c++;
        }
        $out = '<select name="menus">';
        for($z=0; $z<count($allMenus); $z++)
            $out.= '<option '.(($id == $allMenus[$z]['id'] && $id > 0) ? 'selected="selected" ' : '' ).' value="'.$allMenus[$z]['id'].'">'.$allMenus[$z]['name'].'</option>';
        $out.= '</select>';
        return $out;
    }
    
    public static function setNodeType($type){
    	 $out = '<select name="type">';
    	 $out.=' <option '.(($type == 'member') ? 'selected="selected" ' : '' ).' value="member">member</option>';
    	 $out.=' <option '.(($type == 'public') ? 'selected="selected" ' : '' ).' value="public">public</option>';
    	 $out.= '</select>';
    	 return $out;
    }

    public static function getCategUnitById($id){
        global $dbObj;
        $sql = "SELECT `NAME` FROM categ_units WHERE ID=".safe_sql($id);
        $res = $dbObj->query($sql);
        while($result = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
            $name = $result['NAME'];
        return(!empty($name) ? $name:'');
    }
}

//$__tree = new application_nodes($dbObj);
//print $__tree->initTree();

?>
