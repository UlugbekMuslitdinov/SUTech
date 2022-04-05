<!DOCTYPE html>

<?php
//	define('__ROOT__', dirname(dirname(__FILE__, $levels=2))); //Note $levels=2 tells dirname() to return parent directory path two levels up, not default one, because thsi index.php needs to escape /forms folder to /sucs which is  two levels away
	require_once($_SERVER["DOCUMENT_ROOT"].'/template/header.php');
	//require_once(__ROOT__.'/template/sidebar.php');
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/phonerequest.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
	<script type="text/javascript" src="js/phonerequest.js"></script>
	<title>Phone Setup Request</title>
</head>
<body>
	<div class="container" align="center">
		<!--<div class="jumbotron" style="padding-right: 10px;">
			<a href="https://tech.union.arizona.edu/">
				<div><img src="/images/white-blue-banner.gif" /></div><div class="logoheader" align="center"><img src="/images/techheader.png" /></div>
			</a> -->
			<div class="content" align="left">
				<h2>Phone Setup Request</h2><hr />
				<form role="form" class="needs-validation" data-toggle="validator" action="submit.php" method="POST">
					<div><h4>Supervisor Information</h4></div>
					<div class="form-row form-group">
						<div class="form-group col-md-6 has-feedback">
							<label for="supervisor_name" class="control-label">Requesting Supervisor</label>
							<input type="text" class="form-control" id="supervisor_name" name="supervisor_name" placeholder="Supervisor's Name" required>
							<div class="invalid-feedback">
								Please fill out this field.
							</div>
						</div>
						<div class="form-group has-feedback col-md-6">
							<label for="supervisor_phone" class="control-label">Phone Number</label>
							<input type="tel" class="form-control" id="supervisor_phone" name="supervisor_phone" placeholder="(xxx) xxx-xxxx" required>
							<div class="invalid-feedback">
								Please fill out this field.
							</div>
						</div>
					</div>

					<div class="form-group form-check form-check-inline">
						<input class="form-check-input" type="radio" name="employee_status" id="new_employee" value="new_employee" required>
						<label class="form-check-label" for="new_employee">New Employee</label>
						<div class="invalid-feedback">
							Please fill out this field.
						</div>
					</div>
					<div class="form-group form-check form-check-inline">
						<input class="form-check-input" type="radio" name="employee_status" id="update_employee" value="update_employee" data-error="Please fill out this field." required>
						<label class="form-check-label" for="update_employee">Update Employee</label>
						<div class="invalid-feedback">
							Please fill out this field.
						</div>
					</div>
					<div class="form-group has-feedback">
						<h4>Building and Room Information</h4>
						<div class="form-row form-group has-feedback">
							<div class="form-group has-feedback col-md-6">
								<label for="building">Building</label>
								<input type="text" class="form-control" name="building" id="building" required>
								<div class="invalid-feedback">
									Please fill out this field.
								</div>
							</div>
							<div class="form-group col-md-6 has-feedback">
								<label for="room_number">Room Number</label>
								<input type="text" class="form-control" name="room_number" id="room_number" required>
								<div class="invalid-feedback">
									Please fill out this field.
								</div>
							</div>
						</div>


						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="net_id" class="form-check-label">Net ID</label>
								<input type="text" class="form-control" name="net_id" id="net_id" required>
								<div class="invalid-feedback">
									Please fill out this field.
								</div>
							</div>
						</div>
					</div>


					<div class="form-group">
						<h4>Phone Information</h4>
						<div class="form-group">
							<p>Is there a network jack available in the area?</p>
							<div class="form-check" onclick="jack_id()">
								<input class="form-check-input" type="radio" name="jack" id="jack1" value="yes">
								<label class="form-check-label" for="jack1">
									Yes
								</label>
								<div id="jack_id" style="display: none;" class="jack_id">
									<label for="jack">Jack ID</label>
									<input class="form-control col-md-6" type="text" name="jack_id" id="jack">
									<div class="invalid-feedback">
										Please fill out this field.
									</div>
								</div>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jack" id="jack2" value="no" onclick="disable_jack_id()" checked>
								<label class="form-check-label" for="jack2">
									No
								</label>
							</div>
						</div>

						<div class="form-group">
							<p>Would you like voicemail?</p>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="voicemail" id="voicemail1" value="yes" checked>
								<label class="form-check-label" for="voicemail1">
									Yes
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="voicemail" id="voicemail2" value="no">
								<label class="form-check-label" for="voicemail2">
									No
								</label>
							</div>
						</div>

						<div class="form-group">
							<p>Does this user need long distance restrictions?</p>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="long_distance" id="long_distance1" value="yes" checked>
								<label class="form-check-label" for="long_distance1">
									Yes
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="long_distance" id="long_distance2" value="no">
								<label class="form-check-label" for="long_distance2">
									No
								</label>
							</div>
						</div>

						<div class="form-group">
							<p>Does this user need a new phone added to the location or will one be provided?</p>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="need_phone" id="need_phone1" value="yes" checked>
								<label class="form-check-label" for="need_phone1">
									Yes
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="need_phone" id="need_phone2" value="no">
								<label class="form-check-label" for="need_phone2">
									No
								</label>
							</div>
						</div>

						<div class="form-group">
							<p>Do any other call appearances need to be added on the presets of the phone?</p>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="call_appearance" id="call_appearance_yes" value="yes" onclick="enable_call_appearance()">
								<label class="form-check-label" for="call_appearance_yes">
									Yes
								</label>
								<div style="display: none;" id="call_appearance">
									<?php
									for ($i=1; $i < 5; $i++) { 
										echo "
										<div id='call_appearance" . $i . " class='jack_id'>
										<label for='call_appearance" . $i . "'>Call Appearance " . $i . ": </label>
										<input class='form-control col-md-6' type='text' name='call_appearance" . $i . "'>
										<div class='invalid-feedback'>
										Please fill out this field.
										</div>";

										echo "</div>";
									}
									?>
								</div>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="call_appearance" value="no" onclick="disable_call_appearance()" checked>
								<label class="form-check-label" for="call_appearance_no">
									No
								</label>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="kfs_number">KFS Number</label>
								<input type="text" class="form-control" name="kfs_number" id="kfs_number">
							</div>
						</div>

						<div align="center" class="request_button">
							<button type="submit" class="btn btn-primary">Send Request</button>	
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>

<?php
//require_once("/template/footer.php");
require_once($_SERVER["DOCUMENT_ROOT"].'template/footer.php');
?>