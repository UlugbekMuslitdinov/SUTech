<?php
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");

    mysql_connect("150.135.72.235", "labpc", "l@bpc!") or die(mysql_error());
    mysql_select_db("pgina") or die(mysql_error());

    $date = date('d-m-Y');
    if ($_POST['generate']=="Generate Report" && isset($_POST['datepicker']) && $_POST['datepicker']!="") {
        $date = date('d-m-Y',strtotime($_POST['datepicker']));
    }
    // Assuming $date is in format DD-MM-YYYY
    list($day, $month, $year) = explode("-", $date);

    // Get the weekday of the given date
    $wkday = date('l',mktime('0','0','0', $month, $day, $year));

    switch($wkday) {
        case 'Monday': $numDaysToMon = 0; break;
        case 'Tuesday': $numDaysToMon = 1; break;
        case 'Wednesday': $numDaysToMon = 2; break;
        case 'Thursday': $numDaysToMon = 3; break;
        case 'Friday': $numDaysToMon = 4; break;
        case 'Saturday': $numDaysToMon = 5; break;
        case 'Sunday': $numDaysToMon = -1; break;
    }

    // Timestamp of the monday for that week
    $monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);

    $seconds_in_a_day = 86400;

    // Get date for 7 days from Monday (inclusive)
    for($i=-1; $i<6; $i++)
    {
        $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
    }

    foreach ($dates as $daynum => $day) {
        $clink_query = 'SELECT * FROM pgina.logs WHERE loginstamp > "'.date("Y-m-d H:i:s",strtotime($day)).'" AND loginstamp < "'.date("Y-m-d H:i:s",strtotime($day." +1 day")).'" AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "yontaek" AND username NOT LIKE "__ayontaek" AND username NOT LIKE "macadmin" AND machine LIKE "%CLINK%" ORDER BY loginstamp DESC';
        $kachina_query = 'SELECT * FROM pgina.logs WHERE loginstamp > "'.date("Y-m-d H:i:s",strtotime($day)).'" AND loginstamp < "'.date("Y-m-d H:i:s",strtotime($day." +1 day")).'" AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "yontaek" AND username NOT LIKE "__ayontaek" AND username NOT LIKE "macadmin" AND machine LIKE "%KACH%" ORDER BY loginstamp DESC';
        $psu_query = 'SELECT * FROM pgina.logs WHERE loginstamp > "'.date("Y-m-d H:i:s",strtotime($day)).'" AND loginstamp < "'.date("Y-m-d H:i:s",strtotime($day." +1 day")).'" AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "yontaek" AND username NOT LIKE "__ayontaek" AND username NOT LIKE "macadmin" AND machine LIKE "%PSU%" ORDER BY loginstamp DESC';

        $result = mysql_query($clink_query)
        or die(mysql_error());
        $clink[$daynum] = mysql_num_rows($result);

        $result = mysql_query($kachina_query)
        or die(mysql_error());
        $kachina[$daynum] = mysql_num_rows($result);

        $result = mysql_query($psu_query)
        or die(mysql_error());
        $psu[$daynum] = mysql_num_rows($result);
    }
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css" type="text/css" />
<script type="text/javascript">
      $(function() {
          $( "#datepicker" ).datepicker({
              showOn: "button",
              buttonImage: "/images/calendar.gif",
              buttonImageOnly: true,
              maxDate: 0
          });
      });
</script>
<style>
.ui-datepicker-trigger {
    margin-left: 5px;
    vertical-align:middle;
}
</style>
<h1>Weekly Lab Usage Report</h1><br />
<form id="date_form" name="date_form" method="post" action="#">
<div>
    Date: <input type="text" name="datepicker" id="datepicker" />&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Generate Report" name="generate" />
</div>
</form>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Day of the Week');
        data.addColumn('number', 'Campus Link Kiosks');
        data.addColumn('number', 'Kachina Lab');
        data.addColumn('number', 'PSU Lab');
        data.addRows([
          ['Sunday', <?php echo $clink[-1].", ".$kachina[-1].", ".$psu[-1] ?>],
          ['Monday', <?php echo $clink[0].", ".$kachina[0].", ".$psu[0] ?>],
          ['Tuesday', <?php echo $clink[1].", ".$kachina[1].", ".$psu[1] ?>],
          ['Wednesday', <?php echo $clink[2].", ".$kachina[2].", ".$psu[2] ?>],
          ['Thursday', <?php echo $clink[3].", ".$kachina[3].", ".$psu[3] ?>],
          ['Friday', <?php echo $clink[4].", ".$kachina[4].", ".$psu[4] ?>],
          ['Saturday', <?php echo $clink[5].", ".$kachina[5].", ".$psu[5] ?>]
        ]);

        var options = {
          <?php echo "title: 'Lab Usage for the week of ".date("m-d-Y",strtotime($date))."'," ?>
          hAxis: {title: 'Day of the Week'}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('weekly_chart'));
        chart.draw(data, options);
      }
</script>

<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Lab');
        data.addColumn('number', 'Sunday');
        data.addColumn('number', 'Monday');
        data.addColumn('number', 'Tuesday');
        data.addColumn('number', 'Wednesday');
        data.addColumn('number', 'Thursday');
        data.addColumn('number', 'Friday');
        data.addColumn('number', 'Saturday');
        data.addColumn('number', 'Weekly Total');
        data.addRows(3);
        data.setCell(0, 0, 'Campus Link');
        data.setCell(0, 1, <?php echo $clink[-1]; ?>);
        data.setCell(0, 2, <?php echo $clink[0]; ?>);
        data.setCell(0, 3, <?php echo $clink[1]; ?>);
        data.setCell(0, 4, <?php echo $clink[2]; ?>);
        data.setCell(0, 5, <?php echo $clink[3]; ?>);
        data.setCell(0, 6, <?php echo $clink[4]; ?>);
        data.setCell(0, 7, <?php echo $clink[5]; ?>);
        data.setCell(0, 8, <?php echo array_sum($clink); ?>);
        data.setCell(1, 0, 'Kachina');
        data.setCell(1, 1, <?php echo $kachina[-1]; ?>);
        data.setCell(1, 2, <?php echo $kachina[0]; ?>);
        data.setCell(1, 3, <?php echo $kachina[1]; ?>);
        data.setCell(1, 4, <?php echo $kachina[2]; ?>);
        data.setCell(1, 5, <?php echo $kachina[3]; ?>);
        data.setCell(1, 6, <?php echo $kachina[4]; ?>);
        data.setCell(1, 7, <?php echo $kachina[5]; ?>);
        data.setCell(1, 8, <?php echo array_sum($kachina); ?>);
        data.setCell(2, 0, 'PSU');
        data.setCell(2, 1, <?php echo $psu[-1]; ?>);
        data.setCell(2, 2, <?php echo $psu[0]; ?>);
        data.setCell(2, 3, <?php echo $psu[1]; ?>);
        data.setCell(2, 4, <?php echo $psu[2]; ?>);
        data.setCell(2, 5, <?php echo $psu[3]; ?>);
        data.setCell(2, 6, <?php echo $psu[4]; ?>);
        data.setCell(2, 7, <?php echo $psu[5]; ?>);
        data.setCell(2, 8, <?php echo array_sum($psu); ?>);

        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true});
      }
</script>
<div style="clear: both;"></div>
<div id="weekly_chart" style="width: 100%; height: 400px;"></div>
<div id='table_div'></div>
<?php
        require_once("footer.php");
?>