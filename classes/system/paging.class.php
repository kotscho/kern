<?
 /** SimplePagingClass ****************************************************************************
 *																								  *
 *	This notice MUST stay intact for legal use:													  *																								  *
 *  @author Kostas Doskas <kdoskas@web.de>														  *
 *  @version v.1.0(last revision: April 29th, 2009)										  	  	  *
 *  @copyright © 2006 Kostas Doskas															      *
 *																								  *
 ** This class divides a sql resultset into x numbers of pages depending on the number of rows ****

 ** usage *****************************************************************************************
 *																								  *
 *	@include class and initiat new object:	                                                      *
 *  require_once("classPaging.php");															  *
 *	$pager = new paging(4, 'your_css_class');	  //4 rows per page plus this cssclass 			  *
 *  $myPager = $pager->turnPage($_GET['pager'], $query);//1st param: use exactly like this		  *
 * 														//2nd param: your sql query				  * 
 *	use $myPager for your loop (mysql_fetch_array)												  *
 *  and below render links with $pager->displayPageNums(2); //this param is used to create an     *
 * 															//offset on the left and right side   *
 * 					                                        //like 3 4 [5] 6 7                    *
 * 															 it's not mandatory. 	              *
 *  $pager->dataInfo //displays an onChange page select , its optional							  *
 * 																								  *													  *
 *************************************************************************************************/
namespace classes\system;

class paging{
	
	
	var $currentLimit;
	var $currentQuery;
	var $start=0;
	var $totalRows;
	var $numberOfPages;
	var $links;
	var $myPager;
	var $resultSet;
	var $end;
	var $cssClass;
	var $dataInfo;
	var $pageSelect;
	var $buildSelect;
	var $db;
	var $where;
	
	function __construct($limit, $cssClass, $dbc){
		
	   $this->db = $dbc;	
	   $this->cssClass=$cssClass;	
	   $this->currentLimit=$limit;
       return(1);
	}

	function turnPage($pager, $query, $where=''){
		
		if(!empty($where)){
			$this->where = $where;
		}
		$this->myPager = (empty($pager))?1:$pager; //the actual get param from the URL
		$this->currentQuery=$query;//unmodified query string
		$this->totalRows=mysql_num_rows(mysql_query($this->currentQuery));//total selected rows
		if(empty($this->myPager) xor $this->myPager == 1){//starting point, the first result part
			$this->currentQuery = $this->currentQuery.' LIMIT '.$this->start.','.$this->currentLimit;//query preparation
			$this->resultSet = mysql_query($this->currentQuery);
			return  ($this->totalRows > 0) ?  $this->resultSet : FALSE;
		}
		elseif( $this->myPager > 1){
			$this->start = $this->currentLimit*($this->myPager - 1); //update starting point for the query
			$this->currentQuery = $this->currentQuery.' LIMIT '.$this->start.','.$this->currentLimit;
			$this->resultSet  = mysql_query($this->currentQuery);
			
			return ($this->totalRows > 0) ? $this->resultSet  : FALSE;
		}
	}
	
	function displayPageNums($visibillityOffset='', $params=''){
		
		if($this->totalRows%$this->currentLimit != 0){ // if total rows are not divisible by limit(without remainder)...
			$this->numberOfPages = floor($this->totalRows/$this->currentLimit)+1;//...round down an add 1 to get the physical number of pages
		}
		elseif($this->totalRows%$this->currentLimit == 0){// else, simply divide
			$this->numberOfPages = $this->totalRows/$this->currentLimit;
		}
		$this->links .= (!empty($this->myPager) && $this->myPager > 1) ? "<a class=\"$this->cssClass\" href=\"".$_SERVER['PHP_SELF']."?pager=1&amp;where=".(urlencode($this->where)).(!empty($params)?('&amp;'.$params):'')."\">αρχή</a>&nbsp;<a class=\"$this->cssClass\" href=\"".$_SERVER['PHP_SELF']."?pager=".($this->myPager-1)."&amp;where=".(urlencode($this->where)).(!empty($params)?('&amp;'.$params):'')."\">&lt;&lt;&lt;&lt; </a>&nbsp; ":'';
 		for($x=1; $x <= $this->numberOfPages; $x++){ 
			$y = $x; 
			
			($x == $this->myPager || ($x == 1 && empty($this->myPager))) ? $z = "<strong>[$x]&nbsp;</strong>" : $z="<a class=\"$this->cssClass\" href=\"".$_SERVER['PHP_SELF']."?pager=$y"."&amp;where=".(urlencode($this->where)).(!empty($params)?('&amp;'.$params):'')."\"><u>$x</u></a>&nbsp;"; //current page number appears bold
			if($visibillityOffset){
			    if( (($x + $visibillityOffset) == $this->myPager) XOR ($x + ($visibillityOffset) == ($this->myPager+1)) OR ($x ==1 && $this->myPager == 1))	
			       $this->links .= $z;
			    if( (($x - $visibillityOffset) == $this->myPager) XOR ($x - ($visibillityOffset) == ($this->myPager-1)) )	
			       $this->linksToTheRight .= $z;
			    if($this->myPager == $x && $this->myPager != 1)
			       $this->current = $z;    
			}
			$this->buildSelect.="<option value=\"$y\"".(($this->myPager == $y)?' selected="selected" ':'').">$y</option>";
		}
		$listInf=($this->myPager == $this->numberOfPages) ? $this->current.'&nbsp;τέλος&nbsp;':$this->current;
		$this->links =  $this->links.$listInf.$this->linksToTheRight;
		$this->links .= (!empty($this->myPager) && $this->myPager < $this->numberOfPages) ? "&nbsp;<a class=\"$this->cssClass\" href=\"".$_SERVER['PHP_SELF']."?pager=".($this->myPager+1)."&amp;where=".(urlencode($this->where)).(!empty($params)?('&amp;'.$params):'')."\">&gt;&gt;&gt;&gt;</a>&nbsp;<a class=\"$this->cssClass\" href=\"".$_SERVER['PHP_SELF']."?pager=".$this->numberOfPages."&amp;where=".(urlencode($this->where)).(!empty($params)?('&amp;'.$params):'')."\">τέλος</a>":'';
		($this->start == 0)? $this->start=1 :  $this->start=$this->start+1;
		$this->end=((($this->start-1) + $this->currentLimit) >= $this->totalRows) ? $this->totalRows : (($this->start-1) + $this->currentLimit);//stop counting when limit is reached
		($this->end == $this->start) ? ($this->start = '') : $this->start = $this->start.'-'; 
		$this->dataInfo = "<br /><br /><br />Αποτελέσματα:&nbsp;&bull;&nbsp;&bull;&nbsp;&bull;&nbsp;&nbsp;".$this->start.($this->end)." (".$this->totalRows.")";
		$this->pageSelect.="<form action=\"".$_SERVER['PHP_SELF']."\" style=\"display: inline;\" method=\"get\" name=\"getPage\">Επιλέξτε σελίδα:<select name=\"pager\" onchange=\"document.getPage.submit();\">";
		$this->pageSelect.= $this->buildSelect;
		$this->pageSelect.="</select>&nbsp;&nbsp;";
        //kdos 05122009 param must become an array to support unlimited number of params...
		$this->pageSelect.=(!empty($params)?'<input type="hidden" name="'.reset(explode('=',$params )).'" value="'.end(explode('=',$params )).'" />':'');
		$this->pageSelect.="<input type=\"hidden\" name=\"where\" value=\"".($this->where)."\"/>";
		$this->pageSelect.="</form>";
		
		return($this->totalRows <= $this->currentLimit) ? NULL :'<br /><br />'.$this->pageSelect.$this->links;//kdos 13-03-2007: don't display links if there's only 1 result page
		
	}
	
	function datainf(){
		return(!empty($this->dataInfo))?$this->dataInfo.'<br />':'';
	}
	
	
	
}



?>