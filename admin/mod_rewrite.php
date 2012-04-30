<?php

//mod_rewrite.php
if(SEO_FRIENDLY == 'OFF')//Don't perform this script if not wanted.
	exit();

$currentrules  = "Options +FollowSymLinks\nRewriteEngine On\n";
$currentrules .= "RewriteRule ^search-results$ frontend/source/main.php\n";
$currentrules .= "RewriteRule ^sitemap$ frontend/source/main.php?sitemap=true\n";
$currentrules .=  $ob->createRewriteRules();
file_put_contents('../.htaccess', $currentrules);
?>