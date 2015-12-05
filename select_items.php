<?php
	include('db.config.php');
	$dbh = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
	mysql_select_db($mysql_database, $dbh);
	if (!$dbh) {
		die('Could not connect to mysql: '. mysql_errno() . mysql_error());
	}
	$sql = "SELECT * FROM stock_items WHERE ven_id='$_POST[p_vid]'";
	$res = mysql_query($sql,$dbh);
	echo '<option value="0" selected="selected">Select Item</option>';
	while($row = mysql_fetch_array($res)) {
		echo '<option value="' . $row['item_id'] . '">' . $row['item_name'] . '</option>';
	}
?>
