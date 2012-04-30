<?php
//class.newsletter.php
namespace classes\src;

class newsletter {
    
    var $dbObj;
    var $id;
    var $created;
    var $title;
    var $content;
    var $sent;
    var $newsletterdata;
    
    function __construct(&$dbobj){
        $this->dbObj = $dbobj;
    }

    function get_items(){
		$sql ="SELECT * FROM newsletter";
		$res=$this->dbObj->query($sql);
		$c=0;
		while($newsletter=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$this->newsletterdata[$c]['id']=$newsletter['ID'];
            $this->newsletterdata[$c]['created']=$newsletter['CREATED'];
            $this->newsletterdata[$c]['title']=$newsletter['TITLE'];
            $this->newsletterdata[$c]['content']=$newsletter['CONTENT'];
            $this->newsletterdata[$c]['sent']=$newsletter['SENT'];
			$c++;
		}
		return(is_array($this->newsletterdata)?true:false);
	}
    
    function getPagedItems(&$_this, $ordering=''){//paging object
	      	$sql= "SELECT * FROM newsletter ORDER BY ".(empty($ordering)?'ID':$ordering); 
			$c=0;
	        $_this->turnPage($_GET['pager'], $sql);
        while($res=mysql_fetch_assoc($_this->resultSet)){
            $this->newsletterdata[$c]['id']=$res['ID'];
            $this->newsletterdata[$c]['created']=$res['CREATED'];
            $this->newsletterdata[$c]['title']=$res['TITLE'];
            $this->newsletterdata[$c]['content']=$res['CONTENT'];
            $this->newsletterdata[$c]['sent']=$res['SENT'];
            $c++;
        }
	    return(is_array($this->newsletterdata)?true:false);	
	 }
     
    function getNewsletterDataById($id){
        $sql = "SELECT * FROM newsletter WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        $c=0;
        while($newsletter=$res->fetchRow(MDB2_FETCHMODE_ASSOC)){
            $this->id = $newsletter['ID'];
            $this->created= $newsletter['CREATED'];
            $this->content = $newsletter['CONTENT'];
            $this->title = $newsletter['TITLE'];
            $this->sent = $newsletter['SENT'];
        }
        return(!empty($this->id)?true:false);
     }
     
    function deleteNewsletter($id){
        $sql = "DELETE FROM newsletter WHERE ID=".safe_sql($id);
        $res=$this->dbObj->query($sql);
        return(($res)?true:false);
    }
    
    function insert(){
		$sql = "INSERT newsletter SET
                CREATED=".safe_sql($this->created).",
                CONTENT=".safe_sql($this->content).",
                TITLE=".safe_sql($this->title).",
                SENT=".safe_sql($this->sent);
		$res=$this->dbObj->query($sql); 
		
		return($res==true?true:false);
	}
	
	function update($id){
		$sql = "UPDATE newsletter SET 
                CONTENT=".safe_sql($this->content).",
                TITLE=".safe_sql($this->title).",
                SENT=".safe_sql($this->sent)."
				WHERE ID=".safe_sql($id);
		$res=$this->dbObj->query($sql);
		
		return($res==true?true:false);
	}
}

class newsletterHelpers{
    public static function sendNewsletter($newsletterid, $template){
    	
        global $dbObj;
        $error = 0;
        $sql = "SELECT CONTENT, TITLE, CREATED FROM newsletter WHERE ID = ".safe_sql($newsletterid);
        $res=$dbObj->query($sql);
       
        while($mail = $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
         	
            $currentMail['content'] = $mail['CONTENT'];
            $currentMail['title'] = $mail['TITLE'];
            $currentMail['created'] = $mail['CREATED'];
        }
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
        $headers .= "From: ".NO_REPLY."<".NO_REPLY.">\r\n";
        $headers .=  "X-Mailer: PHP\r\n";
        $mailbody= file_get_contents($template);
        $mailbodyA = str_replace('{_CONTENT_}', $currentMail['content'], $mailbody);
        $mailbodyB = str_replace('{_CREATED_}', $currentMail['created'], $mailbodyA);
        $mailbodyC = str_replace('{_TITLE_}', $currentMail['title'], $mailbodyB);
        
        $mails=membersHelpers::getAllActiveMembers();
       
        for($z=0; $z<count($mails); $z++){
            if(!mail($mails[$z], $currentMail['title'], $mailbodyC, $headers)){
                file_put_contents('mail.log', date("d-m-Y").': sending failed for '. $mails[$z]. ' newsletter ID: '.$newsletterid, FILE_APPEND);
                $error++;
            }
            else{
                $sql = "UPDATE newsletter SET SENT ='Y' WHERE ID=".safe_sql($newsletterid);
                $dbObj->query($sql);
            }
        }
        return($error=== (int)0)?true:false;
    }
    
    public static function showMailLogs(){}
    
    public static function deleteMailLogs(){}
}



?>