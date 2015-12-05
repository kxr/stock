<?php
	function clean_num( $num ){
		$pos = strpos($num, '.');
		if($pos === false) { // it is integer number
			return $num;
		}
		else{ // it is decimal number
			return rtrim(rtrim($num, '0'), '.');
		}
	}
	function price2code ( $price) {
		$price_arr = str_split($price);
		$nums_arr = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0' );
		$code = mysql_result(mysql_query('SELECT value from stock_config where name="price_code"'),0);
		$code_arr = str_split($code);
		return str_replace( $nums_arr, $code_arr, $price);
	}
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

	// If form is posted, insert values in database
	if ( isset($_POST['iname'])){

		$iname=addslashes($_POST['iname']);
		$vid=$_POST['vid'];

		$sql_q="INSERT INTO stock_items VALUES ('', '$vid', '$iname');";

		$result = mysql_query($sql_q);

		if ( $result )
			echo '<script type="text/javascript">window.alert("Item Added Successfully")</script>';
		else
			echo '<script type="text/javascript">window.alert("ERROR!! Failed to Add Item")</script>';
	}
?>
<html>
<head>
	<title><?php echo $company_name;?> Stock :: Home</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/popup.js'></script>
	<script type="text/javascript" src='js/jquery.dataTables.min.js'></script>
	<link href="css/table_box.css" rel="stylesheet" type="text/css">
	<link href="css/panel.css" rel="stylesheet" type="text/css">
	<link href="css/popup.css" rel="stylesheet" type="text/css">
	<link href="css/tool_tip.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		html,body {margin:0;padding:0;}
	</style>
</head>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>
<center>
	<table border="1" id="table_box">
				<?php
					$res_table = mysql_query ('SELECT ven_id, ven_name from stock_vendors ORDER BY ven_name');	
						while($row = mysql_fetch_array($res_table)){
							$_vi = $row['ven_id'];
							$_vn = $row['ven_name'];
							echo '<thead>';	
							echo "<tr><th align=\"center\" colspan=5>$_vn</th></tr>";
							echo "<tr>";
							echo "<th>Item Name</th>";
							echo "<th>Stock</th>";
							echo "<th>Hold</th>";
							echo "<th>Last Purchase</th>";
							echo "<th>Last Sale</th>";
							echo "</tr>";
							echo '</thead>';


							$res_table2 = mysql_query("SELECT item_id, item_name from stock_items where ven_id=\"$_vi\"");
							while($row2 = mysql_fetch_array($res_table2)){
								$_ii = $row2['item_id'];
								$_in = $row2['item_name'];
								//total purchases qty count
								$t_pur = mysql_result(mysql_query("select sum(qty) from stock_purchase where item_id=\"$_ii\""),0);
								//total sale qty count
								$t_sal = mysql_result(mysql_query("select sum(qty) from stock_sale where item_id=\"$_ii\""),0);
								//total hold
								$t_hld = mysql_result(mysql_query("select sum(qty) from stock_hold where item_id=\"$_ii\" and status = \"0\""),0);
								//stock total purchase - total stock
								$t_stk = $t_pur - $t_sal - $t_hld;

								echo '<tbody>';
								echo '<tr>';
								echo "<td><a href=\"item_detail.php?iid=$_ii&p=a\">$_in</a></td>";
								echo '<td>'.clean_num($t_stk).'</td><td>'.clean_num($t_hld).'</td>';

								//last purchase
								$lpr_res = mysql_query("select * from stock_purchase where item_id=\"$_ii\" ORDER BY date DESC LIMIT 1;");
								$lpr_arr = mysql_fetch_array($lpr_res);
									echo '<td><a href="javascript:;" class="Tooltip">'.$lpr_arr['date'];
										echo '<span>';
											echo '<table id="TooltipTable">';
											echo '<tr><td>ID</td><td>Date</td><td>Type</td><td>Inv #</td><td>Qty</td><td>Unit Price</td><td>Comments</td></tr>';
											echo '<tr><td>'.$lpr_arr['pur_id'].'</td><td>'.$lpr_arr['date'].'</td><td>'.$lpr_arr['pur_type'].'</td><td>'.$lpr_arr['invoice_no'].'</td><td>'.clean_num($lpr_arr['qty']).'</td><td>'.price2code(clean_num($lpr_arr['uprice'])).'</td><td>'.$lpr_arr['comments'].'</td></tr>';
											echo '</table>';
										echo '</span>';
									echo '</a></td>';

								//last sale
								$lsl_res = mysql_query("select * from stock_sale where item_id=\"$_ii\" ORDER BY date DESC LIMIT 1;");
								$lsl_arr = mysql_fetch_array($lsl_res);
									echo '<td><a href="javascript:;" class="Tooltip">'.$lsl_arr['date'];
										echo '<span>';
											echo '<table id="TooltipTable">';
											echo '<tr><td>ID</td><td>Date</td><td>Type</td><td>Inv #</td><td>Qty</td><td>Unit Price</td><td>Comments</td></tr>';
											echo '<tr><td>'.$lsl_arr['sale_id'].'</td><td>'.$lsl_arr['date'].'</td><td>'.$lsl_arr['sale_type'].'</td><td>'.$lsl_arr['invoice_no'].'</td><td>'.clean_num($lsl_arr['qty']).'</td><td>'.price2code(clean_num($lsl_arr['uprice'])).'</td><td>'.$lsl_arr['comments'].'</td></tr>';
											echo '</table>';
										echo '</span>';
									echo '</a></td>';
								echo '</tr></tr></tr></tr></tr></tr></tr></tr></tbody>';
							}
						}
				?>
	</table>



	<div id="popupForm">
		<table width=100% cellspacing="5">
		 <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">		
			<tr>
				<td colspan="2" align="center" valign="center"><h2>Add Item</h2></td>
				<td align="right"><a href="javascript:;"><img src="imgs/close.png" height="35" width="35" id="popupFormClose"></img></a></td>
			</tr>
			<tr>
				<td> <b>Vendor: </b> </td>
				<td>
					<select name="vid">
						<?php
							$res_table = mysql_query('SELECT ven_id, ven_name FROM stock_vendors');
							while($row = mysql_fetch_array($res_table)){
								echo '<option value="'.$row['ven_id'].'">'.$row['ven_name'].'</option>';
							}
						?>	
					</select>
				</td>	
			</tr>
			<tr>
				<td> <b>Item Name: </b> </td>
				<td><input name="iname" id="add-form" type="text" size="30" /> </td>
			</tr>
			<tr>
				<td colspan=2" align=center> <input type=submit value=Add /></td>
			</tr>
		 </form>
		</table>
	</div>
	<div id="backgroundPopup"></div>

</center>
</body>
