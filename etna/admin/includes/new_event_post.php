<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('../login/config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
$race = clean($_POST['race']);
$date = clean($_POST['date']);
$date = date('Y-m-d',strtotime($date));
$website = clean($_POST['website']);
$discipline = clean($_POST['discipline']);

$quary = "ALTER TABLE obra AUTO_INCREMENT = 1";
$qry = "INSERT INTO gray8110_etna . obra(
			event,
			date,
			website,
			discipline)
			VALUES ('$race', '$date', '$website', '$discipline')";

	$fresult = @mysql_query($quary);
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: ../");
		exit();
	}else {
		die("Query failed");
	}
?>