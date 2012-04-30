<?php
namespace classes\system;
class application_nodes_stages{
	
	var $filenames = array();
	var $behaviour;
	var $stagepath = '../frontend/tpl/stage';

	function __construct(){
		$this->readFolder();
	}

	function readFolder(){
		if(!is_dir($this->stagepath))
			return FALSE;
		$handle = opendir($this->stagepath);
		if(!is_dir())
		while(($file=readdir($handle)) !== FALSE){
			if($file != '.' & $file != '..')
				$this->filenames[] = reset(explode('.',$file));
		}
		return TRUE;
	}
	
	function stagesSelect($stagename=''){
		
		$out = '<select name="stagename" >';
		for($z=0; $z<count($this->filenames); $z++)
			$out.= '<option value="'.$this->filenames[$z].'"'.(($this->filenames[$z] == $stagename)?' selected="selected" ':'').'>'.$this->filenames[$z].'</option>';
		$out.='</select>';
		
		return $out;
		
	}
	
}
?>