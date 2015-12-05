<?php
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
	if ( isset($_POST['vname'])){

		$vname=$_POST['vname'];
		$vphone=$_POST['vphone'];
		$vmobile=$_POST['vmobile'];
		$vfax=$_POST['vfax'];
		$vemail=$_POST['vemail'];
		$vdetail=$_POST['vdetail'];

		$sql_q="INSERT INTO stock_vendors VALUES ('', '$vname', '$vphone', '$vmobile', '$vfax', '$vemail', '$vdetail');";

		$result = mysql_query($sql_q);

		if ( $result )
			echo '<script type="text/javascript">window.alert("Vendor Added Successfully")</script>';
		else
			echo '<script type="text/javascript">window.alert("ERROR!! Failed to Add Vendor")</script>';
	}
?>
<html>
<head>
	<title><?php echo $company_name;?> Stock :: Vendors</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/popup.js'></script>
	<link href="css/table_box.css" rel="stylesheet" type="text/css">
	<link href="css/panel.css" rel="stylesheet" type="text/css">
	<link href="css/popup.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		html,body {margin:0;padding:0;}
	</style>
</head>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>
<br />
<center>
	<table border="1" id="table_box">
		<thead>		
			<tr>
				<th>#</th>
				<th>Vendor</th>
				<th>Phone</th>
				<th>Mobile</th>
				<th>Fax</th>
				<th>Email</th>
				<th>Detail</th>
			</tr>
		</thead>

		<tbody>
				<?php
					// Select Qurey to print the content
					$res_table = mysql_query('SELECT * FROM stock_vendors');

					while($row = mysql_fetch_array($res_table)){
						echo '<tr>';
						echo '<td>'.$row['ven_id'].'</td>';
						echo '<td>'.$row['ven_name'].'</td>';
						echo '<td>'.$row['ven_phone'].'</td>';
						echo '<td>'.$row['ven_mobile'].'</td>';
						echo '<td>'.$row['ven_fax'].'</td>';
						echo '<td>'.$row['ven_email'].'</td>';
						echo '<td><pre>'.$row['ven_detail'].'</pre></td>';
						echo '</tr>';
					}
				?>
		</tbody>
	</table>



	<div id="popupForm">
		<table width=100% cellspacing="5">
		 <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">		
			<tr>
				<td colspan="2" align="center" valign="center"><h2>Add Vendor</h2></td>
				<td align="right"><a href="javascript:;"><img src="imgs/close.png" height="35" width="35" id="popupFormClose"></img></a></td>
			</tr>
			<tr>
				<td> <b>Vendor Name: </b> </td>
				<td><input name="vname"id="add-form" type="text" size="20"/> </td>	
			</tr>
			<tr>
				<td> <b>Vendor phone: </b> </td>
				<td><input name="vphone" id="add-form" type="text" size="20" /> </td>
			</tr>
			<tr>
				<td> <b>Vendor Mobile: </b> </td>
				<td><input name="vmobile" id="add-form" type="text" size="20" /> </td>
			</tr>
			<tr>
				<td> <b>Vendor Fax: </b> </td>
				<td><input name="vfax" id="add-form" type="text" size="20" /> </td>
			</tr>
			<tr>
				<td> <b>Vendor Email: </b> </td>
				<td><input name="vemail" id="add-form" type="text" size="20" /> </td>
			</tr>
			<tr>
				<td> <b>Vendor Detail: </b> </td>
				<td><textarea name="vdetail" id="add-form" rows=5 cols=24 ></textarea></td>
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
