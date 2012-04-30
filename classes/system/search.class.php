<?php
namespace classes\system;
//class.search.php
//to do's: add Porter stamming method for greek...
class search {
    var $term;
    var $fields;
    var $module;
    var $dbObj;
    var $current_query;
    var $where_clause;
    var $num_rows;
    var $performance;
    var $customarray;//used to pass an external select box(actually an external table, like status ect.)
    				// - rule: 1st key represents the name of the select box, the following keys the values.
    
    
    function __construct(&$dbobj, $mod='', $customarray=null){
    	$this->customarray = $customarray;
    	$this->module = $mod;
        $this->dbObj = $dbobj;
    }
    
    function fullSearch($needle){
        $sql = "SELECT NT.TITLE, NT.ID, NTC.APPLICATION_NODES_ID, AN.parent_ID, AN.description FROM content NT, content_application_nodes NTC, application_nodes AN
                    WHERE (
                    NT.TEASER 
                    LIKE ".safe_sql('%'.$needle.'%')."
                    OR 
                    NT.CONTENT 
                    LIKE ".safe_sql('%'.$needle.'%'). "
                    OR 
                    NT.TITLE 
                    LIKE ".safe_sql('%'.$needle.'%'). ")  
                    AND 
                    NT.ID=NTC.CONTENT_ID
                    AND
                    NTC.APPLICATION_NODES_ID = AN.ID
                    ";
        $res=$this->dbObj->query($sql);
        $c=0;
        while($search_result = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $search_total[$c]['title'] = $search_result['TITLE'];
            $search_total[$c]['id'] = $search_result['ID'];
            $search_total[$c]['application_nodes_id'] = $search_result['APPLICATION_NODES_ID'];
            $search_total[$c]['parent_id'] = $search_result['parent_ID'];
            $search_total[$c]['descr'] = $search_result['description'];
            $c++;
        }
        
        return (is_array($search_total)?$search_total:FALSE);
    }
    
    
    function adminSearch($searchfields=array()){
    	
    	$sql .= "SELECT * FROM ".($this->module); 
    			
    	foreach($searchfields as $k => $v){
    		if($v == 'ALL' || $k == 'submit_search' || $v == '')
    			unset($searchfields[$k]);
    	}
    	array_multisort($searchfields);
    	if(is_array($searchfields) && (count($searchfields) > 0)){
    		$where_clause = " WHERE ";
    		foreach($searchfields as $k => $v){
    			if(empty($v))
    				continue;
    			if((end(explode('_',$k)) == 'enum') || (end(explode('_',$k)) == 'id'))
    				$where_clause .= reset(explode('_', $k)).'='.safe_sql($v).(($k != (array_pop(array_keys($searchfields))))?' AND ': '');
    			if(end(explode('_',$k)) == 'text')
    				$where_clause .= reset(explode('_', $k)).' LIKE '.safe_sql('%'.$v.'%').(($k != (array_pop(array_keys($searchfields))))?' AND ': '');
    			
    		}
    		$sql .= $where_clause;
    	}
    	else{
    		return FALSE;
    	}
    	$this->where_clause = $where_clause;
    	$this->current_query = $sql;
    	$timer_start=microtime(true);	
    	$res=$this->dbObj->query($sql);
    	$this->performance = microtime(true) - $timer_start;	
    	if(!$res)
    		return FALSE;
    		
    	$searchresults = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
    	$this->num_rows = count($searchresults);
    	
       	return $searchresults;
    }
    
    function renderSearchMask($css=''){
    	$this->getDBFields();
		$output .= '<form method="post" name="search_'.$this->table.'" action="'.$_SERVER['PHP_SELF'].'" >';
		$output .= '<fieldset class="'.$css.'">';
		$output .= '<legend>Αναζήτηση '.$this->module.'</legend>';
		for($z=0; $z < count($this->fields); $z++){
			foreach($this->fields[$z] as $k => $v){
				if(strstr($v, 'password') !== FALSE)//search password: inactive
					continue;
				if($k == 'text')
					$output .= '<label for="'.$v.'">'.$v.'</label><input name="'.$v.'_text" type="'.$k.'" /><br /><br />';
				if($k == 'select'){
					$output.='<label for="'.$v.'">'.$v.'</label>';
					$output.='<select name="'.$v.'_enum">';
					$output.='<option value="ALL">ALL</option>';
					for($c=0; $c<count($this->fields[$z]['options']); $c++)
						$output.='<option value="'.$this->fields[$z]['options'][$c].'">'.$this->fields[$z]['options'][$c].'</option>';
					$output.='</select><br /><br />';
				}	
			}
		}
		//add the external selects
		if($this->customarray != null){
			$output.='<label for="'.$this->customarray[0].'">'.$this->customarray[0].'</label>';
			$output .= '<select name="'.$this->customarray[0].'_id">';
			$output .= '<option value="ALL">ALL</option>';
			for($x=1; $x<count($this->customarray); $x++)
				$output .= '<option value="'.$x.'">'.$this->customarray[$x].'</option>';
			$output.= '</select>';
		}
		$output .= '<input type="submit" class="input_button" value="Αναζήτηση" name="submit_search" />';	
		$output .= '</fieldset></form>';
		
		return $output;
	}
    
	function getDBFields(){
		$sql = "SHOW CREATE TABLE ".$this->module;
		$res=$this->dbObj->query($sql);
		if(!$res)
			return FALSE;
		$description = $res->fetchAll();
		$characteristics = explode("\n", $description[0][1]);
		for($z=0; $z<count($characteristics); $z++){
			 if(mb_strstr($characteristics[$z], 'varchar') !== FALSE){
			 	$this->fields[$z]['text'] = str_replace('`','',reset(explode(' ',trim($characteristics[$z]))));//specifiying the input type here (easy recognition upon formrendering)
			 }
			 if(mb_strstr($characteristics[$z], 'enum') !== FALSE){
			 	$start=mb_strpos(trim($characteristics[$z]), '\'');
			 	$end=mb_strpos(trim($characteristics[$z]), ')');
			 	$options = mb_substr(trim($characteristics[$z]), $start, ($end-$start));
			 	$options = str_replace('\'', '', $options);
			 	$this->fields[$z]['select'] = str_replace('`','',reset(explode(' ',trim($characteristics[$z]))));//specifiying the input type here (easy recognition upon formrendering)
			 	$this->fields[$z]['options'] = explode(',',$options);
			 }
		}
		array_multisort($this->fields);
		
		return TRUE;
	}
	
	function triggerResultDisplay($arr){
		if(count($arr) == (int)1){
			header('location: '.$this->module.'.php?edit=true&id='.$arr[0]['id']);
			exit();
		}
		else{
			return;
		}	
	}
	
	function resultInfo(){// i hate to do this, but...
		$_SESSION['performance_info'] = '&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;Total results:&nbsp;'.$this->num_rows.', in '.$this->performance.' seconds';
		return $_SESSION['performance_info'];
	}
	
}
?>
