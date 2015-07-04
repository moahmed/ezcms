<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
 *
 * Include: Displays the footer
 * 
 */
$sql = 'SELECT (SELECT Count(*) from pages where `published`=1) as pubCNT, (SELECT Count(*) from pages where `published`=0) as unCNT';
$rs = mysql_query($sql) or die("Unable to Details for Web Page");
$row      = mysql_fetch_array($rs);
$pubCNT   = $row['pubCNT'];
$unpubCNT = $row['unCNT'];
$totCNT   = $pubCNT+$unpubCNT;
mysql_free_result($rs);
?>
<div class="clearfix"></div>
<div id="footer">
  <div class="container">
    <div class="row-fluid" style="text-align:center; font-size:0.9em; ">
      <div class="span3"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> 
	  </div>
      <div class="span6"> 
	  	Published: <span class="label label-info"><?php echo $pubCNT; ?> page(s)</span> &middot; 
		Unpublished: <span class="label"><?php echo $unpubCNT; ?> page(s)</span> &middot; 
		Total: <span class="label label-inverse"><?php echo $totCNT; ?> pages</span> 
	  </div>
      <div class="span3"> ezCMS Ver:<strong>3.141029</strong> </div>
    </div>
  </div>
</div>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.treeview/jquery.treeview.js"></script>
<script type="text/javascript">
	$('.tooltipme2').tooltip();
	$("#left-tree").treeview({
		collapsed: true,
		animated: "medium",
		unique: true
	});	
</script>