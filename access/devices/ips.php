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
  <li class="active">Devices (IP View)</li>
</ol>
<h1>Devices (IP View)</h1>

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
<br />
<table id="dev_table" class="footable table table-striped toggle-square">
  <thead>
    <tr>
      <th data-class="expand">IP</th>
	  <th data-hide="phone">Name</th>
	  <th data-hide="phone,tablet">Location</th>
    </tr>
  </thead>
</table>
<?php
/*
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
        ORDER BY sys__ip_address.net_segment_id, INET_ATON(sys__ip_address.address), sys__ip_address.timestamp
        LIMIT $start, $limit";
*/
    include_once('footer2.php');
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#dev_table').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "./ips_ajax.php",
		"fnDrawCallback": function (oSettings) {  
           var $dt = $('#myTable').footable({  
             breakpoints: { // The different screen resolution breakpoints  
               phone: 320,  
               tablet: 768  
             }  
           });  
           $dt.trigger('footable_resize');  
         }
    } );
} );
</script>