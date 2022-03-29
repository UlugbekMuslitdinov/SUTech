<?php 

$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
$uri = '/' . trim($uri, '/');

$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
$base_url = '/' . trim($uri, '/');
// $base_url = $this->getCurrentUri();
$routes = array();
$routes = explode('/', $base_url);

foreach($routes as $route)
{
	if(trim($route) != '')
		array_push($routes, $route);
}

switch ($routes[1]) {
	case '':
		require_once('staff_list.php');
		require_once('main_view.php');
	
	default:
		break;
}


?>