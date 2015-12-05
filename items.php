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
	if ( isset($_POST['iname']) && strlen($_POST['iname']) != 0){

		$iname=addslashes($_POST['iname']);
		$vid=$_POST['vid'];

		$sql_q="INSERT INTO stock_items VALUES ('', '$vid', '$iname');";

		$result = mysql_query($sql_q);

		if ( $result )
			echo '<script type="text/javascript">window.alert("Item Added Successfully")</script>';
		else
			echo '<script type="text/javascript">window.alert("ERROR!! Failed to Add Item")</script>';
	}
	elseif ( isset($_POST['iname']) && strlen($_POST['iname']) == 0) {
			echo '<script type="text/javascript">window.alert("ERROR: Item Name Empty")</script>';
		}
?>
<html>
<head>
	<title><?php echo $company_name;?> Stock :: Items</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/popup.js'></script>
	<script type="text/javascript" src='js/jquery.dataTables.min.js'></script>
	<link href="css/table_box.css" rel="stylesheet" type="text/css">
	<link href="css/panel.css" rel="stylesheet" type="text/css">
	<link href="css/popup.css" rel="stylesheet" type="text/css">
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
							echo '<tr>';
							echo "<th colspan=3>$_vn</th>";
							echo '</tr>';
							echo '</thead>';


							$res_table2 = mysql_query("SELECT item_id, item_name from stock_items where ven_id=\"$_vi\"");
							while($row2 = mysql_fetch_array($res_table2)){
								$_ii = $row2['item_id'];
								$_in = $row2['item_name'];
								echo '<tbody>';
								echo '<tr>';
								echo "<td>$_ii</td>";
								echo "<td><a href=\"item_detail.php?iid=$_ii&p=a\">$_in</a></td>";
								echo '</tr>';
								echo '</tbody>';
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
