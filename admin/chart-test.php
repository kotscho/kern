<?php
//error_reporting(E_ALL);
require_once('config.php');
require_once(CLASS_PATH.'class.chartmod.php');

$values = array('01/12' => 300, '08/12' => 400, '20/12' => 632, '29/12' => 450);
$markerparameters = array(
	array('s,990066,0,0.0,4.0'),array('s,990066,0,0.5,4.0'),array('v,BBCCED,0,2,1.0')
);
$gchart = new chartmod($gphpchart, $values, 'lc', array(0,500,100,1500,2000,2500));
$gchart->grids = '8.3333,10,1,5';
$gchart->draw('MSLeague-Test',200, $markerparameters);
print '<span style="width: 300px; float: left;">'.$gchart->currentchart.'</span>';
?>