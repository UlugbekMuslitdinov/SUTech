<?php
	$webauth_script_override = "/access/admin.php";
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
  <li class="active">Inventoried Devices</li>
</ol>
<h1>Inventoried Devices</h1>
<?php	
	if (isset($_POST["submit"]) && $_POST["submit_confirm"]=="confirm_phrase") {
		$query = 'SELECT * FROM  import__device WHERE is_imported = FALSE';
		$result = $db_link->query($query);
		$sync_successes = 0;
		$sync_errors = 0;
		while($row = $result->fetch_array()) {
			// Keep track of if we see something we don't like
			$sync_error = FALSE;
			
			// Clean up all the fields
			$fields = array("name","manufacturer","device_type","model",
				"ip_addresses","serial_number","location","os",
				"install_date","description","proc_name",
				"proc_max_speed","proc_cores","logical_proc","memory",
				"nics_enabled","nics_total","mac_addresses","jack_id",
				"users");
			foreach($fields as $cur_field) {
				$row[$cur_field] = trim($row[$cur_field]);
			}
			
			// Translate basic stuff
			$sys_name = strval($row["name"]);
			$sys_manufacturer = strval($row["manufacturer"]);
			$sys_device_type = strval($row["device_type"]);
			$sys_model = strval($row["model"]);
			$sys_serial_number = strval($row["serial_number"]);
			$sys_location = strval($row["location"]);
			$sys_os = strval($row["os"]);
			$sys_install_time = strtotime($row["install_date"]);
			$sys_description = strval($row["description"]);
			$sys_proc_name = strval($row["proc_name"]);
			$sys_proc_max_speed = strval($row["proc_max_speed"]);
			$sys_proc_cores = intval($row["proc_cores"]);
			$sys_logical_proc = intval($row["logical_proc"]);
			$sys_memory = intval($row["memory"]);
			$sys_nics_enabled = intval($row["nics_enabled"]);
			$sys_nics_total = intval($row["nics_total"]);
			$sys_jack_id = strval($row["jack_id"]);
			
			// Create emtpy arrays to parse things into
			$sys_ip_addresses = array();
			$sys_hostnames = array();
			$sys_mac_addresses = array();
			$sys_users = array();
			
			$temp = explode(";",$row["ip_addresses"]);
			foreach ($temp as $cur_ip) {
				// See if it was really just not configured
				if ($cur_ip == "0.0.0.0") {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping invalid IP > '.$cur_ip.'</div>';
				}
				// Make sure this string is not already in the array
				else if (in_array($cur_ip,$sys_ip_addresses)) {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping duplicate IP > '.$cur_ip.'</div>';
				}
				// Double check if is valid IP_v4 string and then add if so
				else if (filter_var($cur_ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) {
					$cur_hostname = gethostbyaddr(strval($cur_ip));
					if (!in_array($cur_hostname,$sys_hostnames) && !(filter_var($cur_hostname,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))) {
						array_push($sys_hostnames,$cur_hostname);
					}
					$query2 = 'SELECT net_segment_id FROM sys__ip_address WHERE address = "'.$cur_ip.'"';
					$result2 = $db_link->query($query2);
					$row2 = $result2->fetch_assoc();
					$cur_seg = $row2["net_segment_id"];
					$sys_ip_addresses[$cur_seg] = $cur_ip;
				}
				else {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping invalid IP > '.$cur_ip.'</div>';
				}
			}
			
			$temp = explode(";",$row["mac_addresses"]);
			foreach ($temp as $cur_mac) {
				// Make sure this string is not already in the array
				if (in_array($cur_mac,$sys_mac_addresses)) {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping duplicate MAC Address > '.$cur_mac.'</div>';
				}
				// Otherwise add to the array
				else {
					array_push($sys_mac_addresses,$cur_mac);
				}
			}
			
			$temp = explode(";",$row["users"]);
			foreach ($temp as $cur_user) {
				$cur_user = explode("~",$cur_user);
				// Make sure this string is not already in the array
				if (in_array($cur_user[0],$sys_users)) {
					echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping duplicate user > '.$cur_user[0].'</div>';
				}
				// Filter out Sophos users
				else if (strpos($cur_user[0], 'Sophos') !== FALSE) {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping Sophos user > '.$cur_user[0].'</div>';
				}
				// Filter out ASPNET users
				else if (strpos($cur_user[0], 'ASPNET') !== FALSE) {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping ASPNET user > '.$cur_user[0].'</div>';
				}
				// Filter out Administrator users
				else if (strpos($cur_user[0], 'Administrator') !== FALSE) {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping Administrator user > '.$cur_user[0].'</div>';
				}
				// Filter out billybob users
				else if (strpos($cur_user[0], 'billybob') !== FALSE || strpos($cur_user[0], 'BILLYBOB') !== FALSE) {
					//echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping billybob user > '.$cur_user[0].'</div>';
				}
				else if (strpos($cur_user[0], 'Guest') !== FALSE|| strpos($cur_user[0], 'DELETEme') !== FALSE || strpos($cur_user[0], 'test') !== FALSE
						  || strpos($cur_user[0], 'UpdatusUser') !== FALSE) {
					echo '<div class="alert alert-warning"><b>PARSER NOTICE</b>: Skipping user > '.$cur_user[0].'</div>';
				}
				// Otherwise add to the array
				else {
					$query2 = 'SELECT id FROM user WHERE
						(netid = "'.$cur_user[0].'") OR
						(novell_id = "'.$cur_user[0].'")';/* AND
						(active = TRUE)';*/
					$result2 = $db_link->query($query2);
					// Couldn't find user record
					if (mysqli_num_rows($result2) == 0) {
						$sync_error = TRUE;
						echo '<div class="alert alert-danger"><b>PARSER ERROR</b>: Unable to find active user record > '.$cur_user[0].'</div>';
					}
					// Found too many user records
					else if (mysqli_num_rows($result2) > 1) {
						$sync_error = TRUE;
						echo '<div class="alert alert-danger"><b>PARSER ERROR</b>: Query returned too many user record ids > '.$cur_user[0].'</div>';
					}
					else {
						$row2 = $result2->fetch_assoc();
						$sys_users[$row2["id"]] = $cur_user[0];
					}
				}
			}

			if (!$sync_error) {
				$query2 = "INSERT INTO sys__device SET
					name = \"$sys_name\",
					manufacturer = \"$sys_manufacturer\",
					device_type = \"$sys_device_type\",
					model = \"$sys_model\",
					serial_number = \"$sys_serial_number\",
					location = \"$sys_location\",
					os = \"$sys_os\",
					install_date = \"$sys_install_time\",
					description = \"$sys_description\",
					proc_name = \"$sys_proc_name\",
					proc_max_speed = \"$sys_proc_max_speed\",
					proc_cores = \"$sys_proc_cores\",
					logical_proc = \"$sys_logical_proc\",
					memory = \"$sys_memory\",
					nics_enabled = \"$sys_nics_enabled\",
					nics_total = \"$sys_nics_total\"";
				//echo $query2."<br /><br />";
				$result2 = $db_link->query($query2);

				$new_device_id = mysqli_insert_id($db_link);
				//echo $new_device_id."<br />";
				//$new_device_id = 88;
				if ($new_device_id > 0) {
					if (count($sys_users)>0) {
						$query2 = "INSERT INTO sys__user_account (user_id,device_id) VALUES ";
						foreach ($sys_users as $cur_user_id => $cur_user) {
							$query2 .= "($cur_user_id,$new_device_id),";
						}
						$query2 = rtrim($query2,",");
						//echo $query2."<br /><br />";
						$result2 = $db_link->query($query2);
					}
					
					if (count($sys_mac_addresses)>0) {
						$query2 = "INSERT INTO sys__mac_address (device_id,address) VALUES ";
						foreach ($sys_mac_addresses as $cur_mac_addresses) {
							$query2 .= "($new_device_id,\"$cur_mac_addresses\"),";
						}
						$query2 = rtrim($query2,",");
						//echo $query2."<br /><br />";
						$result2 = $db_link->query($query2);
					}
					
					if (count($sys_ip_addresses)>0) {
						$query2 = "INSERT INTO sys__ip_address (device_id,net_segment_id,address,jack_id) VALUES ";
						$query3 = "UPDATE sys__ip_address SET active = FALSE WHERE ";
						foreach ($sys_ip_addresses as $cur_seg => $cur_ip_addresses) {
							$query2 .= "($new_device_id,$cur_seg,\"$cur_ip_addresses\",\"$sys_jack_id\"),";
							$query3 .= ' address="'.$cur_ip_addresses.'" OR';
						}
						$query2 = rtrim($query2,",");
						$query3 = rtrim($query3,"OR");
						//echo $query3.'<br /><br />';
						$result3 = $db_link->query($query3);
						//echo $query2."<br /><br />";
						$result2 = $db_link->query($query2);
						$new_ip_id = mysqli_insert_id($db_link);
						//$new_ip_id = 8888;
						if ($new_ip_id > 0) {
							if (count($sys_hostnames)>0) {
								$query2 = "INSERT INTO sys__dns_registration (ip_address_id,name,subdomain) VALUES ";
								foreach ($sys_hostnames as $cur_hostname) {
									$cur_hostname = explode(".", $cur_hostname, 2);
									$query2 .= '('.$new_ip_id.',"'.$cur_hostname[0].'","'.$cur_hostname[1].'"),';
								}
								$query2 = rtrim($query2,",");
								//echo $query2."<br /><br />";
								$result2 = $db_link->query($query2);
							}
						}
					}
				}
				$query2 = 'UPDATE import__device SET is_imported = TRUE WHERE import__device.id = '.$row["id"];
				$result2 = $db_link->query($query2);
				$sync_successes++;
			}
			else {
				echo '<div class="alert alert-danger"><b>ERROR</b>: Will not sync imported device due to pending errors > '.$sys_name.' - '.$sys_location.'</div>';
				$sync_errors++;
			}
		}
		echo '<br /><br /><div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">Sync Summary</div>
				<div class="panel-body">
				  Imported Successfully: <b>'.$sync_successes.'</b><br />
				  Skipped Due to Errors: <b>'.$sync_errors.'</b>
				</div>
			  </div>
			  <br />';
		include_once('footer2.php');die;
	}
?>
<style type="text/css">
.footable-row-detail-name {
	text-align: right;
}
.footable-row-detail-value > input {
	margin-bottom: 5px;
}
</style>

<br /><br />
<div class="row">
    <div class="col-xs-8 col-sm-5 col-md-5">
      <input id="filter1" class="form-control" placeholder="Filter" type="text"/>
    </div>
    <div class="col-xs-4 col-sm-7 col-md-7">
		<button type="submit" class="btn btn-danger clear-filter clear-filter" onClick="$('table').trigger('footable_clear_filter');">Reset Filters</button>
    </div>
</div>
<br />
<table class="table footable toggle-square" data-filter="#filter1" data-page-size="10">
  <thead>
    <tr>
      <th data-toggle="true">Computer Name</th>
      <th data-hide="phone">IP Address</th>
	  <th data-hide="phone">Location</th>
      <th data-hide="phone">Import Time</th>
      <th data-hide="phone,tablet">Name</th>
      <th data-hide="phone,tablet">Manufacturer</th>
      <th data-hide="phone,tablet">Device Type</th>
      <th data-hide="phone,tablet">Model</th>
      <th data-hide="phone,tablet">IP Addresses</th>
      <th data-hide="phone,tablet">Serial Number</th>
      <th data-hide="phone,tablet">Location</th>
      <th data-hide="phone,tablet">Operating System</th>
      <th data-hide="phone,tablet">Original Install Date</th>
      <th data-hide="phone,tablet">Description</th>
      <th data-hide="phone,tablet">Processor Name</th>
      <th data-hide="phone,tablet">Processor Max Speed</th>
      <th data-hide="phone,tablet">Processor Cores</th>
      <th data-hide="phone,tablet">Logical Processors</th>
      <th data-hide="phone,tablet">Memory (GB)</th>
      <th data-hide="phone,tablet">Enabled NICs</th>
      <th data-hide="phone,tablet">Total NICs</th>
      <th data-hide="phone,tablet">MAC Address</th>
      <th data-hide="phone,tablet">Jack ID</th>
      <th data-hide="phone,tablet">User Info</th>
      <th data-hide="phone,tablet">Import ID</th>
      <th data-hide="phone,tablet">Import Time</th>
    </tr>
  </thead>
  <tbody>
  <?php
    $query = 'SELECT * FROM  import__device';
	$result = $db_link->query($query);
	while($row = $result->fetch_array()) {
		$import_time = strtotime($row["import_timestamp"]);
		$install_time = strtotime($row["install_date"]);
		
		$cur_ip = explode(';',trim($row["ip_addresses"]))[0];
		if ($cur_ip == "0.0.0.0") {
			$cur_ip = "none";
			$cur_ip_long = -1;
		}
		else if (filter_var($cur_ip, FILTER_VALIDATE_IP)) {
			$cur_ip_long = explode('.',$cur_ip);
			$cur=0;
			foreach ($cur_ip_long as $cur_oct) {
				$cur_ip_long[$cur] = strval(sprintf("%03s",$cur_oct));
				$cur++;
			}
			$cur_ip_long = implode('.',$cur_ip_long);
		}
		else {
			$cur_ip = "none";
			$cur_ip_long = -1;
		}
		
		echo'<tr>
          <td>'.$row["name"].'</td>
          <td data-value="'.$cur_ip_long.'">'.$cur_ip.'</td>
		  <td>'.$row["location"].'</td>
          <td data-value="'.sprintf("%010s",$row["id"]).'">'.date('n/j/Y - H:i:s', $import_time).'</td>';
		
		$fields = array("name","manufacturer","device_type","model",
						"ip_addresses","serial_number","location","os",
						"install_date","description","proc_name",
						"proc_max_speed","proc_cores","logical_proc","memory",
						"nics_enabled","nics_total","mac_addresses","jack_id",
						"users");
		$editable_fields = array();
		foreach($fields as $cur_field) {
			if (in_array($cur_field,$editable_fields)) {
				echo '<td><input id="'.$cur_field.'_'.$row["id"].'" class="form-control" type="text" value="'.$row[$cur_field].'"/></td>';
			}
			else if ($cur_field=="install_date") {
				echo '<td>'.date("l, F j, Y @ g:i a", strtotime($row[$cur_field])).'</td>';
			}
			else if ($cur_field=="users") {
				echo '<td><b>username~password_set~last_login</b><br />';
				$user_list = explode(";",$row["users"]);
				foreach($user_list as $cur_user) {
					echo $cur_user."<br />";
				}
				echo'</td>';
			}
			else {
				echo '<td>'.$row[$cur_field].'</td>';
			}
		}
        echo '<td>'.$row["id"].'</td>
          <td>'.date("l, F j, Y @ g:i a", $import_time).'</td>
		</tr>';
	}
	
	$total_dev = mysqli_num_rows($result);
	
	$query = 'SELECT count(*) AS num FROM import__device WHERE os LIKE "%XP%"';
	$result = $db_link->query($query);
	$result = mysqli_fetch_assoc($result);
	$num_xp = intval($result["num"]);
	$query = 'SELECT count(*) AS num FROM import__device WHERE os LIKE "%7%"';
	$result = $db_link->query($query);
	$result = mysqli_fetch_assoc($result);
	$num_7 = intval($result["num"]);
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


<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Summary</div>
  <div class="panel-body">
    Number of Windows XP Machines: <b><?=$num_xp;?></b><br />
	Number of Windows 7 Machines: <b><?=$num_7;?></b><br />
	Total Inventoried Devices: <b><?=$total_dev;?></b>
  </div>
</div>
<br />

<?php
	echo '<form name="import_devices" action="#" method="POST">
		<input type="hidden" name="submit_confirm" value="confirm_phrase" />
		<button type="submit" name="submit" value="submit" class="btn btn-primary clear-filter clear-filter">Process Importable Inventory Data</button>
	</form>';
	
    include_once('footer2.php');
?>
<script src="/FooTable-2/js/footable.js?v=2-0-1" type="text/javascript"></script>
<script src="/FooTable-2/js/footable.sort.js?v=2-0-1" type="text/javascript"></script>
<script src="/FooTable-2/js/footable.filter.js?v=2-0-1" type="text/javascript"></script>
<!--<script src="/FooTable-2/js/footable.paginate.js?v=2-0-1" type="text/javascript"></script>-->
<script type="text/javascript">
  $(function () {
    $('table').footable();
    //$('table').trigger('footable_expand_all');
  });
</script>