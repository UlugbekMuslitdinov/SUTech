<?php
	$webauth_script_override = "/access/index.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("access");
    
    // the table name
	$tableName="sys__ip_address";
    // How many records you want to show on each page
	$limit = 255; 
	// this is the page which should be targeted. If you call this script
	// on a page named about.php then 'about.php' should be the target page
	$targetpage = "index.php";
    require_once 'paginate.php';

?>

<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li class="active">Devices (All Active)</li>
</ol>
<h1>Devices (All Active)</h1>

<style type="text/css">
.footable-row-detail-name {
	font-weight: bold;
}
.footable-row-detail-value > input {
	margin-bottom: 5px;
}
.footable-row-detail {
    background: #f5f5f5;
}
</style>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<br />
<table class="table footable toggle-square" data-filter="#filter1" data-page-size="255">
  <thead>
    <tr>
      <th data-toggle="true">IP</th>
	  <th data-hide="phone">Name</th>
	  <th data-hide="phone">Location</th>
	  <th data-hide="phone">Last Updated</th>
	  <th data-hide="phone,tablet">IP Note</th>
	  <th data-hide="phone,tablet">Jack ID</th>
	  <th data-hide="phone,tablet">Manufactuer</th>
	  <th data-hide="phone,tablet">Model</th>
	  <th data-hide="phone,tablet">Serial Number</th>
	  <th data-hide="phone,tablet">Device Type</th>
	  <th data-hide="phone,tablet">DNS Registrations</th>
      <th data-hide="phone,tablet">Net Segment - Name</th>
      <th data-hide="phone,tablet">Net Segment - Location</th>
      <th data-hide="phone,tablet">Net Segment - VLAN</th>
      <th data-hide="phone,tablet">Net Segment - Mask</th>
	  <th data-hide="phone,tablet">Net Segment - Gateway</th>
      <th data-hide="phone,tablet">Net Segment - Notes</th>
	  <th data-hide="phone,tablet"></th>
    </tr>
  </thead>
  <tbody>
<?php

    // Get page data
	$query = "SELECT
            sys__ip_address.device_id as device_id,
			sys__ip_address.address as ip_address,
            sys__ip_address.jack_id as ip_jack_id,
			sys__ip_address.note as ip_note,
            sys__ip_address.timestamp as ip_timestamp,
            sys__net_segment.name as seg_name,
            sys__net_segment.location as seg_location,
            sys__net_segment.vlan as seg_vlan,
            sys__net_segment.mask as seg_mask,
			sys__net_segment.gateway as seg_gateway,
            sys__net_segment.note as seg_note,
			sys__dns_registration.name as dns_name,
			sys__dns_registration.subdomain as dns_subdomain,
			sys__device.name as dev_name,
			sys__device.manufacturer as dev_manufacturer,
			sys__device.device_type as dev_device_type,
			sys__device.model as dev_model,
			sys__device.serial_number as dev_serial_number,
			sys__device.location as dev_location
        FROM sys__ip_address
        LEFT JOIN sys__net_segment
			ON sys__ip_address.net_segment_id = sys__net_segment.id
		LEFT JOIN sys__dns_registration
			ON sys__ip_address.id = sys__dns_registration.ip_address_id
		LEFT JOIN sys__device
			ON sys__ip_address.device_id = sys__device.id
		WHERE
			sys__ip_address.active = TRUE
			AND (
				sys__ip_address.device_id IS NOT NULL 				 
			)
        ORDER BY sys__ip_address.net_segment_id, INET_ATON(sys__ip_address.address), sys__ip_address.timestamp
        LIMIT $start, $limit";
	$result = $db_link->query($query);
	while($row = $result->fetch_array()) {
        if (!isset($row["jack_id"])||$row["jack_id"]=="") {
            $row["jack_id"] = "-";
        }
		 if (!isset($row["dev_location"])||$row["dev_location"]=="") {
            $row["dev_location"] = substr($row["ip_note"], 0, 60);
        }
		echo'<a name="dep_'.$row["id"].'"></a>
		<tr>
          <td>'.$row["ip_address"].'</td>
		  <td>'.$row["dev_name"].'</td>
		  <td>'.$row["dev_location"].'</td>
          <td>'.$row["ip_timestamp"].'</td>
		  <td>'.$row["ip_note"].'</td>
		  <td>'.$row["ip_jack_id"].'</td>
		  <td>'.$row["dev_manufacturer"].'</td>
		  <td>'.$row["dev_model"].'</td>
		  <td>'.$row["dev_serial_number"].'</td>
		  <td>'.$row["dev_device_type"].'</td>
		  <td>'.$row["dns_name"].'.'.$row["dns_subdomain"].'</td>
		  <td>'.$row["seg_name"].'</td>
          <td>'.$row["seg_location"].'</td>
          <td>'.$row["seg_vlan"].'</td>
          <td>'.$row["seg_mask"].'</td>
		  <td>'.$row["seg_gateway"].'</td>
          <td>'.$row["seg_note"].'</td>
		  <td>';
		if ($row["device_id"]>0) {
			echo '<br /><a href="edit.php?device_id='.$row["device_id"].'&return=ips" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Device</a><br />';	
		}
		echo '</td>
		</tr>';
	}
?>
          </tbody>
          <tfoot>
          <tr>
          <td colspan="3">
                <br /><?=$paginate?>
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