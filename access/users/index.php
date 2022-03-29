<?php
	$webauth_script_override = "/access/index.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("access");
?>

<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li class="active">Users</li>
</ol>
<h1>Users</h1>

<style type="text/css">
.footable-row-detail-name {
	text-align: right;
}
.footable-row-detail-value > input {
	margin-bottom: 5px;
}
</style>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<br /><br />
<!--<div class="row">
    <div class="col-xs-8 col-sm-5 col-md-5">
      <input id="filter1" class="form-control" placeholder="Filter" type="text"/>
    </div>
    <div class="col-xs-4 col-sm-7 col-md-7">
		<button type="submit" class="btn btn-danger clear-filter clear-filter" onClick="$('table').trigger('footable_clear_filter');">Reset Filters</button>
    </div>
</div>
<br />-->
<table class="table footable toggle-square" data-filter="#filter1" data-page-size="10">
  <thead>
    <tr>
      <th data-toggle="true">NetID</th>
      <th data-hide="phone">Name</th>
	  <th data-hide="phone">EmplID</th>
	  <th data-hide="phone,tablet">Name</th>
	  <th data-hide="phone,tablet">NetID</th>
	  <th data-hide="phone,tablet">EmplID</th>
	  <th data-hide="phone,tablet">UA ID</th>
	  <th data-hide="phone,tablet">ISO Number</th>
	  <th data-hide="phone,tablet">E-Mail Address</th>
	  <th data-hide="phone,tablet">Date of Birth</th>
    </tr>
  </thead>
  <tbody>
  <?php
    //Get the emplid, netid, and user.id from local mysql
    $query = 'SELECT * FROM  user LIMIT 10';
	$result = $db_link->query($query);
	
	$username = 'studentunion-portal';
	$password = 'SC2BsxHqLG9WWh2yzqksnrSP76FhKJdD';
	
	$attributes = array("cn" => "Common Name",
						"dateOfBirth" => "Date of Birth",
						"uid" => "NetID",
						"emplId" => "EmplID",
						"uaId" => "UA ID",
						"isoNumber" => "ISO Number",
						"mail" => "E-mail Address");	
	
	while($row = $result->fetch_array()) {
		$url = 'https://eds.arizona.edu/people/'.$row["emplid"];
		$cred = sprintf('Authorization: Basic %s', base64_encode($username.':'.$password));
		$opts = array( 'http' => array ('method'=>'GET', 'header'=>$cred));
		$ctx = stream_context_create($opts);
		
		// send our request and retrieve the DSML response
		$dsml = file_get_contents($url,false,$ctx);
		
		// create SimpleXMLElement from response
		$xml = new SimpleXMLElement($dsml);
		
		$xml->registerXPathNamespace('dsml', 'http://www.dsml.org/DSML');
		
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
		
		echo'<a name="user_'.$row["id"].'"></a>
		<tr>
          <td>'.$row["netid"].'</td>
		  <td>'.$attrib_vals["cn"].'</td>
		  <td>'.$row["emplid"].'</td>
		  <td>'.$attrib_vals["cn"].'</td>
		  <td>'.$row["netid"].'</td>
		  <td>'.$row["emplid"].'</td>
		  <td>'.$attrib_vals["uaId"].'</td>
		  <td>'.$attrib_vals["isoNumber"].'</td>
		  <td>'.$attrib_vals["mail"].'</td>
		  <td>'.$attrib_vals["dateOfBirth"].'</td>
		</tr>';
	}
?>
          </tbody>
          <tfoot class="hide-if-no-paging">
          <tr>
          <td colspan="5">
            <ul class="pagination pagination-centered"></ul>
          </td>
        </tr>
        </tfoot>
      </table>
<?php
    include_once('footer2.php');
?>
<script src="/FooTable-2/js/footable.js?v=2-0-1" type="text/javascript"></script>
<script src="/FooTable-2/js/footable.sort.js?v=2-0-1" type="text/javascript"></script>
<script src="/FooTable-2/js/footable.filter.js?v=2-0-1" type="text/javascript"></script>
<script type="text/javascript">
  $(function () {
    $('table').footable();
    //$('table').trigger('footable_expand_all');
  });
</script>