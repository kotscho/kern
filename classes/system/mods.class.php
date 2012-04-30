<?php
//class.mods.php
namespace classes\system;

class mods extends eventlogger {

	var $dbObj;
	var $name;
	var $type;
	var $status;
	var $base = '/var/www/html/kern/mods/';
	var $currentTarget;
	var $currentTargetFile;
	var $mod;
	var $modname;
	var $modsdata;
	
	function __construct(&$dbobj){
		$this->dbObj = $dbobj;
	}


	function unpack($archive){
		$this->currentTargetFile = basename($archive['uploadedfile']['name']);
		$this->mod = reset(explode('.', $this->currentTargetFile));//like mod@whatever
		$this->modname = end(explode('@',$this->mod));
		$this->currentTarget = $this->base.$this->currentTargetFile;
		//check if a module with the same name already exists:
		$sql = "SELECT `name`FROM mods WHERE `name` = ".safe_sql($this->modname);
		$res = $this->dbObj->query($sql);
		$nameExists = $res->fetchAll(MDB2_FETCHMODE_ASSOC);
		
		if(count($nameExists) >= 1){
			$this->logg('install-'.date('Y-m-d', time()), 'module name `'.$this->modname.'` exists '.$this->currentTarget);
			return false;
		}
			
		$zip = new ZipArchive();
                //die($archive['uploadedfile']['tmp_name'].'----'. $this->currentTarget);
		if(move_uploaded_file($archive['uploadedfile']['tmp_name'], $this->currentTarget)){
			if($zip->open($this->currentTarget)){
				$zip->extractTo($this->base);
				$zip->close();
                                //try catch everything here and delete the zip file using $this->deleteModFiles
				$this->logg('install-'.date('Y-m-d', time()), 'unpacked '.$this->currentTarget);
				return true;
			}
			else{
				$this->logg('install-'.date('Y-m-d', time()), 'failed unpack '.$this->currentTarget);
				return false;
			}
		}
		else{
			$this->logg('install-'.date('Y-m-d', time()), 'failed moving '.$this->currentTarget);
			return false;
		}
	}
	
	function install($archive){
		if(!$this->unpack($archive))
			return false;
		$hd = @opendir($this->base.$this->mod);
		
		if($hd){
			while(($file = readdir($hd)) !== false)
				if($file != '.' && $file != '..'){
					if((strstr($file, '.xml')) !== false)
						if($this->createModTables($this->base.$this->mod.'/'.$this->modname.'.xml')){
							$this->registerModule($this->base.$this->mod.'/'.$this->modname.'.xml');
						}
						else{
							$this->logg('install-'.date('Y-m-d', time()), 'installation failed for: '.$this->modname);
							return false;
						}
				}
			return true;
		}
		else{
			$this->logg('install-'.date('Y-m-d', time()), 'failed opening directory: '.$this->base.$this->mod);
			return false;
		}
	}
	
	function registerModule($xmlfile){
		
		$xml = simplexml_load_file($xmlfile);
	
		$sql = "INSERT `mods` SET name=".safe_sql($xml->module->name).",
				type = 'standard', 
				status = 'installed',
				author = ".safe_sql($xml->module->author).", 
				version = ".safe_sql($xml->module->version);
                
		$res = $this->dbObj->query($sql);
	
		return (($res)?true:$this->logg('install-'.date('Y-m-d', time()), 'module registration failed '.$xml->module->name));
	}
	
	//creates sql as described in the appropriate module xml file
	//NOTE: will only be executed if the xml file has a database section,
	//in other words: some modules don't use a database at all, others use existing tables or views etc.
	function createModTables($xmlfile){
		$xml = simplexml_load_file($xmlfile);
		if(empty($xml->module->database))
			return true;
		foreach($xml->module->database->table as $table){
			$sql .= 'CREATE TABLE `mod_'.$table['name'].'`(';
			for($z=0; $z < count($table->field); $z++){
				if($table->field[$z]['type'] != 'enum') 
					$sql .= '`'.$table->field[$z].'` ';
				$sql.=
				(!empty($table->field[$z]['type']) && ($table->field[$z]['type'] != 'enum')?$table->field[$z]['type'].' ':'');
				
				if($table->field[$z]['type'] == 'enum'){
					foreach($table->field[$z]->value as $key => $val)
						$values[] = '\''.$val.'\'';
                            	}
				$sql.=
				(($table->field[$z]['type'] == 'enum')?'`'.$table->field[$z]['name'].'` enum('.implode(',', $values).') ':'').
				(!empty($table->field[$z]['size'])?' ('.$table->field[$z]['size'].') ':'').
				(!empty($table->field[$z]['special'])?$table->field[$z]['special'].' ':'').
				(!empty($table->field[$z]['default'])?' default '.$table->field[$z]['default'].' ':'').
				(($table->field[$z]['primary'] == 'yes')?'PRIMARY KEY ':'').
				(($table->field[$z]['notnull'] == 'yes')?'NOT NULL ':'').
				(($z <= (count($table->field)-2))?',':'');
			}
			$sql .=') TYPE='.$table['type']; 
		}
		$res = $this->dbObj->query($sql);
		return (($res)?true:$this->logg('install-'.date('Y-m-d', time()), 'failed creating table '.$table));
	} 
	
	function getPagedItems(&$_this, $ordering=''){
		$sql= "SELECT * FROM `mods` ORDER BY ".(empty($ordering)?'id':$ordering); 
		$c=0;
	    $_this->turnPage($_GET['pager'], $sql);
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->modsdata[$c]['id']=$res['id'];
            $this->modsdata[$c]['name']=$res['name'];
            $this->modsdata[$c]['status']=$res['status'];
            $this->modsdata[$c]['author']=$res['author'];
            $this->modsdata[$c]['version']=$res['version'];
            if($this->hasAdminSection($res['name']))
            	$this->modsdata[$c]['conf']='mods.php?conf='.$res['name'];
            else
            	$this->modsdata[$c]['conf']= '';
            $this->modsdata[$c]['descr']=$this->showModDescr($res['name']);
            $c++;
        }
        
	    return(is_array($this->modsdata)?true:false);	
	}
	
	function getAllMods($categid){
		$sql = "SELECT m.name,m.status, m.author,an.mods FROM mods m, application_nodes an 
				WHERE an.ID=".safe_sql($categid);
		$res = $this->dbObj->query($sql);
		while($mods = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$html .= '<input name="mod_'.$mods['name'].'" value="'.$mods['name'].'" class="mod_checkbox" type="checkbox" '.
			((strstr($mods['mods'], $mods['name'])!==FALSE)?' selected="selected" ':' ').
			(($mods['status']!='installed')?' disabled="disabled"':' ')
			.'/>&nbsp;'.$mods['name'].'<br />';
		}
		return(!empty($html)?$html:FALSE);	
	}
	
	function hasAdminSection($modname){
		$xml = simplexml_load_file($this->base.'mod@'.$modname.'/'.$modname.'.xml');
		
		return(!empty($xml->module->admin))?true:false;
	}	
	
	function renderAdminSection($modname){
		$xml = simplexml_load_file($this->base.'mod@'.$modname.'/'.$modname.'.xml');
		$out .='<div class="mod_config">';
		$out .= '<form name="'.$modname.'_conf" method="post" action="'.$_SERVER['PHP_SELF'].'">';
		$out .= '<fieldset class="mod_fieldset">';
		$out .= '<legend>Mod '.$modname.'</legend>';
		foreach($xml->module->admin as $elements){
			
			if(!empty($elements->select)){
				$out .= '<label for="">'.$elements->select['name'].'</label>';
				$out .= '';
				$out .= '<select name="'. $elements->select['name'].'">';
				for($z=0; $z<count($elements->select->option); $z++)
					$out .= '<option value="'.$elements->select->option[$z]['value'].'">'.$elements->select->option[$z].'</option>';
				$out .= '</select>';
			}
			
			if(is_object($elements->input)){
				for($z=0; $z<count($elements->input); $z++){
					$out .= '<br /><label for="">'.$elements->input[$z]['name'].'</label>';
					$out .= '<input name="'.$elements->input[$z]['name'].'" type="'.$elements->input[$z]['type'].'" value="'.$elements->input[$z]['value'].'" />';
				}
			}
		}
		$out .= '<br /><br /><input style="height: 25px;" type="submit" value="submit" name="sender" />';
		$out .='</fieldset>';
		$out .= '</form>';
		$out .='</div>';
		
		return $out;
	}
	
	function showModDescr($modname){
		$xml = simplexml_load_file($this->base.'mod@'.$modname.'/'.$modname.'.xml');
		return $xml->module->descr;
	}
	//set the uninstall flag
	function uninstall($modname){
		$sql = 'UPDATE `mods` SET status=\'inactive\' WHERE name='.safe_sql($modname);
		$res = $this->dbObj->query($sql);
		return(($res)?$this->__redirect($modname, 'uninstall'):false);
	}
	function reinstall($modname){
		$sql = 'UPDATE `mods` SET status=\'installed\' WHERE name='.safe_sql($modname);
		$res = $this->dbObj->query($sql);
		return(($res)?$this->__redirect($modname, 'reinstall'):false);
	}
	
	function update($modname){
	
	}
	//deletes module pysically and leaves no trace...
	function delete($modname){
		if(!is_dir($this->base.'mod@'.$modname)){
			return false;
		}
		else{
			$dir = opendir($this->base.'mod@'.$modname);
			while(($fl = readdir($dir)) !== FALSE){
				try{
					if(($fl != '.') && ($fl != '..'))
						$this->deleteModFiles($this->base.'mod@'.$modname.'/'.$fl);
				}
				catch(Exception $e){
					$this->logg('exception-'.date('Y-m-d', time()), $e->getMessage());
					return false;
				}
			}
			closedir($dir);
			if(rmdir($this->base.'mod@'.$modname)){
				/*
				physically deleted all files with success, now delete the db records
				and drop existing table(if exists)...done
				*/
				$res = $this->dbObj->query("DELETE FROM `mods` WHERE name=".safe_sql($modname));
				if(!$res)
					return false;
				$res = $this->dbObj->query("SELECT * FROM `mod_".$modname."` WHERE name=".safe_sql($modname));
				if(!$res){
					return true; //if table doesn't exist we are done
				}
				else{
					//drop it 
					$res = $this->dbObj->query("DROP TABLE `mod_".$modname."`");
					return(($res)?true:false);
				} 
			}
			else{
				return false;
			}
		}
	}
	
	function deleteModFiles($filename){
		if(!unlink($filename))
			throw new Exception('Couldn\'t delete file'.$filename);
		else 
			return true;
	}
	
	function __redirect($modname, $action){
		header('location: mods.php?mod='.$modname.'&'.$action.'=success&complete=true');
	}
	
	

}






?>