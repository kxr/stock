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

	// GET ItemID
	$g_iid = $_GET['iid'];

	// Find Item Name and VendorID
	$viin_res = mysql_query("SELECT ven_id, item_name FROM stock_items where item_id=\"$g_iid\"");
	$row = mysql_fetch_array($viin_res);
	$vid = $row['ven_id'];
	$it_nm = $row['item_name'];
	//Find Vendor Name
	$vn_res = mysql_query("SELECT ven_name FROM stock_vendors where ven_id=\"$vid\"");
	$row = mysql_fetch_array($vn_res);
	$vnm = $row['ven_name'];


	//Load Configuration Variables
	$currency = mysql_result(mysql_query('SELECT value from stock_config where name="currency"'),0);
	$company_name = mysql_result(mysql_query('SELECT value from stock_config where name="company_name"'),0);
	$sql_q = mysql_query('SELECT value from stock_config where name="sale_type"');
/*	while( $res_st = mysql_fetch_array($sql_q) ){
		$sale_types[] = $res_st['value'];
	}
	$sql_q = mysql_query('SELECT value from stock_config where name="purchase_type"');
	while( $res_pt = mysql_fetch_array($sql_q) ){
		$purchase_types[] = $res_pt['value'];
	}

	// If form is posted, insert values in database
	if ( isset($_POST['sdate']) && isset($_POST['iid']) && isset($_POST['stype']) && isset($_POST['sinvoiceno']) && isset($_POST['suprice']) && isset($_POST['sqty']) ){

		$sdate=$_POST['sdate'];
		$iid=$_POST['iid'];
		$stype=$_POST['stype'];
		$sinvoiceno=$_POST['sinvoiceno'];
		$suprice=$_POST['suprice'];
		$sqty=$_POST['sqty'];
		$scomments=$_POST['scomments'];
		$sql_q="INSERT INTO stock_sale VALUES ('', '$sdate', '$iid', '$stype', '$sinvoiceno', '$suprice', '$sqty', '$scomments');";
-
		$result = mysql_query($sql_q);

		if ( $result )
			echo '<script type="text/javascript">window.alert("Sale record Added Successfully")</script>';
		else
			echo '<script type="text/javascript">window.alert("ERROR!! Failed to Add Sale record")</script>';
	}
*/
?>
<html>
<head>
	<title><?php echo $company_name;?> Stock :: Item Detail</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/popup.js'></script>
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
<h2 style="{margin:0;}"><?php echo "$it_nm [$vnm]";?></h2>
<?php
	//Stock
	$t_pur = mysql_result(mysql_query("select sum(qty) from stock_purchase where item_id=\"$g_iid\""),0);
	$t_sal = mysql_result(mysql_query("select sum(qty) from stock_sale where item_id=\"$g_iid\""),0);
	$t_hld = mysql_result(mysql_query("select sum(qty) from stock_hold where item_id=\"$g_iid\""),0);
	$t_stk = $t_pur - $t_sal - $t_hld;
        echo '<table border="1" id="table_box" style="{margin:0;}">';
        echo '<thead>';
	echo '<tr> <th>Stock</th> <th>Hold</th> </tr>';
        echo '</thead>';
        echo '<tbody>';
	echo '<tr><td>'.clean_num($t_stk).'</td><td>'.clean_num($t_hld).'</td></tr>';
	echo '</tbody>';
	echo '</table>';			

?>

<center>
<button onClick="window.location='<?php echo $_SERVER['PHP_SELF'].'?iid='.$g_iid.'&p=a';?>'">*</button>
<button onClick="window.location='<?php echo $_SERVER['PHP_SELF'].'?iid='.$g_iid.'&p=v';?>'">Vendor</button>
<button onClick="window.location='<?php echo $_SERVER['PHP_SELF'].'?iid='.$g_iid.'&p=s';?>'">Sale</button>
<button onClick="window.location='<?php echo $_SERVER['PHP_SELF'].'?iid='.$g_iid.'&p=p';?>'">Purchase</button>
<button onClick="window.location='<?php echo $_SERVER['PHP_SELF'].'?iid='.$g_iid.'&p=h';?>'">Hold</button>

	<?php
		//VENDOR DETAILS
		if ( $_GET['p'] == "v" ||  $_GET['p'] == "a" ){
			echo '<table border="1" id="table_box" style="{margin:0;}">';
			echo '<thead>';
			echo '<tr>';
			echo '<th colspan="2">Vendor Details</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$res_vendor = mysql_query("SELECT * FROM stock_vendors WHERE ven_id=\"$vid\";");
			$row = mysql_fetch_array($res_vendor);
			echo '<tr><td colspan="2"><textarea cols="32" rows="8" readonly="readonly">';
echo 'Vendor Name: '.$row['ven_name'].'
Vendor phone: '.$row['ven_phone'].'
Vendor Mobile: '.$row['ven_mobile'].'
Vendor Fax: '.$row['ven_fax'].'
Vendor Email: '.$row['ven_email'].'
Vendor Detail: 
'.$row['ven_detail'];
			echo '</textarea></td></tr>';
			echo '</tbody>';
			echo '</table>';
		}

		//SALE RECORDS
		if ( $_GET['p'] == "s" ||  $_GET['p'] == "a" ){
			echo '<table border="1" id="table_box" style="{margin:0;}">';
			echo '<thead>';
			echo '<tr>';
			echo '<th colspan="8">Sale Records</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$res_sale = mysql_query("SELECT * FROM stock_sale WHERE item_id=\"$g_iid\";");
			while ($row = mysql_fetch_array($res_sale)) {
				echo '<tr>';
				echo '<td>'.$row['sale_id'].'</td>';
				echo '<td>'.$row['date'].'</td>';
				echo '<td>'.$row['sale_type'].'</td>';
				echo '<td>'.$row['invoice_no'].'</td>';
				echo '<td>'.clean_num($row['uprice']).'</td>';
				echo '<td>'.clean_num($row['qty']).'</td>';
				echo '<td>'.clean_num($row['uprice'] * $row['qty']).'</td>';
				echo '<td><pre>'.$row['comments'].'</pre></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}

		//PURCHASE RECORDS
		if ( $_GET['p'] == "p" ||  $_GET['p'] == "a" ){
			echo '<table border="1" id="table_box" style="{margin:0;}">';
			echo '<thead>';
			echo '<tr>';
			echo '<th colspan="8">Purchase Records</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$res_pur = mysql_query("SELECT * FROM stock_purchase WHERE item_id=\"$g_iid\";");
			while ($row = mysql_fetch_array($res_pur)) {
				echo '<tr>';
				echo '<td>'.$row['pur_id'].'</td>';
				echo '<td>'.$row['date'].'</td>';
				echo '<td>'.$row['pur_type'].'</td>';
				echo '<td>'.$row['invoice_no'].'</td>';
				echo '<td>'.clean_num($row['uprice']).'</td>';
				echo '<td>'.clean_num($row['qty']).'</td>';
				echo '<td>'.clean_num($row['uprice'] * $row['qty']).'</td>';
				echo '<td><pre>'.$row['comments'].'</pre></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}

		//HOLD RECORDS
		if ( $_GET['p'] == "h" ||  $_GET['p'] == "a" ){
			echo '<table border="1" id="table_box" style="{margin:0;}">';
			echo '<thead>';
			echo '<tr>';
			echo '<th colspan="8">Hold Records</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			$res_hol = mysql_query("SELECT * FROM stock_hold WHERE item_id=\"$g_iid\" AND status = \"0\";");
			while ($row = mysql_fetch_array($res_hol)) {
				echo '<tr>';
				echo '<td>'.$row['hold_id'].'</td>';
				echo '<td>'.$row['date'].'</td>';
				echo '<td>'.$row['hold_type'].'</td>';
				echo '<td>'.clean_num($row['qty']).'</td>';
				echo '<td><pre>'.$row['comments'].'</pre></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
	?>

</center>
</body>
