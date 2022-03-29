<?php
	$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	include_once('header2.php');
	require_once("mysql/include.php");
	$db_link = select_db("access");
	//ini_set('display_errors', '1');
	//error_reporting(E_ALL);
	$fields = array("name" => "Name",
				"manufacturer" => "Manufacturer",
				"device_type" => "Device Type",
				"model" => "Model",
				"serial_number" => "Serial Number",
				"location" => "Location",
				"os" => "Operating System",
				"install_date" => "Install Date",
				"description" => "Description",
				"proc_name" => "Processor - Name",
				"proc_max_speed" => "Processor - Max Spee",
				"proc_cores" => "Processor - Cores",
				"logical_proc" => "Processor - Logical Cores",
				"memory" => "Memory (GB)",
				"nics_enabled" => "NICs Enabled",
				"nics_total" => "NICs Total",
				"asset_tag" => "Asset Tag");
	$examples = array("name" => "SU-CS-WKS2001",
				"manufacturer" => "Dell",
				"device_type" => "Desktop",
				"model" => "Latitude E5430",
				"serial_number" => "ABC123",
				"location" => "BLDG XX RM XXX - Department - Use",
				"os" => "Microsoft Windows 7 Enterprise 64-bit SP1",
				"install_date" => "02/17/2014",
				"description" => "Wilbur's Workstation",
				"proc_name" => "Intel(R) Core(TM) i7-3770 CPU @ 3.40GHz",
				"proc_max_speed" => "3401",
				"proc_cores" => "4",
				"logical_proc" => "8",
				"memory" => "8",
				"nics_enabled" => "1",
				"nics_total" => "3",
				"asset_tag" => "A012345");
	$editable = array("location","asset_tag");
?>
<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li class="active">Edit Device</li>
</ol>
<h1>Edit Device</h1>
<?php
	if ($_POST["submit"]=="submit") {
		if (intval($_POST["submit_id"])>0) {
			$query = 'UPDATE sys__device SET ';
			foreach($editable as $cur_field) {
				$_POST[$cur_field] = strip_tags(mysql_real_escape_string(trim($_POST[$cur_field])));
				if (!isset($_POST[$cur_field])||$_POST[$cur_field]=="") {
					$query .= $cur_field.' = NULL,';
				}
				else {
					$query .= $cur_field.' = "'.$_POST[$cur_field].'",';
				}
			}
			$query = rtrim($query, ",");
			$query .= ' WHERE sys__device.id="'.intval($_POST["submit_id"]).'"';
			$result = $db_link->query($query);
			if ($result) {
				echo '<div class="alert alert-success">Changes to '.strip_tags(mysql_real_escape_string(trim($_POST["display_name"]))).' were successful.</div>';
			}
			else {
				echo '<div class="alert alert-danger"><b>ERROR: </b>'.$db_link->error.'</div>';
			}
		}
		else {
			echo 'submitted without hidden value?';
			die;
		}
	}

	if (intval($_GET["device_id"])>0) {
		$device_id = intval($_GET["device_id"]);
		$query = 'SELECT * FROM sys__device WHERE id="'.$device_id.'"';
		$result = $db_link->query($query);
		if ($result) {
			$cur_device = $result->fetch_array();
		}
		else {
			echo 'ERROR: Unable to fetch current department.';
			die;
		}
?>
<div class="theme_form row">
	<!-- form: -->
	<section>
		<form id="defaultForm" method="post" class="form-horizontal">
			<div class="">
				
				<?php
				foreach ($fields as $cur_field => $cur_title) {
					echo '<div class="form-group col-md-6 col-sm-6">
						<label class="control-label">'.$cur_title.'</label>
						<div style="margin-right: 20px;">
							<input type="text" class="form-control" name="'.$cur_field.'" id="'.$cur_field.'" placeholder="eg. '.$examples[$cur_field].'" value="';
					if (isset($cur_device[$cur_field])&&$cur_device[$cur_field]!="") {
						echo $cur_device[$cur_field];
					}
					echo '" ';
					if (!in_array($cur_field,$editable)) {
						echo 'disabled ';
					}
					echo '/>
						</div>
					</div>';
				}
				echo '<input type="hidden" name="submit_id" value="'.$device_id.'" />';
				?>
				<div class="col-xs-12" style="margin-top: 20px;padding: 0;">
					<div id="dev_user_footable" class="form-group col-xs-12 col-sm-6">
						<table class="table footable toggle-square" data-filter="#filter1" data-page-size="255">
							<thead>
							  <tr>
								<th data-toggle="true">NetID</th>
								<th data-hide="phone">Novell Username</th>
								<th>Name</th>
								<th data-hide="phone,tablet">Departments</th>
								<th data-hide="phone,tablet">Supervisor?</th>
								<th data-hide="phone,tablet" data-sort-ignore="true"></th>
							  </tr>
							</thead>
							<tbody>
								<tr>
									<?php
										$query2 = 'SELECT
											user.id as cur_user_id,
											user.name as user_name,
											user.alias as user_alias,
											user.emplid as user_emplid,
											user.netid as user_netid,
											user.novell_id as user_novell_id,
											department.display_name as dep_name,
											department.phone as dep_phone,
											department.acct_num as dep_acct_num,
											department.foodpro_num as dep_foodpro_num
										FROM sys__user_account
										LEFT JOIN user
											ON sys__user_account.user_id = user.id
										LEFT JOIN role
											ON user.id = role.user_id
										LEFT JOIN department
											ON role.department_id = department.id
										WHERE
											sys__user_account.active = TRUE AND
											sys__user_account.device_id = "'.$device_id.'" AND
											user.active = TRUE
										ORDER BY user.netid';
										//var_dump($query2);
										$result2 = $db_link->query($query2);
										$num_users = mysqli_num_rows($result2);
										if ($num_users > 0) {
											while($row2 = $result2->fetch_array()) {
												if (!isset($row2["user_alias"]) || $row2["user_alias"] == "") {
													$row2["user_alias"] = $row2["user_name"];
												}
												if (!isset($row2["user_novell_id"]) || $row2["user_novell_id"] == "") {
													$row2["user_novell_id"] = "-";
												}
												echo '<tr>
													<td>'.$row2["user_netid"].'</td>
													<td>'.$row2["user_novell_id"].'</td>
													<td>'.$row2["user_alias"].'</td>
													<td>';
													
												/*$query3 = 'SELECT * FROM sys__dns_registration WHERE sys__dns_registration.ip_address_id = '.$row2["cur_ip_id"];
												$result3 = $db_link->query($query3);
												$num_dns = mysqli_num_rows($result3);
												if ($num_dns > 0) {
													while($row3 = $result3->fetch_array()) {
														echo $row3["name"].'.'.$row3["subdomain"].'<br />';
													}
												}
												else {*/
													echo '-';
												//}
													echo '</td>
													<td>-</td>
													<td><span style="color: #d9534f;"><i class="fa fa-minus-square"></i> Remove</span></td>
												</tr>';
											}	
										}
										else {
											echo '<tr>
												<td colspan=5>None Assigned</td>
											</tr>';
										}
									?>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4" style="text-align: right;">
										<br />
										<button class="btn btn-success toggle" type="button" >
										<i class="fa fa-plus-square"></i> Add User Login</button>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					
					<div class="form-group col-xs-12 col-sm-6">
						<table class="table footable toggle-square" data-filter="#filter2" data-page-size="255">
							<thead>
							  <tr>
								<th data-toggle="true">MAC Adddress</th>
								<th data-sort-ignore="true"></th>
							  </tr>
							</thead>
							<tbody>
								<tr>
									<?php
										$query2 = 'SELECT
											sys__mac_address.address as mac_address,
											sys__mac_address.id as cur_mac_id
										FROM sys__mac_address
										WHERE
											sys__mac_address.device_id = "'.$device_id.'" AND
											sys__mac_address.active = TRUE';
										$result2 = $db_link->query($query2);
										$num_ips = mysqli_num_rows($result2);
										if ($num_ips > 0) {
											while($row2 = $result2->fetch_array()) {
												echo '<tr>
													<td>'.$row2["mac_address"].'</td>
													<td><span style="color: #d9534f;"><i class="fa fa-minus-square"></i> Remove</span></td>
												</tr>';
											}	
										}
										else {
											echo '<tr>
												<td>None Assigned</td>
												<td><span style="color: #666;"><i class="fa fa-minus-square"></i> Remove</span></td>
											</tr>';
										}
									?>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4" style="text-align: right;">
										<br />
										<button class="btn btn-success toggle" type="button" >
										<i class="fa fa-plus-square"></i> Add MAC Address</button>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				
				<div class="form-group col-xs-12">
					<table class="table footable toggle-square" data-filter="#filter3" data-page-size="255">
						<thead>
						  <tr>
							<th data-toggle="true">IP Adddress</th>
							<th data-hide="phone">Subnet Mask</th>
							<th data-hide="phone">Gateway</th>
							<th data-hide="phone">Jack ID</th>
							<th data-hide="phone,tablet">Notes</th>
							<th data-hide="phone,tablet">DNS Registrations</th>
							<th data-hide="phone,tablet">Net Segment - Name</th>
							<th data-hide="phone,tablet">Net Segment - Location</th>
							<th data-hide="phone,tablet">Net Segment - VLAN</th>
							<th data-hide="phone,tablet">Net Segment - Notes</th>
							<th data-hide="phone,tablet" data-sort-ignore="true"></th>
						  </tr>
						</thead>
						<tbody>
							<tr>
								<?php
									$query2 = 'SELECT
										sys__ip_address.id as cur_ip_id,
										sys__ip_address.address as ip_address,
										sys__ip_address.jack_id as ip_jack_id,
										sys__ip_address.note as ip_note,
										sys__net_segment.name as seg_name,
										sys__net_segment.location as seg_location,
										sys__net_segment.vlan as seg_vlan,
										sys__net_segment.mask as seg_mask,
										sys__net_segment.gateway as seg_gateway,
										sys__net_segment.note as seg_note
									FROM sys__ip_address
									LEFT JOIN sys__net_segment
										ON sys__ip_address.net_segment_id = sys__net_segment.id
									WHERE
										sys__ip_address.device_id = "'.$device_id.'" AND
										sys__ip_address.active = TRUE
									ORDER BY sys__ip_address.net_segment_id, INET_ATON(sys__ip_address.address), sys__ip_address.timestamp';
									$result2 = $db_link->query($query2);
									$num_ips = mysqli_num_rows($result2);
									if ($num_ips > 0) {
										while($row2 = $result2->fetch_array()) {
											echo '<tr>
												<td>'.$row2["ip_address"].'</td>
												<td>'.$row2["seg_mask"].'</td>
												<td>'.$row2["seg_gateway"].'</td>
												<td>'.$row2["ip_jack_id"].'</td>
												<td>'.$row2["ip_note"].'</td>
												<td>';
												
											$query3 = 'SELECT * FROM sys__dns_registration WHERE sys__dns_registration.ip_address_id = '.$row2["cur_ip_id"];
											$result3 = $db_link->query($query3);
											$num_dns = mysqli_num_rows($result3);
											if ($num_dns > 0) {
												while($row3 = $result3->fetch_array()) {
													echo $row3["name"].'.'.$row3["subdomain"].'<br />';
												}
											}
											else {
												echo '-';
											}
												echo '</td>
												<td>'.$row2["seg_name"].'</td>
												<td>'.$row2["seg_location"].'</td>
												<td>'.$row2["seg_vlan"].'</td>
												<td>'.$row2["seg_note"].'</td>
												<td><span style="color: #d9534f;"><i class="fa fa-minus-square"></i> Remove</span></td>
											</tr>';
										}	
									}
									else {
										echo '<tr>
											<td>None Assigned</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td><span style="color: #666;"><i class="fa fa-minus-square"></i> Remove</span></td>
										</tr>';
									}
								?>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4" style="text-align: right;">
									<br />
									<button class="btn btn-success toggle" type="button" >
									<i class="fa fa-plus-square"></i> Add IP Address</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				
			</div>
			<div class="form-group col-xs-12">
				<div>
					<br />
					<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit Changes</button>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
						if (strval($_GET["return"]) =="ips") {
							echo '<a href="/access/devices/ips"><button type="button" class="btn btn-default">Return to IP List</button></a>';
						}
						else {
							echo '<a href="/access"><button type="button" class="btn btn-default">Return to Device List</button></a>';
						}
					?>
				</div>
			</div>
		</form>
	</section>
	<!-- :form -->
</div>

<?php
}
else {
	echo '<div class="alert alert-danger"><b>ERROR:</b> Invalid Department ID.</div>';
}
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
<script type="text/javascript" src="/js/bootstrapValidator.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#defaultForm').bootstrapValidator({
        message: 'Changes are not valid',
        fields: {
			location: {
                validators: {
                    notEmpty: {
                        message: '\'Location\' is required and can\'t be empty'
                    }
                }
            }
        }
    });
});
</script>