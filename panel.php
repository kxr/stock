<?php
	$parts = explode('/', $_SERVER["PHP_SELF"]);
	$self_file = $parts[count($parts) - 1];

	if ( "$self_file" == "index.php" ) {
		$style_index=' style="color: white;" ';
	}
	elseif ( "$self_file" == "hold.php" ) {
		$style_hold=' style="color: white;" ';
		$add_button='<img id="button" height=55 width=55 src="imgs/add.png"></img>';
	}
	elseif ( "$self_file" == "items.php" ) {
		$style_items=' style="color: white;" ';
		$add_button='<img id="button" height=55 width=55 src="imgs/add.png"></img>';
	}
	elseif ( "$self_file" == "sale.php" ) {
		$style_sale=' style="color: white;" ';
		$add_button='<img id="button" height=55 width=55 src="imgs/add.png"></img>';
	}
	elseif ( "$self_file" == "purchase.php" ) {
		$style_purchase=' style="color: white;" ';
		$add_button='<img id="button" height=55 width=55 src="imgs/add.png"></img>';
	}
	elseif ( "$self_file" == "vendors.php" ) {
		$style_vendors=' style="color: white;" ';
		$add_button='<img id="button" height=55 width=55 src="imgs/add.png"></img>';
	}
	elseif ( "$self_file" == "reports.php" ) {
		$style_reports=' style="color: white;" ';
	}
?>

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="left">
			<a href="javascript:;"><?php echo $add_button; ?></a>
		</td>
		<td align="right" valign="top">
			<table cellpadding="5" cellspacing="0" bgcolor="#212121">
				<tr>
					<td><a class="panel" href="index.php" <?php echo $style_index;?>>&nbsp;&nbsp;Stock&nbsp;&nbsp;</a></td>
					<td><a class="panel" href="hold.php" <?php echo $style_hold;?>>&nbsp;&nbsp;Hold&nbsp;&nbsp;</a></td>
					<td><a class="panel" href="sale.php" <?php echo $style_sale;?>>&nbsp;&nbsp;Sale&nbsp;&nbsp;</a></td>
					<td><a class="panel" href="purchase.php" <?php echo $style_purchase;?>>&nbsp;&nbsp;Purchase&nbsp;&nbsp;</a></td>
					<td><a class="panel" href="items.php" <?php echo $style_items;?>>&nbsp;&nbsp;Items&nbsp;&nbsp;</a></td>
					<td><a class="panel" href="vendors.php" <?php echo $style_vendors;?>>&nbsp;&nbsp;Vendors&nbsp;&nbsp;</a></td>
					<td><a class="panel" href="reports.php" <?php echo $style_reports;?>>&nbsp;&nbsp;Reports&nbsp;&nbsp;</a></td>
					<td><a class="panel" href="config.php" <?php echo $style_reports;?>><img src="imgs/settings.png" height="20" width="20"></img</a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
