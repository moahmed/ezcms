<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * script: logs in the user to the site
 * 
 */
session_start();
if (!isset($_SESSION['SESSION' ])) $_SESSION['SESSION' ] = true;
if (!isset($_SESSION['LOGGEDIN'])) $_SESSION['LOGGEDIN'] = false;
include      ('../../config.php');

// reset session variables...
$_SESSION['USERID'] = "";
$_SESSION['LOGGEDIN'] = false;
$_SESSION['EMAIL'] = "";

// initialize variables...
$userid = "";
$passwd = "";
$email = "";

// make sure post parameters were sent...
if (isset($_POST["userid"])) $userid = addslashes($_POST["userid"]);
if (isset($_POST["passwd"])) $passwd = addslashes($_POST["passwd"]);

// form variables must have something in them...
if ($userid == "" || $passwd == "" ) { header("Location: ../?flg=failed&userid=".$userid); exit; }

// check in database...
$query = "SELECT * FROM users WHERE email = '".$userid."';";
 
$result = mysql_query($query) or die("Invalid query: " . mysql_error());

// if userid is not present in DB go back to login page...
if (mysql_affected_rows() != 1) { header("Location: ../?flg=failed&userid=".$userid); exit; }

if ($row = mysql_fetch_assoc($result)) {
		
	if (strcmp($row['passwd'], ($passwd)) != 0) { header("Location: ../?flg=failed&userid=".$userid); exit; }
	
	if (!$row['active']) { header("Location: ../?flg=inactive&userid=".$userid); exit; }
		
	// set user details and user rights ka session variables...
	$_SESSION['USERID'] = $row['id'];
	$_SESSION['EMAIL'] = $row['email'];
	$_SESSION['LOGINNAME'] = $row['username'];
	$_SESSION['viewstats'] = $row['viewstats'];	
	$_SESSION['edituser'] = $row['edituser'];
	$_SESSION['deluser'] = $row['deluser'];
	$_SESSION['editpage'] = $row['editpage'];
	$_SESSION['delpage'] = $row['delpage'];
	$_SESSION['editsettings'] = $row['editsettings'];
	$_SESSION['editcontroller'] = $row['editcont'];
	$_SESSION['editlayout'] = $row['editlayout'];
	$_SESSION['editcss'] = $row['editcss'];
	$_SESSION['editjs'] = $row['editjs'];
	
	// check editor and use it
	$_SESSION['EDITORTYPE']=0;
	if (isset($_POST['sleditorstyle'])) {
		if ($_POST['sleditorstyle'] == '1')$_SESSION['EDITORTYPE']=1;
		if ($_POST['sleditorstyle'] == '2')$_SESSION['EDITORTYPE']=2; 
	}
	
	// update the last login date time stamp.
	
	
	$_SESSION['LOGGEDIN']  = true;
	header("Location: ../pages.php");
}
header("Location: ../?flg=failed&userid=".$userid);
exit;		
?>