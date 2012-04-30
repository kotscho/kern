<?php
session_start();
#=====================================================================#
# include corresponding php source/processing file                    #
# for each subtemplate                                                #
#=====================================================================#
//require_once('../../frontend/source/pat/patErrorManager.php');
//require_once('../../frontend/source/pat/patTemplate.php');
include('../config.php');

#==== sanitize all user input =================#
#                                              #
#==============================================#
foreach($_REQUEST as $k => $v)
    $_REQUEST[$k] = filter_var($v, FILTER_SANITIZE_STRING);
#====================================#
# mandatory things                                                            #
#====================================#
#==========================================#

#==========================================#

//REQUEST PARAM CONTROLLER!!!
//$tmpl->displayParsedTemplate('main');


?>