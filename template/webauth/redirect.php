<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
	if(isset($_GET['sid']))
		session_id($_GET['sid']);
	session_start();
//	$junkFile = fopen("/Library/WebServer/commontools/webauth/junk.txt", "w+");
//	fputs("******* Start of redirect file", $junkFile);
//	fclose($junkFile);

//	print_r($_SESSION);
//	print_r($_GET);
	
	//make sure we are returning from webauth with the ticket set otherwise go back to webauth
	if(!isset($_GET['ticket'])){
	    print("If 1");
		header("Location: https://webauth.arizona.edu/webauth/login?service=https://".$_SERVER['SERVER_NAME']."/template/webauth/redirect.php");
		exit();
		
	}
	//if we did get a tichet from webauth
	else if(isset($_GET['ticket'])){
		print("If 2");
		//create validation url using the ticket and this page as the service
		//$url = '"https://webauth.arizona.edu/webauth/serviceValidate?ticket='.$_GET['ticket'].'&service=https://'.$_SERVER['SERVER_NAME'].'/commontools/webauth/redirect.php"';
		$url = 'https://webauth.arizona.edu/webauth/serviceValidate?ticket='.$_GET['ticket'].'&service=https://'.$_SERVER['SERVER_NAME'].'/template/webauth/redirect.php';
		//echo $url;
		//print "<br><br>";
		
		//send validation request to webauth
		//exec("/opt/local/bin/curl --noproxy <*> -m 120 $url ", $return_message_array, $return_number);
		//exec("echo hello ", $return_message_array, $return_number);
/*
		print_r($return_message_array);
		print "<br><br>";
		print $return_number;
		print "<br><br>";
*/
		$curlHandle = curl_init();
		$options = array(
			CURLOPT_HEADER => false,
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FORBID_REUSE => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_TIMEOUT => 10
		);
		curl_setopt_array($curlHandle, $options);
		
		$temp = curl_exec($curlHandle);
		//var_dump(curl_error($curlHandle));

		//var_dump($return_message_array);
		//print "<br><br>";

		//turn the retured array into a string so that it can be parsed as xml
		//$temp = implode('', $return_message_array);

		//print $temp;
		//print "<br><br>";
		
		//create xml parser
		$xml_parser = xml_parser_create();
		
		//parse xml into an array of values and an array of indexes
		xml_parse_into_struct($xml_parser, $temp, $struct, $index);
		
		//var_dump($index);
		
		//check if ticket was authenticated successfully
		if(isset($index['CAS:AUTHENTICATIONSUCCESS'])){
			
			//save response to session
			$_SESSION['webauth']['netID'] = $struct[$index['CAS:USER'][0]]['value'];
			$_SESSION['webauth']['studentID'] = $struct[$index['CAS:STUDENTID'][0]]['value'];
			$_SESSION['webauth']['activeemployee'] = $struct[$index['CAS:ACTIVEEMPLOYEE'][0]]['value'];
			$_SESSION['webauth']['activestudent'] = $struct[$index['CAS:ACTIVESTUDENT'][0]]['value'];
			$_SESSION['webauth']['employeeID'] = $struct[$index['CAS:EMPLOYEEID'][0]]['value'];
			$_SESSION['webauth']['emplid'] = $struct[$index['CAS:EMPLID'][0]]['value'];
			$_SESSION['webauth']['ua_id'] = $struct[$index['CAS:DBKEY'][0]]['value'];
			
			//make sure this doesn't get used by another app that uses webauth			
			unset($_SESSION['webauth_splash']);
			
			//redirect back to page that was set in include.php
			header("Location:http://".$_SERVER['SERVER_NAME'].$_SESSION['webauth_service']);
			exit();
		}
		//if ticket authentication failed for some reason return user to webauth
		else{
			//echo "Here we are!";
			header("Location: https://webauth.arizona.edu/webauth/login?service=https://".$_SERVER['SERVER_NAME']."/template/webauth/redirect.php");
			exit();
		}
	
	}
?>


