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
	include('db.config.php');
	$dbh = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
	mysql_select_db($mysql_database, $dbh);
	if (!$dbh) {
		die('Could not connect to mysql: '. mysql_errno() . mysql_error());
	}

	//Load Configuration Variables
	$currency = mysql_result(mysql_query('SELECT value from stock_config where name="currency"'),0);
	$company_name = mysql_result(mysql_query('SELECT value from stock_config where name="company_name"'),0);
	$sql_q = mysql_query('SELECT value from stock_config where name="sale_type"');
	while( $res_st = mysql_fetch_array($sql_q) ){
		$sale_types[] = $res_st['value'];
	}
	$sql_q = mysql_query('SELECT value from stock_config where name="purchase_type"');
	while( $res_pt = mysql_fetch_array($sql_q) ){
		$purchase_types[] = $res_pt['value'];
	}

	// If form is posted, insert values in database
	if ( isset ($_POST['submit']) && isset($_POST['sdate']) && isset($_POST['iid']) && isset($_POST['stype']) && isset($_POST['sinvoiceno']) && isset($_POST['suprice']) && isset($_POST['sqty']) && $_POST['iid']!="0" ){

		$sdate=$_POST['sdate'];
		$iid=$_POST['iid'];
		$stype=$_POST['stype'];
		$sinvoiceno=$_POST['sinvoiceno'];
		$suprice=$_POST['suprice'];
		$sqty=$_POST['sqty'];
		$scomments=$_POST['scomments'];
		$sql_q="INSERT INTO stock_sale VALUES ('', '$sdate', '$iid', '$stype', '$sinvoiceno', '$suprice', '$sqty', '$scomments');";
		$result = mysql_query($sql_q);

		if ( $result )
			echo '<script type="text/javascript">window.alert("Sale record Added Successfully")</script>';
		else
			echo '<script type="text/javascript">window.alert("ERROR!! Failed to Add Sale record")</script>';
	}
	elseif ( isset ($_POST['submit']) ){
	
		$sdate=$_POST['sdate'];
		$iid=$_POST['iid'];
		$stype=$_POST['stype'];
		$sinvoiceno=$_POST['sinvoiceno'];
		$suprice=$_POST['suprice'];
		$sqty=$_POST['sqty'];
		echo "<script type=\"text/javascript\">window.alert(\"Not Adding Sale record due to missing value. date: $sdate iid: $iid stype: $stype sinvoiceno: $sinvoiceno suprice: $suprice sqty: $sqty \")</script>";
	}
?>
<html>
<head>
	<title><?php echo $company_name;?> Stock :: Sale</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/popup.js'></script>
	<script type="text/javascript" src='js/chain_select.js'></script>
	<script type="text/javascript" src="calendar/calendar.js"></script>
	<script type="text/javascript">
		function updatetotal() {
		document.addform.stotal.value = (document.addform.sqty.value -0) * (document.addform.suprice.value -0);
		}
	</script>

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
				<th>Sale Type</th>
				<th>Invoice No.</th>
				<th>Unit Price</th>
				<th>Qty</th>
				<th>Total</th>
				<th>Comments</th>
			</tr>
		</thead>

		<tbody>
				<?php
					// Select Qurey to print the content
					$res_table = mysql_query('SELECT sale_id, stock_sale.item_id, date, item_name, sale_type, invoice_no, uprice, qty, comments from stock_sale LEFT JOIN stock_items ON stock_sale.item_id=stock_items.item_id;');

					while($row = mysql_fetch_array($res_table)){
						echo '<tr>';
						echo '<td>'.$row['sale_id'].'</td>';
						echo '<td>'.$row['date'].'</td>';
						//echo '<td>'.$row['item_name'].'</td>';
						echo '<td><a href="item_detail.php?iid='.$row['item_id'].'&p=a">'.$row['item_name'].'</a></td>';
						echo '<td>'.$row['sale_type'].'</td>';
						echo '<td>'.$row['invoice_no'].'</td>';
						echo '<td>'.clean_num($row['uprice']).' '.$currency.'</td>';
						echo '<td>'.clean_num($row['qty']).'</td>';
						echo '<td>'.clean_num($row['uprice'] * $row['qty']).'</td>';
						echo '<td><pre>'.$row['comments'].'</pre></td>';
						echo '</tr>';
					}
				?>
		</tbody>
	</table>

	<div id="popupForm">
		<table width=100% cellspacing="5">
		 <form name="addform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">		
			<tr>
				<td colspan="2" align="center" valign="center"><h2>Add Sale</h2></td>
				<td align="right"><a href="javascript:;"><img src="imgs/close.png" height="35" width="35" id="popupFormClose"></img></a></td>
			</tr>
			<tr>
				<td> <b>Date: </b> </td>
				<td>	<?php
						$myCalendar = new tc_calendar("sdate", true, false);
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
				<td> <b>Sale Type: </b> </td>
				<td>
					<select name="stype">
					<?php
						foreach ($sale_types as $_st ){
							echo "<option value=\"$_st\">$_st</option>";
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td> <b>Invoice #: </b> </td>
				<td><input name="sinvoiceno" id="add-form" type="text" size="20" /> </td>
			</tr>
			<tr>
				<td> <b>Unit Price: </b> </td>
				<td><input name="suprice" value="0" id="add-form" type="text" size="5" onChange="updatetotal()" /><?php echo $currency?></td>
			</tr>
			<tr>
				<td> <b>Quantity: </b> </td>
				<td><input name="sqty" value="1" id="add-form" type="text" size="5" onChange="updatetotal()" /> </td>
			</tr>
			<tr>
				<td> <b>Total: </b> </td>
				<td><input name="stotal" disabled="disabled" value="0" id="add-form" type="text" size="5" /><?php echo $currency?></td>
			</tr>

			<tr>
				<td> <b>Comments: </b> </td>
				<td><textarea name="scomments" id="add-form" rows=5 cols=24 ></textarea></td>
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
