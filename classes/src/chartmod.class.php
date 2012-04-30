<?php
namespace classes\src;
//class chart
//partial wrap of GphpChart class

class chartmod{
	
	var $data = array();
	var $y_scale;
	var $width;
	var $title;
	var $scaletypes = array();
	var $chartmod;
	var $currentchart;
	var $grids;
	
	
	function __construct($gphpchart, $array, $charttype, $y_scale){
		$this->chartmod = $gphpchart;
		$this->data = $array;
		$this->y_scale = $y_scale;
		$this->chartmod->filename = 'cache/chart_'.$charttype.'_'.date('Y-m-d', time()).'.png';
	}
	
	function draw($title, $width , $markerparams=null){// <marker type>,<color>,<data set index>,<data point>,<size> 
		$this->chartmod->title = $title;
		$this->chartmod->width = $width;
		if(!file_exists($this->chartmod->filename)){
			$this->chartmod->add_data(array_values($this->data));
			$this->chartmod->add_labels('x',array_keys($this->data)); //x-axis scale
			$this->chartmod->add_labels('y', $this->y_scale);
			if(is_array($markerparams)){
				for($z=0; $z<count($markerparams); $z++)
					$this->drawMarker((string)$markerparams[$z][0]);
			}
			if(!empty($this->grids))
				$this->drawGrids();
			$this->currentchart = $this->chartmod->get_Image_String();
			$this->chartmod->save_Image();
		}
		else{
			$this->currentchart = $this->chartmod->get_Image_String();
		}
	}
	
	function drawGrids(){
		$this->chartmod->add_grid($this->grids);
	}
	
	function drawMarker($params){
		$this->chartmod->add_marker($params);//kdos 21-10-2010: continue here
	}
	
}
?>