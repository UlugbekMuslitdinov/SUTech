<?php
	$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$_POST['search_value'] = strval($_POST['search_value']);
	
	if (isset($_POST['search_value']) && $_POST['search_value']!="") {
		$username = 'studentunion-portal';
		$password = 'VPpVGqArH08bsyzvzY3i3CdsftLCLdER';
		$url = 'https://eds.arizona.edu/people/'.$_POST['search_value'];
		$cred = sprintf('Authorization: Basic %s', base64_encode($username.':'.$password));
		$opts = array( 'http' => array ('method'=>'GET', 'header'=>$cred));
		$ctx = stream_context_create($opts);
	
		// send our request and retrieve the DSML response
		$dsml = file_get_contents($url,false,$ctx);
	
		// create SimpleXMLElement from response
		$xml = new SimpleXMLElement($dsml);
	
		$xml->registerXPathNamespace('dsml', 'http://www.dsml.org/DSML');
		
		// Build the array and fill in display text for each attribute
		$attributes = array("cn" => "Common Name",
							"givenName" => "Given Name",
							"sn" => "Sur Name",
							"dateOfBirth" => "Date of Birth",
							"uid" => "NetID",
							"emplId" => "EmplID",
							"uaId" => "UA ID",
							"isoNumber" => "ISO Number",
							"employeePhone" => "Work Phone",
							"mail" => "E-mail Address",
							"employeePrimaryDeptName" => "Primary Department Name",
							"employeePrimaryTitle" => "Primary Title",
							"eduPersonPrimaryAffiliation" => "Primary Affiliation");
		// Create an empty array to store the actual values in
		$attrib_vals = array();
		// Go through and query the DSML feed we got back from EDS for attributes
		foreach ($attributes as $cur_attrib => $cur_title) {
			$query = "//dsml:entry/dsml:attr[@name='".$cur_attrib."']/dsml:value";
			$vals = $xml->xpath($query);
			foreach($vals as $node) {
				$attrib_vals[$cur_attrib] = $node;
			}
		}
		// Build the array of affiliations
		$affiliations = array();
		$query = "//dsml:entry/dsml:attr[@name='eduPersonAffiliation']/dsml:value";
		$vals = $xml->xpath($query);
		foreach($vals as $node) {
			array_push($affiliations,$node);
		}
	}
?>
<ol class="breadcrumb">
	<li><a href="/strap">Home</a></li>
	<li><a href="/access">Access</a></li>
	<li class="active">EDS User Lookup</li>
</ol>
<h1>EDS User Lookup</h1>

<form name="edslookup" action="#" method="POST">
	
<div class="row">
    <div class="col-xs-8 col-sm-5 col-md-5">
      <input name="search_value" class="form-control" placeholder="NetID, Catcard#, EmplID, or UAID" type="text" value="<?=$_POST['search_value'];?>">
    </div>
    <div class="col-xs-4 col-sm-7 col-md-7">
		<button type="submit" class="btn btn-primary clear-filter clear-filter">Search</button>
    </div>
</div>
<br />

<?php
if (!isset($_POST['search_value']) || $_POST['search_value']=="") {
	echo 'No results found.';
}
else {
	// Format the Phone Number
	$attrib_vals["employeePhone"] = preg_replace('/[^0-9]/', '', $attrib_vals["employeePhone"]);	
	$len = strlen($attrib_vals["employeePhone"]);
	if($len == 7)
	$attrib_vals["employeePhone"] = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $attrib_vals["employeePhone"]);
	elseif($len == 10)
	$attrib_vals["employeePhone"] = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $attrib_vals["employeePhone"]);
	
	// Format the DOB
	$attrib_vals["dateOfBirth"] = date("F j, Y", strtotime($attrib_vals["dateOfBirth"]));

	echo '<table>';
	foreach ($attributes as $cur_attrib => $cur_title) {
		echo '<tr>
			<td style="font-weight:bold;">'.$cur_title.':</td>
			<td style="padding-left:20px;">'.$attrib_vals[$cur_attrib].'</td>
		</tr>';
	}
	$affil_str = "";
	foreach ($affiliations as $affil) {
		$affil_str .= $affil.', ';
	}
	$affil_str = substr($affil_str, 0, -2);
	echo '<tr>
		<td style="font-weight:bold;">Affiliations:</td>
		<td style="padding-left:20px;">'.$affil_str.'</td>
	</tr>';
	echo '</table>';
}
	echo '</form>';
	
	echo '<br /><br /><form name="fulledslookup" action="/access/search/eds_full.php" method="POST">
		<input type="hidden" name="search_value_full" value="'.$_POST['search_value'].'" />
		<button type="submit" class="btn btn-primary clear-filter clear-filter">Show Full EDS Output</button>
	</form>';
	
	require_once("footer2.php");
?>