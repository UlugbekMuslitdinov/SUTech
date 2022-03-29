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
    
    $machines = array(
        "SU-LAB-KACH-01" => 0,
        "SU-LAB-KACH-02" => 0,
        "SU-LAB-KACH-03" => 0,
        "SU-LAB-KACH-04" => 0,
        "SU-LAB-KACH-05" => 0,
        "SU-LAB-KACH-06" => 0,
        "SU-LAB-KACH-07" => 0,
        "SU-LAB-KACH-08" => 0,
        "SU-LAB-PSU-01" => 0,
        "SU-LAB-PSU-02" => 0,
        "SU-LAB-PSU-03" => 0,
        "SU-LAB-PSU-04" => 0,
        "UNION-m01" => 0,
        "UNION-m02" => 0,
        "UNION-m03" => 0,
        "UNION-m04" => 0
    );

    $query = 'SELECT machine, count(machine) AS logins FROM pgina.logs WHERE loginstamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND username NOT LIKE "billybob" AND username NOT LIKE "administrator" AND username NOT LIKE "macadmin" AND username NOT LIKE "yontaek" AND username NOT LIKE "ghorner" AND machine NOT LIKE "%CELL%" GROUP BY machine ORDER BY machine ASC';
    
    $result = mysql_query($query) 
    or die(mysql_error());

    while ($cur = mysql_fetch_assoc($result)) {
        $machines[$cur["machine"]] = intval($cur["logins"]);
    }
?>

<h1>Lab Workstation Usage Report</h1>
<div style="margin-bottom: 10px; margin-top: 20px; margin-left: 2px;">
    <?php
        $num_short = (40-mysql_num_rows($result));
        if ($num_short > 0) {
            echo '<span style="font-weight:bold;color:#C00;">';
        }
        else {
            echo '<span style="font-weight:bold;color:#0C0;">';
        }
        echo 'Machines without logins: '.$num_short.'</span>';
        if ($num_short > 0) {
            echo '&nbsp;&nbsp;&nbsp;<span style="font-style: italic; color: gray;">(Machines without logins highlighted bellow)</span>';
        }
    ?>
</div>
<table>
    <tr>
        <td class="column_header" width="200px">Machine Name</td>
        <td class="column_header"># of Logins in past 7 days</td>
    </tr>
<?php
    foreach ($machines as $cur => $cur_count) {
        if ($cur_count == 0) {
            echo '<tr>
                <td style="background-color: #FFBABA; color: #D8000C;">'.$cur.'</td>
                <td style="background-color: #FFBABA; color: #D8000C;">'.$cur_count.'</td>
            </tr>';
            
        }
        else {
            echo '<tr>
                <td>'.$cur.'</td>
                <td>'.$cur_count.'</td>
            </tr>';
        }
    }
?>
</table>
<?php
        require_once("footer.php");
?>