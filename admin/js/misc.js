function go(){

	var length=document.validate.elements.length;
	//alert(length);
	for(z=0; z<length; z++){
		//
		if(document.validate.elements[z].value == ''){
		alert('Παρακαλούμε συμπληρώστε όλα τα πεδία');
		    return;
		}
		if(document.validate.elements[z].value == 'username' || document.validate.elements[z].value == 'password'){
			alert('Παρακαλούμε συμπληρώστε τα δικά σας στοιχεία');
			return;
		}
	}
	document.validate.submit();
}	

function validator(formelement){
	
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;
	
	for(z=0; z<length; z++){
        if(currentform.elements[z].type == 'hidden')
            continue;
		if(currentform.elements[z].value == '' && currentform.elements[z].name != 'passed_parentid'  && currentform.elements[z].name != 'passed_id'){
			alert('Έχετε αφήσει κενά πεδία '+ currentform.elements[z].name);
		    	return;
		}
	}
	currentform.submit();	
}

function materialValidator(formelement){
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;
	
	for(z=0; z<length; z++){
		
        if(currentform.elements[z].type == 'hidden')
            continue;
		if((currentform.elements[z].value == '') || (currentform.elements[z].value == 'none')){
			alert('Έχετε αφήσει κενά πεδία '+ currentform.elements[z].name);
		    	return;
		}
	}
	currentform.submit();
}

function gameconfiguratorValidator(formelement){
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;

	for(z=0; z<length; z++){
        if(currentform.elements[z].type == 'hidden' || (currentform.elements[z].disabled == true))
            continue;
		if(currentform.elements[z].value == ''){
			alert('Έχετε αφήσει κενά πεδία '+ currentform.elements[z].name);
		    	return;
		}
	}
	currentform.submit();
}

function memberValidator(formelement,mid){
	
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;
   // if(currentform.olduserpassword.value != ''){
        //xajax___isCorrectPassword(currentform.olduserpassword.value, mid);
            //alert('Ο κωδικός είναι λάθος');
            //return;
    //}
    
    if(mid > 0){
    	if(currentform.olduserpassword.value != '' && (currentform.newuserpassword.value == '' || currentform.newuserpasswordrepeat.value == '')){
        	alert('Ο κωδικός 1 πρέπει να αντιστοιχεί στο κωδικό 2!');
        	return;
    	}
    	if(currentform.olduserpassword.value != '' && (currentform.newuserpassword.value != currentform.newuserpasswordrepeat.value)){
        	alert('Ο κωδικός 1 πρέπει να αντιστοιχεί στο κωδικό 2!');
        	return;
    	}
    }
	for(z=0; z<length; z++){
        if(currentform.elements[z].type == 'hidden')
            continue;
        if(currentform.elements[z].name ==  'olduserpassword' || currentform.elements[z].name ==  'newuserpassword' || currentform.elements[z].name =='newuserpasswordrepeat')
            continue;
		if(currentform.elements[z].value == ''){
			alert('Έχετε αφήσει κενά πεδία '+ currentform.elements[z].name);
		    	return;
		}
        if(currentform.elements[z].name == 'userpasswordrepeat'){
            if(currentform.userpasswordrepeat.value != currentform.password.value){
                alert('Ο κωδικός 1 πρέπει να αντιστοιχεί στο κωδικό 2!');
                return;
            }
        }
	}
	currentform.submit();	
}

function fckvalidator(formelement){
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;
    var EditorInstance = FCKeditorAPI.GetInstance('FCKeditor1');
    
    if(EditorInstance.EditorDocument.body.innerHTML =='<p><br></p>' || EditorInstance.EditorDocument.body.innerHTML == ''){
        alert('Έχετε αφήσει κενό');
        return;
    }
    if(EditorInstance.EditorDocument.body.innerHTML.indexOf(".png") != '-1'){
        alert('Δεν επιτρέπονται αρχεία PNG');
        return;
    }
	for(z=0; z<length; z++){
		if(currentform.elements[z].value == ''  &&  currentform.elements[z].name != '' && currentform.elements[z].name != 'teaser' ){
			alert('Έχετε αφήσει κενά πεδία---'+ currentform.elements[z].name);
		    	return;
		}
	}
    currentform.submit();	
}

function uservalidator(formelement, id){
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;

	for(z=0; z<length; z++){
		if(currentform.elements[z].value == '' && currentform.elements[z].name != 'action' ){
			alert('Έχετε αφήσει κενά πεδία');
		    	return;
		}
	}
	xajax___userExists(document.user_form.username.value, id);
}

function uservalidatornew(formelement, id){
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;

	for(z=0; z<length; z++){
		if(currentform.elements[z].value == '' && currentform.elements[z].name != 'action' ){
			alert('Έχετε αφήσει κενά πεδία ');
		    	return;
		}
	}
	if(document.user_form.passwd.value != document.user_form.passwd_repeat.value){
		alert('Ο κωδικός 1 πρέπει να αντιστοιχεί στο κωδικό 2!');
		    	return;
	}

	xajax___userExists(document.user_form.username.value, id);
}


function passwdvalidator(formelement){
	var currentform = document.getElementById(formelement);
	var length=currentform.elements.length;

	for(z=0; z<length; z++){
		if(currentform.elements[z].value == '' && currentform.elements[z].name != 'action'  && currentform.elements[z].name != 'included_magazines_list'){
			alert('Έχετε αφήσει κενά πεδία ');
		    	return;
		}
	}
	if(document.user_form_np.passwd.value != document.user_form_np.passwd_repeat.value){
		alert('Ο κωδικός 1 πρέπει να αντιστοιχεί στο κωδικό 2!');
		    	return;
	}
	currentform.submit();	
}

function pop(url) {
	newwindow=window.open(url,'name','height=760,width=680 , scrollbars=yes');
	if (window.focus) {newwindow.focus()}
	
	return false;
}

function toggleVisibility(select, targetid){
	var sel=document.getElementById(select);
	var ob=document.getElementById(targetid);
	if(sel.value == 'Y')
		ob.style.display = 'inline';
	else
		ob.style.display = 'none';
	return;
}

function deleteItem(type, id){

if(confirm('Διαγραφή '+type+'?'))	
	document.location.href=type+'.php?del=true&id='+id;
else
	return;
}

function massDeleteItem(type){
    if(confirm('Διαγραφή '+type))
        document.checkfields.submit();
    else
        return;
}

function setEdit(){
    
    if(document.material_form.materialname.readOnly == true ){
        document.material_form.materialname.readOnly = false;
        document.getElementById('editlink').innerHTML = 'lock';
    }
    else{
        document.material_form.materialname.readOnly = true; 
        document.getElementById('editlink').innerHTML = 'edit';
    }
    return;
}


function materialIDCollector(){

    var matArray = document.forms[0].elements['active_material[]'];
    var stringOfIds = '';
    var seperator = '';
    //if(typeof(matArray) !== 'undefined')
        //alert(matArray.value);
    if(typeof(matArray) == 'undefined'){
        return '';
    }
    if(matArray.length > 0){
        for(z=0; z<matArray.length; z++){
            if(matArray.length > (z+1) )
                seperator=':'; 
            else 
                seperator='';
            stringOfIds = stringOfIds+matArray[z].value+seperator;
        }
        return '&matlist='+stringOfIds;
    }
    else{
        //alert('na und!!!');
        return '&matlist='+matArray.value;
    }
    
}


function contentIDCollector(){

    var contentArray = document.forms[0].elements['active_articles[]'];
    var stringOfIds = '';
    var seperator = '';
    //if(typeof(contentArray) !== 'undefined')
        //alert(contentArray.value);
    if(typeof(contentArray) == 'undefined'){
        return '';
    }
    if(contentArray.length > 0){
        for(z=0; z<contentArray.length; z++){
            if(contentArray.length > (z+1) )
                seperator=':'; 
            else 
                seperator='';
            stringOfIds = stringOfIds+contentArray[z].value+seperator;
        }
        return '&contentlist='+stringOfIds;
    }
    else{
        return '&contentlist='+contentArray.value;
    }
    
}

function memberMailCheck(str){
    
    var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    if(filter.test(str)){
        return xajax___dnslookup(str);
    }
    else{
        alert('Υπάρχει συντακτικό λάθος στο email!');
        return;
    }
}

function togglePasswordId(id, trigger){

    var current = document.getElementById(id);
    if(current.style.display=='block'){
        current.style.display='none';
        trigger.innerHTML='Αλλαγή κωδικού';
        return;
    }
    else{
        current.style.display='block';
        document.members_form.olduserpassword.value = '';
        trigger.innerHTML='Άρση κωδικού';
        return;
    }
}

function __checkall(sender){
    
    var obj = document.forms['checkfields'];
    for(var z=0; z < obj.elements.length; z++){
       if(obj.elements[z].type == "checkbox"){
           if(obj.elements[z].checked == false){
                obj.elements[z].checked = true;
                sender.innerHTML = 'ακύρωση';
            }
            else{
                 obj.elements[z].checked = false;
                 sender.innerHTML = 'επιλογή όλων';
            }
        }
    }
}


function calculatePlayerValue(){ //used in player.php
	
	if(document.player_form.availableShares.value != '' && document.player_form.integer_places.value != '' && document.player_form.decimal_places.value != '')
		return (document.player_form.total.value=(document.player_form.availableShares.value)*(document.player_form.integer_places.value + '.' + document.player_form.decimal_places.value));
	else
		return '';
}

function assignTotalEventValue(currentvalue, currenttarget, teamname, currentevent){ //used in class.gameresults.php
	
	
	var obj = document.getElementById('game_results_grid');
	document.getElementById(currenttarget).value = 0;
	for(var z=0; z < obj.elements.length; z++){
		if(obj.elements[z].name == currenttarget)
			continue;
		if( obj.elements[z].name.indexOf(currentevent) >= parseInt("0") && obj.elements[z].name.indexOf(teamname) >= parseInt("0") ){ //kdos here...
			document.getElementById(currenttarget).value = (Number(obj.elements[z].value) + Number(document.getElementById(currenttarget).value) );
		}
		
	}
		
	
	return (document.getElementById(currenttarget).value);
}
//under presure..sorry for the rookie code here....
function toggleGameConfigNotOrdinary(command){
	if(command == 'n'){
		alert('No usage of non ordinary schedules');
		document.getElementById('not_ordinary_open_explicit_time').disabled = true;
		document.getElementById('not_ordinary_close_explicit_time').disabled = true;
		document.getElementById('datepicker_not_ordinary_open').disabled = true;
		document.getElementById('datepicker_not_ordinary_close').disabled = true;
	}
	else{
		alert('Please provide date and time for your non ordinary schedules');
		document.getElementById('not_ordinary_open_explicit_time').disabled = false;
		document.getElementById('not_ordinary_close_explicit_time').disabled = false;
		document.getElementById('datepicker_not_ordinary_open').disabled = false;
		document.getElementById('datepicker_not_ordinary_close').disabled = false;
	}
	return;
}


function refParent(form){

	document.getElementById(form).submit();
	opener.location.reload(true);
	return;
}
