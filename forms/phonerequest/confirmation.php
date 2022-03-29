<?php 
	$fields = ["supervisor_name", "supervisor_phone", "employee_status", "building", "room_number", "net_id", "jack", "jack_id", "voicemail", "long_distance", "need_phone", "call_appearance", "kfs_number", "call_appearance1", "call_appearance2", "call_appearance3", "call_appearance4"];

	$field_name= ["Supervisor Name", "Supervisor Phone", "Employee Status", "Building", "Room Number", "Net ID", "Jack", "Jack ID", "Voicemail", "Long Distance", "Need Phone", "Call Appearance", "KFS Number", "Call Appearance 1", "Call Appearance 2", "Call Appearance 3", "Call Appearance 4"];


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" type="text/css" href="css/phonerequest.css">
	<title>Phone Setup Request</title>
</head>
<body>
	<div class="container" align="center">
		<div class="jumbotron">

			<a href="https://tech.union.arizona.edu/">
				<div><img src="/images/white-blue-banner.gif" /></div><div class="logoheader"><img src="/images/techheader.png" /></div>
			</a>

			<div class="content" align="left">
				<h2>Phone Setup Request Confirmation</h2>
				<table class="table">
					<tbody>

						<?php
							$i = 0;
							foreach($fields as $field) {
								if(($field == 'call_appearance1' ||
									$field == 'call_appearance2' ||
									$field == 'call_appearance3' ||
									$field == 'call_appearance4') &&
									$_POST['call_appearance'] == "no")
									continue;

								if($field == 'jack_id' && $_POST['jack'] == "no") {
									$i++;
									continue;
								}

								echo "<tr>";
								echo "<th scope=\"row\">$field_name[$i]</th>";
								echo "<td>$_POST[$field]</td>";
								echo "</tr>";
								$i++;
							}
						?>
					</tbody>
				</table>

				<div align="center" class="request_button">
					<a href="/">
						<button type="button" class="btn btn-primary">Return to Home</button>
					</a>	
				</div>
			</div>
		</a>
	</div>
</div>
</body>
</html>