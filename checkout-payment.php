<?php
session_start();

$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

/* check connection */
if (mysqli_connect_errno()) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* update database record */
if ($_POST["input-billing-address"] == "same")
{
	$query = "UPDATE orders
		SET `status` = '2',
			`modified_dt` = NOW(),
			`billing-streetaddress` = `shipping-streetaddress`,
			`billing-addressextension` = `shipping-addressextension`,
			`billing-city` = `shipping-city`,
			`billing-zipcode` = `shipping-zipcode`,
			`billing-country` = `shipping-country`
		WHERE id = " . $_SESSION['orderID'];
}
else
{
	$query = "UPDATE orders
		SET `status` = '2',
			`modified_dt` = NOW(),
			`billing-streetaddress` = '".$_POST["streetaddress"]."',
			`billing-addressextension` = '".$_POST["addressextension"]."',
			`billing-city` = '".$_POST["city"]."',
			`billing-zipcode` = '".$_POST["zipcode"]."',
			`billing-country` = '".$_POST["country"]."'
		WHERE id = " . $_SESSION['orderID'];
}

$result = mysqli_query($link, $query);

if ($result) {
	printf("Record updated\n");
} else {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	printf("Error: %s\n", mysqli_error($link));
}

mysqli_close($link);
?>