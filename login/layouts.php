<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * View: Displays the layouts in the site
 * 
 */
 
// **************** ezCMS CLASS ****************
require_once ("class/ezcms.class.php"); // CMS Class for database access
$cms = new ezCMS(); // create new instance of CMS Class with loginRequired = true

if (isset($_GET['show'])) $filename = $_GET['show']; else $filename = "layout.php";
$content = @fread(fopen('../'.$filename, "r"), filesize('../'.$filename));
$content =  htmlspecialchars($content);
if (isset($_GET["flg"])) $flg = $_GET["flg"]; else $flg = "";
$msg = "";
if ($flg=="red") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> An error occurred and the layout was NOT saved.</div>';
if ($flg=="green")
	$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Saved!</strong> You have successfully saved the layout.</div>';
if ($flg=="pink") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Failed!</strong> The layout file is NOT writeable.
				You must contact HMI Tech Support to resolve this issue.</div>';
if ($flg=="delfailed") 
	$msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Delete Failed!</strong> An error occurred and the layout was NOT deleted.</div>';
if ($flg=="deleted")
	$msg = '<div class="alert"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Deleted!</strong> You have successfully deleted the layout.</div>';
if ($flg=="noperms") 
	$msg = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Permission Denied!</strong> You do not have permissions for this action.</div>';				
?><!DOCTYPE html><html lang="en"><head>

	<title>Layouts &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	
</head><body>
  
	<div id="wrap">
		<?php include('include/nav.php'); ?>  
		<div class="container">
			<div class="container-fluid">
			  <div class="row-fluid">
				<div class="span3 white-boxed">
				
					<ul id="left-tree">
					  <li class="open" ><i class="icon-list-alt icon-white"></i> 
					  	<a class="<?php if ($filename=="layout.php") echo 'label label-info'; ?>" href="layouts.php">layout.php</a>
					  	<ul>
							<?php if ($handle = opendir('..')) {
									while (false !== ($entry = readdir($handle))) {
										if (preg_match('/^layout\.[a-z0-9_-]+\.php$/i',$entry)) {
											if ($filename==$entry) $myclass = 'label label-info'; else $myclass = '';
											echo '<li><i class="icon-list-alt icon-white"></i> <a href="layouts.php?show='.
												$entry.'" class="'.$myclass.'">'.$entry.'</a></li>';
										}
									}
									closedir($handle);
								}?>
						</ul>
					  </li>					
					</ul>
					
				</div>
				<div class="span9 white-boxed">
					<form id="frmlayout" action="scripts/set-layouts.php" method="post" enctype="multipart/form-data">
						<div class="navbar">
							<div class="navbar-inner">
								<input type="submit" name="Submit" id="Submit" value="Save Changes" class="btn btn-inverse" style="padding:5px 12px;"> 
								<div class="btn-group">
								  <a class="btn dropdown-toggle btn-inverse" data-toggle="dropdown" href="#">
									Save As <span class="caret"></span></a>
									
								  <div id="SaveAsDDM" class="dropdown-menu" style="padding:10px;">
									<blockquote>
									  <p>Save layout as</p>
									  <small>Only Alphabets and Numbers, no spaces</small>
									</blockquote>
									<div class="input-prepend input-append">
									  <span class="add-on">layout.</span>
									  <input id="txtSaveAs" name="txtSaveAs" type="text" class="input-medium appendedPrependedInput">
									  <span class="add-on">.php</span>
									</div><br>
									<p><a id="btnsaveas" href="#" class="btn btn-large btn-info">Save Now</a></p>
								  </div>
								  
								</div>
								<?php if ($filename!='layout.php') 
									echo '<a href="scripts/del-layouts.php?delfile='.
										$filename.'" onclick="return confirm(\'Confirm Delete ?\');" class="btn btn-danger">Delete</a>'; ?>
							</div>
						</div>
						<?php echo $msg; ?>
						<input type="hidden" name="txtName" id="txtName" value="<?php echo $filename; ?>">
						<textarea name="txtContents" id="txtContents" class="input-block-level"
							style="height: 460px; width:100%"><?php echo $content; ?></textarea>
					</form>
				</div>
				
			  </div>
			</div>
		</div> 
	</div>

<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(3)").addClass('active');
	$('#SaveAsDDM').click(function (e) {
		e.stopPropagation();
	});	
	$('#btnsaveas').click( function () {
		var saveasfile = $('#txtSaveAs').val().trim();
		if (saveasfile.length < 1) {
			alert('Enter a valid filename to save as.');
			$('#txtSaveAs').focus();
			return false;
		}
		if (!saveasfile.match(/^[a-z0-9]+$/ig)) {
			alert('Enter a valid filename with lower case alphabets and numbers only.');
			$('#txtSaveAs').focus();
			return false;		
		}
		$('#txtName').val('layout.'+saveasfile+'.php');		
		$('#Submit').click();
		return false;
	});
			
</script>
</body></html>
