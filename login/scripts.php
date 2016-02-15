<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * Version 4.160210
 * HMI Technologies Mumbai
 *
 * View: Displays the javascripts files in the CMS
 *
 */

// **************** ezCMS SCRIPTS CLASS ****************
require_once ("class/scripts.class.php"); 

// **************** ezCMS SCRIPTS HANDLE ****************
$cms = new ezScripts();

?><!DOCTYPE html><html lang="en"><head>

	<title>Scripts &middot; ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

	<div id="wrap">
		<?php include('include/nav.php'); ?>
		<div class="container">
			<div class="container-fluid">
			  <div class="row-fluid">
				<div class="span3 white-boxed">

					<ul id="left-tree">
					  <li class="open" ><i class="icon-align-left icon-white"></i>
						<a class="<?php echo $cms->homeclass; ?>" href="scripts.php">main.js</a>
					  	<?php echo $cms->treehtml; ?>
					  </li>
					</ul>

				</div>
				<div class="span9 white-boxed">
				  <form id="frm" action="scripts.php" method="post" enctype="multipart/form-data">
					<div class="navbar">
						<div class="navbar-inner">
						
							<div class="btn-group">
							  <a class="btn dropdown-toggle btn-inverse" data-toggle="dropdown" href="#"> New <span class="caret"></span></a>
								
							  <div class="dropdown-menu subddmenu">
								<blockquote>
								  <p>New Javascript name</p>
								  <small>Only Alphabets and Numbers, no spaces</small>
								</blockquote>
								<div class="input-append">
								  <input id="txtNew" name="txtSaveAs" type="text" class="appendedInput">
								  <span class="add-on">.js</span>
								</div><br>
								<p><a id="btnnew" href="#" class="btn btn-large btn-info">Create New</a></p>
							  </div>
							  
							</div>						
						
							<input type="submit" name="Submit" id="Submit" value="Save" class="btn btn-inverse"> 
							<div class="btn-group">
							  <a class="btn dropdown-toggle btn-inverse" data-toggle="dropdown" href="#">
								Save As <span class="caret"></span></a>

							  <div id="SaveAsDDM" class="dropdown-menu" style="padding:10px;">
								<blockquote>
								  <div>Save Javascript file as</div>
								  <small>Only Alphabets and Numbers, no spaces</small>
								</blockquote>
								<div class="input-append">
								  <input id="txtSaveAs" name="txtSaveAs" type="text" class="appendedInput">
								  <span class="add-on">.js</span>
								</div><br>
								<p><a id="btnsaveas" href="#" class="btn btn-large btn-info">Save Now</a></p>
							  </div>

							</div>
							<?php echo $cms->deletebtn; ?>
							
						</div>
					</div>
					<?php echo $cms->msg; ?>
					<input class="input-block-level" onFocus="this.select();"
						style="cursor: pointer;" onClick="this.select();"  type="text" title="include this link in layouts or page head"
						value="&lt;script src=&quot;<?php echo substr($cms->filename, 2); ?>&quot; type=&quot;text/javascript&quot;&gt;&lt;/script&gt;" readonly/>
					<input type="hidden" name="txtName" id="txtName" value="<?php echo $cms->filename; ?>">
					<textarea name="txtContents" id="txtContents" class="input-block-level"><?php echo $cms->content; ?></textarea>
				  </form>
				</div>
			  </div>
			</div>
		</div>
	</div>

<?php include('include/footer.php'); ?>

<script src="codemirror/lib/codemirror.js"></script>
<script src="codemirror/mode/javascript/javascript.js"></script>
<script src="codemirror/addon/fold/foldcode.js"></script>
<script src="codemirror/addon/fold/foldgutter.js"></script>

<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(5)").addClass('active');

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
		$('#txtName').val('../site-assets/js/'+saveasfile+'.js');
		$('#Submit').click();
		return false;
	});

</script>
</body></html>
