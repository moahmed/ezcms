<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * script: logs in the user to the cms
 *
 */

// Start SESSION if not started 
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start(); 
}

// Set SESSION ADMIN Login Flag to false if not set
if (!isset($_SESSION['LOGGEDIN'])) {
	$_SESSION['LOGGEDIN'] = false;
}

// Make sure post parameters were sent...
if ( (!isset($_POST["userid"])) || (!isset($_POST["passwd"])) ) {
	header('HTTP/1.1 400 BAD REQUEST');
	die('Invalid Request');
}

// Get the POST data
$userid = $_POST["userid"];
$passwd = $_POST["passwd"];

// Form variables must have something in them...
if ( (empty($userid)) || (empty($passwd)) ) { 
	header("Location: ../?flg=failed&userid=$userid"); 
	exit; 
}

// **************** DATABASE ****************
require_once ("../../config.php"); // PDO Class for database access
$dbh = new db; // database handle

// Prepare SQL to fetch user's record from dataabse
$stmt = $dbh->prepare('SELECT * FROM `users` WHERE `email` = ? AND `passwd` = SHA2( ? , 512 ) LIMIT 1');
$stmt->execute( array($userid, $passwd) );

// Check if User Record is present and returned from the database
if ($stmt->rowCount()) { 

	// Fetch the user record from database
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	// User must be ACTIVE to login
	if (!$user['active']) { 
		header("Location: ../?flg=inactive&userid=$userid"); 
		exit; 
	}
	
	//login the user
	$_SESSION['LOGGEDIN']   = true;  // login flag to TRUE
	$_SESSION['USERID']     = $user['id']; // User's ID
	
	// Redirect to logged in page
	header("Location: ../pages.php");	
	exit;
	
}

// Login failed
header("Location: ../?flg=failed&userid=".$userid);
exit;

?>
