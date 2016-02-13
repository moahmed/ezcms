<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * Version 4.160210
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Controller Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezController extends ezCMS {

	public $content = '';
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// get the contents of the controller file (index.php)
		$this->content = htmlspecialchars(file_get_contents("../index.php"));
		
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->update();
		}
		
		// Get the Message to display if any
		$this->getMessage();

	}
	
	// Function to Update the Controller
	private function update() {
	
		// Check permissions
		if (!$this->usr['editcont']) {
			header("Location: controllers.php?flg=noperms");
			exit;
		}
		
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}
		
		// Check if controller is writeable
		if (!is_writable('../index.php')) {
			header("Location: controllers.php?flg=unwriteable");
			exit;
		}
		
		// Fetch the contents
		$contents = $_POST["txtContents"];
		
		if (file_put_contents('../index.php', $contents )) {
			header("Location: controllers.php?flg=saved");
			exit;
		}
		
		header("Location: controllers.php?flg=failed");
		exit;
		
	}
	
	// Function to Set the Display Message
	private function getMessage() {
	
		// Set the HTML to display for this flag
		switch ($this->flg) {
			case "failed":
				$this->setMsgHTML('error','Save Failed !','An error occurred and the controller was NOT saved.');
				break;
			case "saved":
				$this->setMsgHTML('success','Controller Saved !','You have successfully saved the controller.');
				break;
			case "unwriteable":
				$this->setMsgHTML('error','Not Writeable !','The controller file is NOT writeable.');
				break;
			case "noperms":
				$this->setMsgHTML('info','Permission Denied !','You do not have permissions for this action.');
				break;
		}
		
	}
	
}
?>