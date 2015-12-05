<?php
	// Decimal number cleaner i.e, removing trailing zeros
	// This function should go to a function library if one is built
	function clean_num( $num ){
		$pos = strpos($num, '.');
		if($pos === false) { // it is integer number
			return $num;
		}
		else{ // it is decimal number
			return rtrim(rtrim($num, '0'), '.');
		}
	}

	//Including Calendar class
	require_once('calendar/classes/tc_calendar.php');

	// MYSQL Connection and Database Selection
	include_once('db.config.php');
	$dbh = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
	mysql_select_db($mysql_database, $dbh);
	if (!$dbh) {
		die('Could not connect to mysql: '. mysql_errno() . mysql_error());
	}

	//Load Configuration Variables
	$currency = mysql_result(mysql_query('SELECT value from stock_config where name="currency"'),0);
	$company_name = mysql_result(mysql_query('SELECT value from stock_config where name="company_name"'),0);
	$sql_q = mysql_query('SELECT value from stock_config where name="hold_type"');
	while( $res_ht = mysql_fetch_array($sql_q) ){
		$hold_types[] = $res_ht['value'];
	}

	// Check for Delete
	if ( isset ($_GET['delete']) ) {
		$to_del=$_GET['delete'];
		$sql_q="UPDATE stock_hold SET status = status + 1 WHERE hold_id='$to_del'";
		$result = mysql_query($sql_q);
		
		if ( $result )
			echo '<script type="text/javascript">window.alert("Hold record Deleted Successfully.")</script>';
		else
			echo '<script type="text/javascript">window.alert("ERROR!! Failed to Delete Hold record")</script>';

		header('location:'.$_SERVER['PHP_SELF']);
	}
	// Check for show all
	if ( ! isset ($_GET['showall']) ) {
		$mysql_condition=' WHERE status = "0"';
	}

	// If form is posted, insert values in database
	if ( isset ($_POST['submit']) && isset($_POST['hdate']) && isset($_POST['iid']) && isset($_POST['htype']) && isset($_POST['hqty']) && $_POST['iid']!="0" ){

		$hdate=$_POST['hdate'];
		$iid=$_POST['iid'];
		$htype=$_POST['htype'];
		$huprice=$_POST['huprice'];
		$hqty=$_POST['hqty'];
		$hcomments=$_POST['hcomments'];
		$sql_q="INSERT INTO stock_hold VALUES ('', '$hdate', '$iid', '$htype', '$hqty', '$hcomments', '');";
		$result = mysql_query($sql_q);

		if ( $result )
			echo '<script type="text/javascript">window.alert("Hold Record Added Successfully")</script>';
		else
			echo '<script type="text/javascript">window.alert("ERROR!! Failed To Add Hold Record")</script>';
	}
	elseif ( isset ($_POST['submit']) ){
		$hdate=$_POST['hdate'];
		$iid=$_POST['iid'];
		$htype=$_POST['htype'];
		$hqty=$_POST['hqty'];
		$hcomments=$_POST['hcomments'];
	
		echo "<script type=\"text/javascript\">window.alert(\"Not Adding Hold record due to missing value. date: $hdate iid: $iid htype: $htype hqty: $hqty \")</script>";
	}
?>
<html>
<head>
	<title><?php echo $company_name;?> Stock :: Hold</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/popup.js'></script>
	<script type="text/javascript" src='js/chain_select.js'></script>
	<script type="text/javascript" src="calendar/calendar.js"></script>
	<link href="css/table_box.css" rel="stylesheet" type="text/css">
	<link href="css/panel.css" rel="stylesheet" type="text/css">
	<link href="css/popup.css" rel="stylesheet" type="text/css">
	<link href="calendar/calendar.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		html,body {margin:0;padding:0;}
	</style>
</head>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>
<center>
	<table border="1" id="table_box">
		<thead>		
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>Item Name</th>
				<th>Hold Type</th>
				<th>Qty</th>
				<th>Comments</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
				<?php
					// Select Qurey to print the content
					$res_table = mysql_query('SELECT hold_id, stock_hold.item_id, date, item_name, hold_type, qty, comments from stock_hold LEFT JOIN stock_items ON stock_hold.item_id=stock_items.item_id'.$mysql_condition);

					while($row = mysql_fetch_array($res_table)){
						echo '<tr>';
						echo '<td>'.$row['hold_id'].'</td>';
						echo '<td>'.$row['date'].'</td>';
						//echo '<td>'.$row['item_name'].'</td>';
						echo '<td><a href="item_detail.php?iid='.$row['item_id'].'&p=a">'.$row['item_name'].'</a></td>';
						echo '<td>'.$row['hold_type'].'</td>';
						echo '<td>'.clean_num($row['qty']).'</td>';
						echo '<td><pre>'.$row['comments'].'</pre></td>';
						echo '<td><a href="'.$_SERVER['PHP_SELF'].'?delete='.$row['hold_id'].'"><img src="imgs/del.png" width="15" height="15"></img></a></td>';
						echo '</tr>';
					}
				?>
		</tbody>
	</table>

	<div id="popupForm">
		<table width=100% cellspacing="5">
		 <form name="addform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">		
			<tr>
				<td colspan="2" align="center" valign="center"><h2>Hold Item</h2></td>
				<td align="right"><a href="javascript:;"><img src="imgs/close.png" height="35" width="35" id="popupFormClose"></img></a></td>
			</tr>
			<tr>
				<td> <b>Date: </b> </td>
				<td>	<?php
						$myCalendar = new tc_calendar("hdate", true, false);
						$myCalendar->setIcon("calendar/images/iconCalendar.gif");
						$myCalendar->setPath("calendar/");
						$myCalendar->setYearInterval(2012, 2020);
						$myCalendar->setAlignment('left', 'bottom');
						$myCalendar->setDate(date('d'), date('m'), date('Y'));
						$myCalendar->writeScript();
					?>
				</td>	
			</tr>
			<tr>
				<td> <b>Item : </b> </td>
				<td>
					<select id="svenid">
						<?php
							$sql = "SELECT * FROM stock_vendors";
							$res = mysql_query($sql,$dbh);
							echo '<option>Select Vendor</option>';
							while($row = mysql_fetch_array($res)) {
								echo '<option value="' . $row['ven_id'] . '">' . $row['ven_name'] . '</option>';
							}
						?>
					</select>
					<br />
					<select name="iid" id="sitemid">
						<option disabled="disabled">Select Vendor</option>
					</select>
				</td> 
			</tr>
			<tr>
				<td> <b>Hold Type: </b> </td>
				<td>
					<select name="htype">
					<?php
						foreach ($hold_types as $_ht ){
							echo "<option value=\"$_ht\">$_ht</option>";
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td> <b>Quantity: </b> </td>
				<td><input name="hqty" value="1" id="add-form" type="text" size="5" onChange="updatetotal()" /> </td>
			</tr>
			<tr>
				<td> <b>Comments: </b> </td>
				<td><textarea name="hcomments" id="add-form" rows=5 cols=24 ></textarea></td>
			</tr>
			<tr>
				<td colspan="2" align=center> <input name="submit" type="submit" value="Add" /></td>
			</tr>
		 </form>
		</table>
	</div>
	<div id="backgroundPopup"></div>
</center>
</body>
