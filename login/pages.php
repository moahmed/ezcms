<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * Version 4.160210
 * HMI Technologies Mumbai
 *
 * View: Displays the web pages in the CMS
 *


if (isset( $_REQUEST["id"])) $id = $_REQUEST["id"]; else $id = 1;
$usefooter      = '';
$useheader      = '';
$useside        = '';
$usesider       = '';
$isredirected   = '';
$showinsmenu    = '';
$published      = '';
$parentid       = '';
$slLayout		= '';
$showinmenu     = '';
$name           = '';
$title          = '';
$keywords       = '';
$redirect       = '';
$description 	= '';
$maincontent 	= '';
$sidebar    	= '';
$siderbar 		= '';
$header			= '';
$footer 		= '';
$head 			= '';
$cont 			= '';
$url        	= '';

// check if form is posted
if (isset($_REQUEST['Submit'])) {

	if (!$_SESSION['editpage']) {header("Location: pages.php?id=$id&flg=noperms");exit;}	// permission denied

	$title       	= mysql_real_escape_string($_REQUEST['txtTitle']);
	$name      	    = validateName($_REQUEST['txtName']);
	$keywords    	= mysql_real_escape_string($_REQUEST['txtKeywords']);
	$description 	= mysql_real_escape_string($_REQUEST['txtDesc']);
	$maincontent 	= mysql_real_escape_string($_REQUEST['txtMain']);
	$sidebar 		= mysql_real_escape_string($_REQUEST['txtSide']);
	$siderbar 		= mysql_real_escape_string($_REQUEST['txtrSide']);
	$header 		= mysql_real_escape_string($_REQUEST['txtHeader']);
	$footer 		= mysql_real_escape_string($_REQUEST['txtFooter']);
	$head 			= mysql_real_escape_string($_REQUEST['txtHead']);
	$cont 			= '';

	$redirect 	    =  ''; //($_REQUEST['txtRedirect']);
	if (isset($_REQUEST['slGroup'])) $parentid = ($_REQUEST['slGroup']); else $parentid = '0';
	$slLayout		= ($_REQUEST['slLayout']);
	if(isset($_REQUEST['ckside'     ])) $useside       =1; else $useside      = '';
	if(isset($_REQUEST['cksider'    ])) $usesider      =1; else $usesider     = '';
	if(isset($_REQUEST['ckHeader'   ])) $useheader     =1; else $useheader    = '';
	if(isset($_REQUEST['ckFooter'   ])) $usefooter     =1; else $usefooter    = '';

	if (strlen(trim($_REQUEST['txtName'])) < 1 ) {
		$_GET["flg"] = 'noname';
		include("include/set-page-vars.php");
	} elseif (strlen(trim($_REQUEST['txtTitle'])) < 1 ) {
		$_GET["flg"] = 'notitle';
		include("include/set-page-vars.php");
	} elseif ($parentid == $id && $id > 1 ) {
		$_GET["flg"] = 'nestedparent';
		include("include/set-page-vars.php");
	} else {
		if ($id == 'new') {
			// add new page here !
			$qry  = '';
			$qry .= "INSERT INTO `pages` ( ";
			$qry .= "`id` , `pagename` , `title`, ";
			$qry .= "`keywords` , `description` , `maincontent` , ";
			$qry .= "`useheader` , `headercontent` , `head`, `cont` , `layout` , ";
			$qry .= "`usefooter` , `footercontent` ,`useside` , `sidecontent` , `usesider` , `sidercontent` ,";
			$qry .= "`published` , `showinmenu` , `showinsubmenu` , `parentid` , `isredirected` , `redirect`) ";
			$qry .= "VALUES ( NULL , ";
			$qry .= "'" . $name          . "', ";
			$qry .= "'" . $title         . "', ";
			$qry .= "'" . $keywords      . "', ";
			$qry .= "'" . $description   . "', ";
			$qry .= "'" . $maincontent   . "', ";
			$qry .= "'" . $useheader     . "', ";
			$qry .= "'" . $header 		 . "', ";
			$qry .= "'" . $head			 . "', ";
			$qry .= "'" . $cont			 . "', ";
			$qry .= "'" . $slLayout		 . "', ";
			$qry .= "'" . $usefooter     . "', ";
			$qry .= "'" . $footer 		 . "', ";
			$qry .= "'" . $useside       . "', ";
			$qry .= "'" . $sidebar   	 . "', ";
			$qry .= "'" . $usesider      . "', ";
			$qry .= "'" . $siderbar  	 . "', ";
			$qry .= "'" . $published     . "', ";
			$qry .= "'" . $showinmenu    . "', ";
			$qry .= "'" . $showinsmenu    . "', ";
			$qry .= "'" . $parentid      . "', ";
			$qry .= "'" . $isredirected  . "', ";
			$qry .= "'" . $redirect      . "');";
			if (mysql_query($qry)) {
				$id = mysql_insert_id();
				resolveplace();
				reIndexPages();
				mysql_query('OPTIMIZE TABLE `pages`;');
				header("Location: pages.php?id=".$id."&flg=added");	// added
				exit;
			} else {
				$_GET["flg"] = 'pink';
				include("include/set-page-vars.php");
			}

		} else {
			// update page here !

			$qry  = '';
			$qry .= "UPDATE `pages` SET ";
			$qry .= "`pagename`      = '" . $name          . "', ";
			$qry .= "`title`         = '" . $title         . "', ";
			$qry .= "`keywords`      = '" . $keywords      . "', ";
			$qry .= "`description`   = '" . $description   . "', ";
			$qry .= "`maincontent`   = '" . $maincontent   . "', ";
			$qry .= "`useheader`     = '" . $useheader     . "', ";
			$qry .= "`headercontent` = '" . $header 	   . "', ";
			$qry .= "`head`          = '" . $head          . "', ";
			$qry .= "`cont`          = '" . $cont          . "', ";
			$qry .= "`usefooter`     = '" . $usefooter     . "', ";
			$qry .= "`footercontent` = '" . $footer 	   . "', ";
			$qry .= "`useside`       = '" . $useside       . "', ";
			$qry .= "`sidecontent`   = '" . $sidebar   	   . "', ";
			$qry .= "`usesider`      = '" . $usesider      . "', ";
			$qry .= "`sidercontent`  = '" . $siderbar  	   . "', ";
			$qry .= "`published`     = '" . $published     . "', ";
			$qry .= "`showinmenu`    = '" . $showinmenu    . "', ";
			$qry .= "`showinsubmenu` = '" . $showinsmenu   . "', ";
			$qry .= "`parentid`      = '" . $parentid      . "', ";
			$qry .= "`layout`        = '" . $slLayout      . "', ";
			$qry .= "`isredirected`  = '" . $isredirected  . "', ";
			$qry .= "`redirect`      = '" . $redirect      . "'  ";
			$qry .= "WHERE `id` =" . $id . " LIMIT 1 ;";
			//echo $qry; exit;
			if (mysql_query($qry)) {
				reIndexPages();
				mysql_query('OPTIMIZE TABLE `pages`;');
				header("Location: pages.php?id=".$id."&flg=green");	// updated
			} else
				header("Location: pages.php?id=".$id."&flg=red");	// failed
			exit;
		}
	}
} else if ($id <> 'new')  {

	//$qry = "SELECT * FROM `pages` WHERE `id` = " . $id;
	//$rs = mysql_query($qry);

} else {
	if (!$_SESSION['editpage']) {header("Location: pages.php?flg=noperms");exit;}	// permission denied
}
if (isset($_GET["flg"])) $msg = getErrorMsg($_GET["flg"]); else $msg = ""; 
 
 
 */

// **************** ezCMS PAGES CLASS ****************
require_once ("class/pages.class.php"); 

// **************** ezCMS PAGES HANDLE ****************
$cms = new ezPages(); 

?><!DOCTYPE html><html lang="en"><head>

	<title>Pages &middot; ezCMS Admin</title>
	<style>
		.countDisplay {float: right;}
	</style>
	<?php include('include/head.php'); ?>

</head><body>

	<div id="wrap">
		<?php include('include/nav.php'); ?>
		<div class="container">
			<div class="container-fluid">
			  <div class="row-fluid">
				<div class="span3 white-boxed">
					<p><input type="text" id="txtsearch" class="input-block-level" placeholder="Search here ..."></p>
					<?php echo $cms->treehtml; ?>
				</div>
				<div class="span9 white-boxed">

					<form id="frmPage" action="" method="post" enctype="multipart/form-data">
					<div class="navbar">
						<div class="navbar-inner">
							<input type="submit" name="Submit" class="btn btn-inverse"
								value="<?php if ($cms->id == 'new') echo 'Add Page'; else echo 'Save Changes';?>">
							  <?php if ($cms->id != 'new') { ?>
								<a href="<?php echo $cms->page['url']; ?>" target="_blank"
									<?php if ($cms->page['published']!='checked') echo 'onclick="return confirm(\'The page is Not published, its only visible to you.\');"'; ?>
									class="btn btn-inverse">View</a>
								<a href="pages.php?id=new" class="btn btn-inverse">New</a>
								<a href="scripts/copy-page.php?copyid=<?php echo $cms->id; ?>" class="btn btn-inverse">Copy</a>
								<?php if ($cms->id != 1 && $cms->id != 2) echo '<a href="scripts/del-page.php?delid='.$cms->id.
										'" onclick="return confirm(\'Confirm Delete ?\');" class="btn btn-danger">Delete</a>'; ?>
								<div class="btn-group">
									<button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></button>
									<ul class="dropdown-menu">
									  <li class="nav-header">Validate</li>
									  <li><a class="lframe" target="_blank" title="Validate the Page HTML" href=
									  	"http://validator.w3.org/check?uri=http%3A%2F%2F<?php
										echo $_SERVER['HTTP_HOST'] . $cms->page['url'];
										?>&charset=%28detect+automatically%29&fbc=1&doctype=Inline&fbd=1&group=0&verbose=1">
										<i class="icon-chevron-right"></i> HTML W3C</a></li>
									  <li><a class="lframe" target="_blank" title="Validate the Page CSS" href=
									  	"http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2F<?php
										echo $_SERVER['HTTP_HOST'] . $cms->page['url'];
										?>&profile=css21&usermedium=all&warning=1&vextwarning=&lang=en">
										<i class="icon-chevron-right"></i> CSS W3C</a></li>
									  <li class="divider"></li>
									  <li class="nav-header">Check</li>
									  <li><a class="lframe" target="_blank" title="Check the Page for broken links" href=
									  	"http://validator.w3.org/checklink?uri=http%3A%2F%2F<?php
										echo $_SERVER['HTTP_HOST'] . $cms->page['url']; ?>&hide_type=all&depth=1&check=Check">
										<i class="icon-chevron-right"></i> Broken Links</a></li>
 									  <li><a class="lframe" target="_blank" title="Check the Page keyword density" href=
									  	"http://www.webconfs.com/keyword-density-checker.php?url=http%3A%2F%2F<?php
										echo $_SERVER['HTTP_HOST'] . $cms->page['url']; ?>">
										<i class="icon-chevron-right"></i> Keyword Density</a></li>
									</ul>
								</div>
							  <?php } ?>
						</div>
					</div>

					<?php echo $cms->msg; ?>

				    <div class="tabbable tabs-top">
					<ul class="nav nav-tabs" id="myTab">
					  <li class="active"><a href="#d-main">Main</a></li>
					  <li><a href="#d-content">Content</a></li>
					  <li><a href="#d-header">Header</a></li>
					  <li><a href="#d-sidebar">Aside 1</a></li>
					  <li><a href="#d-siderbar">Aside 2</a></li>
					  <li><a href="#d-footer">Footer</a></li>
					  <li><a href="#d-head">Head</a></li>
					</ul>

					<div class="tab-content">
					  <div class="tab-pane active" id="d-main">

						<div class="row">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputTitle">Title Tag</label>
								<div class="controls">
									<input type="text" id="txtTitle" name="txtTitle"
										placeholder="Enter the title of the page"
										title="Enter the full title of the page here."
										data-toggle="tooltip"
										value="<?php echo $cms->page['title']; ?>"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><br>
										<label class="checkbox" <?php if ($cms->id == 1 || $cms->id == 2) echo 'style="display:none"';?>>
										  <input name="ckPublished" type="checkbox" id="ckPublished" value="checkbox" <?php echo $cms->page['publishedCheck']; ?>>
										  Published on site
										</label>
								</div>
							  </div>
							</div>
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputName">Name (URL)</label>
								<div class="controls">
									<input type="text" id="txtName" name="txtName"
										placeholder="Enter the name of the page"
										title="Enter the full name of the page here."
										data-toggle="tooltip"
										value="<?php echo $cms->page['pagename']; ?>"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><br>
									<?php echo $cms->page['publishedMsg']; ?>

								</div>
							  </div>
							</div>
						</div>

						<div class="row" style="margin-left:0">
							<div class="span6">

							  <div class="control-group">
								<label class="control-label" for="inputName">Parent Page</label>
								<div class="controls">
								  <?php if ($cms->id == 1 || $cms->id == 2) echo
								  			'<div class="alert alert-info"><strong>Site Root</strong></div>';
										else echo
											'<select name="slGroup" id="slGroup" class="input-block-level">' .
													$dropdownOptionsHTML . '</select>'; ?>
								</div>
							  </div>

							</div>
							<div class="span6">

							  <div class="control-group">
								<label class="control-label" for="inputName">Layout</label>
								<div class="controls">
									<select name="slLayout" id="slLayout" class="input-block-level">
										<?php
											if (($slLayout=='') || ($slLayout=='layout.php'))
												echo '<option value="layout.php" selected>Default - layout.php</option>';
											else
												echo '<option value="layout.php">Default - layout.php</option>';

											if ($handle = opendir('..')) {
												while (false !== ($entry = readdir($handle))) {
													if (preg_match('/^layout\.[a-z0-9_-]+\.php$/i',$entry)) {
														if ($entry==$slLayout) $myclass = 'selected'; else $myclass = '';
														echo "<option $myclass>$entry</option>";
													}
												}
												closedir($handle);
											}
										?>
									</select>
								</div>
							  </div>

							</div>
						</div>

						<div class="row" style="margin-left:0">
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputDescription">Meta Description</label>
								<div class="controls">
									<textarea name="txtDesc" rows="5" id="txtDesc"
										placeholder="Enter the description of the page"
										title="Enter the description of the page here, this is VERY IMPORTANT for SEO. Do not duplicate on all pages"
										data-toggle="tooltip"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><?php echo $cms->page['description']; ?></textarea>
								</div>
							  </div>
							</div>
							<div class="span6">
							  <div class="control-group">
								<label class="control-label" for="inputKeywords">Meta Keywords</label>
								<div class="controls">
									<textarea name="txtKeywords" rows="5" id="txtKeywords"
										placeholder="Enter the Keywords of the page"
										title="Enter list keywords of the page here, not so important now but use it anyways. Do not stuff keywords"
										data-toggle="tooltip"
										data-placement="top"
										class="input-block-level tooltipme2 countme2"><?php echo $cms->page['keywords']; ?></textarea>
								</div>
							  </div>
							</div>
						</div>
					  </div>

					  <div class="tab-pane" id="d-content">
					    <input border="0" class="input-block-level" name="txtURL" onFocus="this.select();"
							style="cursor: pointer;" onClick="this.select();"  type="text" value="<?php echo $cms->page['url']; ?>" readonly/>
						<textarea name="txtMain" rows="30" id="txtMain" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $cms->page['maincontent']; ?></textarea>
					  </div>

					  <div class="tab-pane" id="d-header">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="ckHeader" type="checkbox" id="ckHeader" value="checkbox" <?php echo $cms->page{'useheader'}; ?>>
								  Enable custom header
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($cms->page{'useheader'}=='checked')
											echo '<span class="label label-important">Page will display custom header below.</span>';
										else
											echo '<span class="label label-info">Page will display the default header.</span>'; ?>
							</div>
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?headcopyid=<?php echo $cms->id; ?>" class="btn btn-mini btn-primary">Copy Default Header</a>
							</div>
						</div>
						<textarea name="txtHeader" rows="30" id="txtHeader" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $cms->page['headercontent']; ?></textarea>
					  </div>

					  <div class="tab-pane" id="d-sidebar">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="ckside" type="checkbox" id="ckside" value="checkbox" <?php echo $cms->page['useside']; ?>>
								  Enable custom Aside 1
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($cms->page['useside']=='checked')
											echo '<span class="label label-important">Page will display custom sidebar A below.</span>';
										else
											echo '<span class="label label-info">Page will display the default sidebar A.</span>'; ?>
							</div>
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?sidecopyid=<?php echo $cms->id; ?>" class="btn btn-mini btn-primary">Copy Default Sidebar A</a>
							</div>
						</div>
						<textarea name="txtSide" rows="30" id="txtSide" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $cms->page['sidecontent']; ?></textarea>
					  </div>

					  <div class="tab-pane" id="d-siderbar">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="cksider" type="checkbox" id="cksider" value="checkbox" <?php echo $usesider; ?>>
								  Enable custom Aside 2
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($usesider=='checked')
											echo '<span class="label label-important">Page will display custom sidebar B below.</span>';
										else
											echo '<span class="label label-info">Page will display the default sidebar B.</span>'; ?>
							</div>
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?sidercopyid=<?php echo $cms->id; ?>" class="btn btn-mini btn-primary">Copy Default Sidebar B</a>
							</div>
						</div>
						<textarea name="txtrSide" rows="30" id="txtrSide" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $cms->page['sidercontent']; ?></textarea>
					  </div>

					  <div class="tab-pane" id="d-footer">
						<div class="row" style="margin-left:0">
							<div class="span4">
								<label class="checkbox">
								  <input name="ckFooter" type="checkbox" id="ckFooter" value="checkbox" <?php echo $cms->page['usefooter']; ?>>
								  Enable custom footer
								</label>
							</div>
							<div class="span4" style="text-align:center">
								<?php if ($cms->page['usefooter']=='checked')
											echo '<span class="label label-important">Page will display custom footer below.</span>';
										else
											echo '<span class="label label-info">Page will display the default footer.</span>'; ?>
							</div>
							<div class="span4" style="text-align:right ">
								<a href="scripts/copy-block.php?footcopyid=<?php echo $cms->id; ?>" class="btn btn-mini btn-primary">Copy Default Footer</a>
							</div>
						</div>
						<textarea name="txtFooter" id="txtFooter" rows="30" style="height: 420px; width:100%"
							class="input-block-level"><?php echo $cms->page['footercontent']; ?></textarea>
					  </div>

					  <div class="tab-pane" id="d-head">

						<blockquote>
						  <p>Append to page head (&lt;head&gt;...include here&lt;\head&gt;) </p>
						  <small>Enter additional <strong>page head content</strong> for this page, you can include js, css, or anything else here</small>
						</blockquote>

						<textarea name="txtHead" rows="30" id="txtHead" style="height: 320px; width:100%"
							class="input-block-level"><?php echo $cms->page['head']; ?></textarea>
					  </div>

					  </div>
					</div>
				  	</form>
				</div>
				<div class="clearfix"></div>
			  </div>
			</div>
		</div>
	</div>

<?php include('include/footer.php'); ?>
<div id="myModal" class="modal hide fade" style="width:90%; margin:2% auto;left: 5%;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h5 style="margin:0; ">Page Stats</h5>
  </div>
  <div class="modal-body">
    <iframe id="shrFrm" src="loading.php"
		width='100%' height='360px' frameborder='0' marginheight='0' marginwidth='0' scrolling="auto"></iframe>
  </div>
</div>
<script type="text/javascript">
	var txtMain_loaded = true;
	var txtHeader_loaded = true;
	var txtFooter_loaded = true;
	var txtSide_loaded = true;
	var txtSider_loaded = true;
	var txtHead_loaded = false;
	var txtCont_loaded = false;

	$('.lframe').click( function() {
		// set the src of the iframe here
		$('#myModal .modal-header h5').text($(this).attr('title'));
		$('#shrFrm').attr('src',$(this).attr('href'));
		$('#myModal').modal('show');
		return false;
	});

	$('#left-tree.treeview li a').click( function() {
		$(this).attr('href', $(this).attr('href')+window.location.hash);
		return true;
	});

	$('#myModal').on('hidden', function () {
  		$('#shrFrm').attr('src','loading.php');
	});

	$('#txtsearch').typeahead({
		source: function (typeahead, query) {
			var pgs=new Array();
			$('#left-tree li a').each( function() {
				pgs.push($(this).text()+']]-->>'+$(this).attr('href'));
			});
			return pgs;
		},
		highlighter: function (item) {
			var regex = new RegExp( '(' + this.query + ')', 'gi' );
			var parts = item.split(']]-->>');
			return (parts[0].replace( regex, "<strong>$1</strong>" )+
					'<span style="display:none;">]]-->>'+parts[1]+'</span>');
		},
		updater: function (item) {
			window.location.href = item.split(']]-->>')[1];
		}
	});

	$('.countme2').each( function() {
		var navKeys = [33,34,35,36,37,38,39,40];
		var that = $(this)
		var thisLabel = $(this).closest('.control-group').find('.control-label');

		$(thisLabel).html( $(thisLabel).text()+
		  	' <span class="countDisplay"><span class="label label-info">'+$(that).val().length+' chars(s)</span></span>');

		// attach event on change
		$(this).on('keyup blur paste', function(e) {
			switch(e.type) {
			  case 'keyup':
				// Skip navigational key presses
				if ($.inArray(e.which, navKeys) < 0) {
					$(thisLabel).find('span.label').text( $(that).val().length+' chars(s)' );
				}
				break;
			  case 'paste':
				// Wait a few miliseconds if a paste event
				setTimeout(function () {
					$(thisLabel).find('span.label').text( $(that).val().length+' chars(s)' );
				}, (e.type === 'paste' ? 5 : 0));
				break;
			  default:
				$(thisLabel).find('span.label').text( $(that).val().length+' chars(s)' );
				break;
			}
		});

	});

	$('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
		window.location.hash = $(this).attr('href').replace('#d-','');
		if ((!txtMain_loaded)&&($(this).attr('href')=='#d-content')) {
			editAreaLoader.init({
				id:"txtMain",
				syntax: "html",
				allow_toggle: false,
				start_highlight: true,
				toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			});
			txtMain_loaded = true;
		}

		if ((!txtHeader_loaded)&&($(this).attr('href')=='#d-header')) {
			editAreaLoader.init({
				id:"txtHeader",
				syntax: "html",
				allow_toggle: false,
				start_highlight: true,
				toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			});
			txtHeader_loaded = true;
		}
		if ((!txtFooter_loaded)&&($(this).attr('href')=='#d-footer')) {
			editAreaLoader.init({
				id:"txtFooter",
				syntax: "html",
				allow_toggle: false,
				start_highlight: true,
				toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			});
			txtFooter_loaded = true;
		}
		if ((!txtSider_loaded)&&($(this).attr('href')=='#d-siderbar')) {
			editAreaLoader.init({
				id:"txtrSide",
				syntax: "html",
				allow_toggle: false,
				start_highlight: true,
				toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			});
			txtSider_loaded = true;
		}
		if ((!txtSide_loaded)&&($(this).attr('href')=='#d-sidebar')) {
			editAreaLoader.init({
				id:"txtSide",
				syntax: "html",
				allow_toggle: false,
				start_highlight: true,
				toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			});
			txtSide_loaded = true;
		}
		if ((!txtHead_loaded)&&($(this).attr('href')=='#d-head')) {
			editAreaLoader.init({
				id:"txtHead",
				syntax: "html",
				allow_toggle: true,
				start_highlight: true,
				toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			});
			txtHead_loaded = true;
		}
		if ((!txtCont_loaded)&&($(this).attr('href')=='#d-controller')) {
			editAreaLoader.init({
				id:"txtCont",
				syntax: "php",
				allow_toggle: true,
				start_highlight: true,
				toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			});
			txtCont_loaded = true;
		}
	});
</script>

<script language="javascript" type="text/javascript">
	if(window.location.hash) $('a[href="'+window.location.hash.replace('#','#d-')+'"]').click();
</script>
</body></html>
