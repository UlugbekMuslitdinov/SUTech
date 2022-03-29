<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	session_start();
	$webauth_script_override = "/access/index.php";
	$require_authorization = true;
	//$_SESSION['webauth']['netID'] = "kmbeyer";
	//session_destroy();
	if($_SERVER['HTTPS']!="on")
	{
		$redirect= "https://pearl.sunion.arizona.edu".$_SERVER['REQUEST_URI'];
		header("Location:$redirect");
	}

	$deny = $require_authorization;
	$_SESSION["sucs_authorized"]=false;
	if ($_POST['action']=="logout"||strval($_GET["action"])=="logout") {
		session_destroy();
		header("Location: https://webauth.arizona.edu/webauth/logout?logout_href=https://pearl.sunion.arizona.edu/strap&logout_text=Return%20To%20Student%20Unions%20Computer%20Support");
	}
	else if ($_POST['action']=="login"||strval($_GET["action"])=="login") {
		include_once("webauth/include.php");
	}
	$script = $_SERVER["SCRIPT_NAME"];
	if (isset($webauth_script_override) && $webauth_script_override!="") {
		$script = $webauth_script_override;
	}
	if (isset($_SESSION['webauth']['netID']) && $require_authorization==true) {
		require_once("mysql/include.php");
		selectDB("sucs");
		$result = mysql_query('SELECT users.netID, users.user_id, COUNT( permission_id ) AS access_records
                FROM users, permissions, resources
                WHERE users.user_id = permissions.user_id
                AND permissions.resource_id = resources.resource_id
				AND resources.script = "'.$script.'"
            	AND users.netID = "'.$_SESSION['webauth']['netID'].'"')
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		$records = $result["access_records"];
		$result = mysql_query('SELECT users.netID, users.user_id
				FROM users
				WHERE users.netID = "'.$_SESSION['webauth']['netID'].'"')
		or die(mysql_error());
		$result = mysql_fetch_array($result);
		$user_id = $result["user_id"];
		$result = mysql_query('SELECT COUNT( permission_id ) AS access_records
                FROM memberships, permissions, resources
                WHERE memberships.user_id = '.intval($user_id).'
                AND memberships.group_id = permissions.group_id
                AND permissions.resource_id = resources.resource_id
				AND resources.script = "'.$script.'"')
			or die(mysql_error());
		$result = mysql_fetch_array($result);
		$records += $result["access_records"];
		if (isset($result) && $records>0) {
                        $_SESSION["sucs_authorized"]=true;
			$deny = false;
        }
	}
	// think this fixes not logged in but auth required
	else if (!isset($_SESSION['webauth']['netID']) && $require_authorization==true) {
		include_once("webauth/include.php");
	}
	
	
	
	if ($deny && $_SERVER["SCRIPT_NAME"]!="/denied.php") {
		echo 'Permission Denied.';
		die;
	}
	else {
	   /**
		* Script:    DataTables server-side script for PHP 5.2+ and MySQL 4.1+
		* Notes:     Based on a script by Allan Jardine that used the old PHP mysql_* functions.
		*            Rewritten to use the newer object oriented mysqli extension.
		* Copyright: 2010 - Allan Jardine (original script)
		*            2012 - Kari Söderholm, aka Haprog (updates)
		* License:   GPL v2 or BSD (3-point)
		*/
	   mb_internal_encoding('UTF-8');
		
	   /**
		* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
	   $aColumns = array( 'address', 'jack_id', 'note' );
		 
	   // Indexed column (used for fast and accurate table cardinality)
	   $sIndexColumn = 'address';
		 
	   // DB table to use
	   $sTable = 'sys__ip_address';
		 
		
	   // Input method (use $_GET, $_POST or $_REQUEST)
	   $input =& $_GET;
		
	   /** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		* If you just want to use the basic configuration for DataTables with PHP server-side, there is
		* no need to edit below this line
		*/
		
	   /**
		* Character set to use for the MySQL connection.
		* MySQL will return all strings in this charset to PHP (if the data is stored correctly in the database).
		*/
	   $gaSql['charset']  = 'utf8';
		
	   /**
		* MySQL connection
		*/
	   require_once("mysql/include.php");
	   $db = select_db("access");
		 
		 
	   /**
		* Paging
		*/
	   $sLimit = "";
	   if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
		   $sLimit = " LIMIT ".intval( $input['iDisplayStart'] ).", ".intval( $input['iDisplayLength'] );
	   }
		 
		 
	   /**
		* Ordering
		*/
	   $aOrderingRules = array();
	   if ( isset( $input['iSortCol_0'] ) ) {
		   $iSortingCols = intval( $input['iSortingCols'] );
		   for ( $i=0 ; $i<$iSortingCols ; $i++ ) {
			   if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
				   $aOrderingRules[] =
					   "`".$aColumns[ intval( $input['iSortCol_'.$i] ) ]."` "
					   .($input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc');
			   }
		   }
	   }
		
	   if (!empty($aOrderingRules)) {
		   $sOrder = " ORDER BY ".implode(", ", $aOrderingRules);
	   } else {
		   $sOrder = "";
	   }
		 
		
	   /**
		* Filtering
		* NOTE this does not match the built-in DataTables filtering which does it
		* word by word on any field. It's possible to do here, but concerned about efficiency
		* on very large tables, and MySQL's regex functionality is very limited
		*/
	   $iColumnCount = count($aColumns);
		
	   if ( isset($input['sSearch']) && $input['sSearch'] != "" ) {
		   $aFilteringRules = array();
		   for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
			   if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' ) {
				   $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$db->real_escape_string( $input['sSearch'] )."%'";
			   }
		   }
		   if (!empty($aFilteringRules)) {
			   $aFilteringRules = array('('.implode(" OR ", $aFilteringRules).')');
		   }
	   }
		 
	   // Individual column filtering
	   for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
		   if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
			   $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$db->real_escape_string($input['sSearch_'.$i])."%'";
		   }
	   }
		
	   if (!empty($aFilteringRules)) {
		   $sWhere = " WHERE ".implode(" AND ", $aFilteringRules);
	   } else {
		   $sWhere = "";
	   }
		 
		 
	   /**
		* SQL queries
		* Get data to display
		*/
	   $aQueryColumns = array();
	   foreach ($aColumns as $col) {
		   if ($col != ' ') {
			   $aQueryColumns[] = $col;
		   }
	   }
		
	   $sQuery = "
		   SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
		   FROM `".$sTable."`".$sWhere.$sOrder.$sLimit;
		
	   $rResult = $db->query( $sQuery ) or die($db->error);
		 
	   // Data set length after filtering
	   $sQuery = "SELECT FOUND_ROWS()";
	   $rResultFilterTotal = $db->query( $sQuery ) or die($db->error);
	   list($iFilteredTotal) = $rResultFilterTotal->fetch_row();
		
	   // Total data set length
	   $sQuery = "SELECT COUNT(`".$sIndexColumn."`) FROM `".$sTable."`";
	   $rResultTotal = $db->query( $sQuery ) or die($db->error);
	   list($iTotal) = $rResultTotal->fetch_row();
		 
		 
	   /**
		* Output
		*/
	   $output = array(
		   "sEcho"                => intval($input['sEcho']),
		   "iTotalRecords"        => $iTotal,
		   "iTotalDisplayRecords" => $iFilteredTotal,
		   "aaData"               => array(),
	   );
		 
	   while ( $aRow = $rResult->fetch_assoc() ) {
		   $row = array();
		   for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
			   if ( $aColumns[$i] == 'version' ) {
				   // Special output formatting for 'version' column
				   $row[] = ($aRow[ $aColumns[$i] ]=='0') ? '-' : $aRow[ $aColumns[$i] ];
			   } elseif ( $aColumns[$i] != ' ' ) {
				   // General output
				   $row[] = $aRow[ $aColumns[$i] ];
			   }
		   }
		   $output['aaData'][] = $row;
	   }
		 
	   echo json_encode( $output );
	}
?>