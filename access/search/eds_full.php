<?php
	$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$_POST['search_value_full'] = strval($_POST['search_value_full']);
	
	if (isset($_POST['search_value_full']) && $_POST['search_value_full']!="") {
		$username = 'studentunion-portal';
		$password = 'VPpVGqArH08bsyzvzY3i3CdsftLCLdER';
		$url = 'https://eds.arizona.edu/people/'.$_POST['search_value_full'];
		$cred = sprintf('Authorization: Basic %s', base64_encode($username.':'.$password));
		$opts = array( 'http' => array ('method'=>'GET', 'header'=>$cred));
		$ctx = stream_context_create($opts);
		
		// send our request and retrieve the DSML response
		$dsml = file_get_contents($url,false,$ctx);
	
		// create SimpleXMLElement from response
		$xml = new SimpleXMLElement($dsml);
	
		$xml->registerXPathNamespace('dsml', 'http://www.dsml.org/DSML');
		
		
	}
?>
<ol class="breadcrumb">
	<li><a href="/strap">Home</a></li>
	<li><a href="/access">Access</a></li>
	<li><a href="/access/search">EDS User Lookup</a></li>
	<li class="active">Full EDS User Output</li>
</ol>
<h1>Full EDS User Output</h1>

<form name="fulledslookup" action="#" method="POST">
	
<div class="row">
    <div class="col-xs-8 col-sm-5 col-md-5">
      <input name="search_value_full" class="form-control" placeholder="NetID, Catcard#, EmplID, or UAID" type="text" value="<?=$_POST['search_value_full'];?>">
    </div>
    <div class="col-xs-4 col-sm-7 col-md-7">
		<button type="submit" class="btn btn-primary clear-filter clear-filter">Search</button>
    </div>
</div>
<br />

<?php
if (!isset($_POST['search_value_full']) || $_POST['search_value_full']=="") {
	echo 'No results found.';
}
else {
	echo '<pre>';
	print_r(htmlspecialchars($xml->asXML()));
	echo '</pre>';
}
	echo '</form>';
	
	echo '<br /><br /><form name="edslookup" action="/access/search/index.php" method="POST">
		<input type="hidden" name="search_value" value="'.$_POST['search_value_full'].'" />
		<button type="submit" class="btn btn-primary clear-filter clear-filter">EDS User Lookup</button>
	</form>';
	
	require_once("footer2.php");
?>