<?php

/*	database connection details
 ******************************/
$db['host'] = "mysql_host";
$db['user'] = "phpipam";
$db['pass'] = "Y6Ph5UUaLwnp737N";
$db['name'] = "phpipam";

/**
 * php debugging on/off
 *
 * true  = SHOW all php errors
 * false = HIDE all php errors
 ******************************/
$debugging = false;

/**
 *	manual set session name for auth
 *	increases security
 *	optional
 */
$phpsessname = "phpipam";

/**	
 *	BASE definition if phpipam 
 * 	is not in root directory (e.g. /phpipam/)
 *
 *  Also change 
 *	RewriteBase / in .htaccess
 ******************************/
define('BASE', "/phpipam/");

?>
