<?php
// Display Alarm requirements.
$num = $_GET['num'];
if ($num == 1) {
?>	
<b>Alarm Pin:</b> (four digit pin) <span class="field_required">*</span><br />
<input name="alarm_pin" id="alarm_pin" type="text" size="5" class="" value="" required /><br /><br />
<b>Alarm Password: <span class="field_required">*</span></b><br />
<input name="alarm_password" id="alarm_password" type="password" size="5" class="" value="<?php if (isset($_SESSION["formdata"]["alarm_password"])) {echo $_SESSION["formdata"]["alarm_password"];} ?>" required /><br /><br />
<b>Alarm Access Area: <span class="field_required">*</span></b><br />
<input type="checkbox" name="alarm_area[]" value="Catalyst Café"> Catalyst Café <br />
<input type="checkbox" name="alarm_area[]" value="Cash Room"> Cash Room (Cash room employees only) <br />
<input type="checkbox" name="alarm_area[]" value="Computer Support"> Computer Support  <br />
<input type="checkbox" name="alarm_area[]" value="Slot Canyon"> Slot Canyon <br />
<input type="checkbox" name="alarm_area[]" value="Esports Arena"> Esports Arena  <br />
<input type="checkbox" name="alarm_area[]" value="Global Cash Room"> Global Cash Room  <br />
<input type="checkbox" name="alarm_area[]" value="Global Market"> Global Market  <br />
<input type="checkbox" name="alarm_area[]" value="Global Food Court "> Global Food Court  <br />
<input type="checkbox" name="alarm_area[]" value="Highland Market"> Highland Market  <br />
<input type="checkbox" name="alarm_area[]" value="Meal Plan Office"> Meal Plan Office  <br />
<input type="checkbox" name="alarm_area[]" value="S tarbucks Student Union"> S tarbucks Student Union  <br />
<input type="checkbox" name="alarm_area[]" value="Arizona Market"> Arizona Market  <br /><br />
<b>Other:</b><br />
<input name="alarm_other" id="alarm_other" type="text" size="20" value="" />
<?php
	}
?>