<?php
	$webauth_script_override = "/access/admin.php";
	$require_authorization = true;
	include_once('header2.php');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	require_once("mysql/include.php");

	$db_link = select_db("access");
	
	$message = '<html><body style="font-family: courier, arial, sans-serif;font-size: 13px;">
	<br /><h3 style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;padding-top:5px;padding-bottom:6px;margin-top:0px;margin-bottom:10px;">Kronos Import -> Access Users Sync Summary</h3>';
	$users_skipped = '<br /><hr /><b>Skipped Users:</b><div style="margin-left: 30px;">';
?>

<ol class="breadcrumb">
  <li><a href="/strap">Home</a></li>
  <li><a href="/access">Access</a></li>
  <li class="active">Sync Kronos Import to Access DB</li>
</ol>
<h1>Sync Kronos Import to Access DB</h1>
<?php

	$total_added = 0;
	$total_skipped = 0;
	$total_deactivated = 0;
	$total_updated = 0;
	
	$query = 'SELECT * FROM import__kronos';
	$result = $db_link->query($query);
	$total_rows = mysqli_num_rows($result);
	
	$username = 'studentunion-portal';
	$password = 'SC2BsxHqLG9WWh2yzqksnrSP76FhKJdD';
	
	$message .= '<b>Deactivated Users:</b><div style="margin-left: 30px;">';
	$query = 'SELECT user.emplid, user.netid, user.alias FROM user LEFT JOIN import__kronos ON import__kronos.emplid = user.emplid WHERE import__kronos.emplid IS NULL AND user.kronos=TRUE AND user.active=TRUE';
	$result = $db_link->query($query);
	while($row = $result->fetch_array()) {
		echo '<div class="alert alert-info">Deactivated employee: '.$row['emplid'].' > '.$row['netid'].'</div>';
		$query2 = 'UPDATE user SET active=FALSE WHERE user.emplid="'.$row['emplid'].'"';
		$result2 = $db_link->query($query2);
		$message .= $row['alias'].' - '.$row['netid'].'<br />';
		$total_deactivated++;
	}
	$message .= '</div>';
	
	$message .= '<br /><hr /><b>Added Users:</b><div style="margin-left: 30px;">';
	$query = 'SELECT import__kronos.emplid FROM user RIGHT JOIN import__kronos ON import__kronos.emplid = user.emplid WHERE user.emplid IS NULL';
	$result = $db_link->query($query);
	while($row = $result->fetch_array()) {
		$url = 'https://eds.arizona.edu/people/'.$row['emplid'];
		$cred = sprintf('Authorization: Basic %s', base64_encode($username.':'.$password));
		$opts = array( 'http' => array ('method'=>'GET', 'header'=>$cred));
		$ctx = stream_context_create($opts);

		// send our request and retrieve the DSML response
		$dsml = file_get_contents($url,false,$ctx);

		// create SimpleXMLElement from response
		$xml = new SimpleXMLElement($dsml);

		$xml->registerXPathNamespace('dsml', 'http://www.dsml.org/DSML');

		$query = "//dsml:entry/dsml:attr[@name='uid']/dsml:value";
		$vals = $xml->xpath($query);
		foreach($vals as $node) {
			$cur_netid = strval($node);
		}
		$query = "//dsml:entry/dsml:attr[@name='cn']/dsml:value";
		$vals = $xml->xpath($query);
		foreach($vals as $node) {
			$cur_cn = $node;
		}
		if (!$cur_netid) {
			echo '<div class="alert alert-warning">Skipped employee: '.$row['emplid'].' > '.$cur_cn.' > EDS found no UID (NetID)</div>';
			$users_skipped .= $cur_cn.' - '.$row['emplid'].' - EDS found no UID (NetID) <br />';
			$total_skipped++;
		}
		else {
			echo '<div class="alert alert-success">Added employee: '.$row['emplid'].' > '.$cur_cn.' - '.$cur_netid.'</div>';
			$query2 = 'INSERT INTO user (emplid, netid, name) VALUES ("'.$row['emplid'].'", "'.$cur_netid.'", "'.$cur_cn.'")';
			$result2 = $db_link->query($query2);
			$message .= $cur_cn.' - '.$cur_netid.'<br />';
			$total_added++;
		}
		unset($cur_netid);
	}
	$message .= '</div>';
	
	$message .= '<br /><hr /><b>Updated Users:</b><div style="margin-left: 30px;">';
	$query = 'SELECT emplid FROM user WHERE name IS NULL AND kronos = TRUE';
	$result = $db_link->query($query);
	while($row = $result->fetch_array()) {
		$url = 'https://eds.arizona.edu/people/'.$row['emplid'];
		$cred = sprintf('Authorization: Basic %s', base64_encode($username.':'.$password));
		$opts = array( 'http' => array ('method'=>'GET', 'header'=>$cred));
		$ctx = stream_context_create($opts);

		// send our request and retrieve the DSML response
		$dsml = file_get_contents($url,false,$ctx);

		// create SimpleXMLElement from response
		$xml = new SimpleXMLElement($dsml);

		$xml->registerXPathNamespace('dsml', 'http://www.dsml.org/DSML');

		$query = "//dsml:entry/dsml:attr[@name='uid']/dsml:value";
		$vals = $xml->xpath($query);
		foreach($vals as $node) {
			$cur_netid = strval($node);
		}
		$query = "//dsml:entry/dsml:attr[@name='cn']/dsml:value";
		$vals = $xml->xpath($query);
		foreach($vals as $node) {
			$cur_cn = strval($node);
		}
		if (!$cur_cn) {
			echo '<div class="alert alert-success">Skipped Employee Name: '.$row['emplid'].' > '.$cur_cn.' - '.$cur_netid.'</div>';
		}
		else {
			$query2 = 'UPDATE user SET name="'.$cur_cn.'" WHERE user.emplid="'.$row['emplid'].'"';
			echo '<div class="alert alert-success">Updated Employee Name: '.$row['emplid'].' > '.$cur_cn.' - '.$cur_netid.'</div>';
			$message .= $cur_cn.' - '.$cur_netid.'<br />';
			//var_dump($query2);
			$result2 = $db_link->query($query2);
		}
		unset($cur_netid);
	}
	$message .= '</div>';
	
	$total_changed = $total_added + $total_deactivated + $total_updated;
?>
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Sync Summary</div>
  <div class="panel-body">
    <b>Users Added</b>: <?=$total_added;?><br />
	<b>Users Deactivated</b>: <?=$total_deactivated;?><br />
	<b>Users Updated</b>: <?=$total_updated;?><br />
	<b>Total User Rows Changed</b>: <?=$total_changed;?><br />
	<hr />
	<b>Total Rows in Import Table</b>: <?=$total_rows;?><br />
	<b>Rows Skipped</b>: <?=$total_skipped;?><br />
  </div>
</div>
<br />
<a href="/access"><button type="button" class="btn btn-primary">Access Home</button></a>
<?php
	
	$message .= $users_skipped.'</div></body></html>';
	$to = "yontaek@email.arizona.edu,ghorner@email.arizona.edu,dianelc@email.arizona.edu";
    $subject = "Kronos Import -> Access Users Sync Summary";
    $headers = 'From: TECH Web Mailer <su-tech@email.arizona.edu>' . "\r\n" .
       'Reply-To: TECH Web Mailer <no-reply@pearl.sunion.arizona.edu>' . "\r\n" .
       'X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$email = mail($to, $subject, $message, $headers);

	require_once("footer2.php");
?>
