<?php
	$require_authorization = true;
	include("webauth/include.php");
	require_once("header.php");
	require_once("sidebar.php");

    mysql_connect("150.135.72.235", "labpc", "l@bpc!") or die(mysql_error());
    mysql_select_db("pgina") or die(mysql_error());

    $date=date('d-m-Y');
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
    }

    // Timestamp of the monday for that week
    $monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);

    $seconds_in_a_day = 86400;

    // Get date for 7 days from Monday (inclusive)
    for($i=0; $i<5; $i++)
    {
        $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
    }

$cellar_query = 'SELECT * FROM pgina.logs WHERE loginstamp >= CURRENT_DATE AND logoutstamp = 0 AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "yontaek" AND username NOT LIKE "macadmin"  AND machine LIKE "%CELL%" ORDER BY loginstamp DESC';
$kachina_query = 'SELECT * FROM pgina.logs WHERE loginstamp >= CURRENT_DATE AND logoutstamp = 0 AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "yontaek" AND username NOT LIKE "macadmin"  AND machine LIKE "%KACH%" ORDER BY loginstamp DESC';
$mac_query = 'SELECT * FROM pgina.logs WHERE loginstamp >= CURRENT_DATE AND logoutstamp = 0 AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "yontaek" AND username NOT LIKE "macadmin"  AND machine LIKE "UNION-m%" ORDER BY loginstamp DESC';
$psu_query = 'SELECT * FROM pgina.logs WHERE loginstamp >= CURRENT_DATE AND logoutstamp = 0 AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "yontaek" AND username NOT LIKE "macadmin" AND machine LIKE "%PSU%" ORDER BY loginstamp DESC';

$result = mysql_query($cellar_query) 
or die(mysql_error());
$cellar = mysql_num_rows($result);

$result = mysql_query($kachina_query) 
or die(mysql_error());
$kachina = mysql_num_rows($result);

$result = mysql_query($mac_query) 
or die(mysql_error());
$mac = mysql_num_rows($result);

$result = mysql_query($psu_query) 
or die(mysql_error());
$psu = mysql_num_rows($result);
?>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Day of the Week');
        data.addColumn('number', 'Cellar Lab');
        data.addColumn('number', 'Kachina Lab');
        data.addColumn('number', 'Mac Lab');
        data.addColumn('number', 'PSU Lab');
        data.addRows([
          ['Current Number of Users', <?php echo $cellar.", ".$kachina.", ".$mac.", ".$psu ?>],
        ]);

        var options = {
          <?php echo "title: 'Current Lab Usage'" ?>
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('current_chart'));
        chart.draw(data, options);
      }
</script>

<h1>Current Lab Usage Report</h1>
<div id="current_chart" style="width: 100%; height: 400px;"></div>
<?php
        require_once("footer.php");
?>