<?php 
	$months = array(1=>'Ιαν', 
					2=>'Φεβ', 
					3=>'Μάρ',
					4=> 'Απρ', 
					5=>'Μαι', 
					6=>'Ιούν', 
					7=>'Ιούλ', 
					8=>'Αυγ', 
					9=>'Σεπ', 
					10=>'Οκτ', 
					11=>'Νοέ', 
					12=>'Δεκ');
	
	function selectEdition(){
		global $months;
		$edition .= '<select name="edition">';
		for($c=1; $c<=12; $c++)
			$edition .= '<option value="'.$c.'">'.$months[$c].'</option>';
		$edition .= '</select>';
		return $edition;
	} 


?>