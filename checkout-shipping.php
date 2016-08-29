<?php
session_start();

$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

/* check connection */
if (mysqli_connect_errno()) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (isset($_SESSION['orderID']))
{
	/* Update existing record */
	$query = "UPDATE orders
		SET `status` = '1',
			`modified_dt` = NOW(),
			`email` = '".$_POST["email"]."',
			`firstname` = '".$_POST["firstname"]."',
			`lastname` = '".$_POST["lastname"]."',
			`shipping-streetaddress` = '".$_POST["streetaddress"]."',
			`shipping-addressextension` = '".$_POST["addressextension"]."',
			`shipping-city` = '".$_POST["city"]."',
			`shipping-zipcode` = '".$_POST["zipcode"]."',
			`shipping-country` = '".$_POST["country"]."',
			`gift` = '".$_POST["gift"]."',
			`newsletter` = '".$_POST["newsletter"]."',
			`product` = '".$_POST["product"]."'
		WHERE id = " . $_SESSION['orderID'];
}
else
{
	/* Create new record */
	$query = "INSERT INTO orders
		(`id`, `created_dt`, `modified_dt`, `email`, `firstname`, `lastname`, `shipping-streetaddress`, `shipping-addressextension`, `shipping-city`, `shipping-zipcode`, `shipping-country`, `gift`, `newsletter`, `product`, `status`)
		VALUES (NULL,
		NOW(),
		NOW(),
		'".$_POST["email"]."',
		'".$_POST["firstname"]."',
		'".$_POST["lastname"]."',
		'".$_POST["streetaddress"]."',
		'".$_POST["addressextension"]."',
		'".$_POST["city"]."',
		'".$_POST["zipcode"]."',
		'".$_POST["country"]."',
		'".$_POST["gift"]."',
		'".$_POST["newsletter"]."',
		'".$_POST["product"]."',
		'1')";
}

$result = mysqli_query($link, $query);

if ($result) {
	if (!isset($_SESSION['orderID'])) {
		$_SESSION['orderID'] = mysqli_insert_id($link);
	}
	header('Content-Type: application/json');
	echo json_encode(array('id' => $_SESSION['orderID']));
} else {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	printf("Error: %s\n", mysqli_error($link));
}

mysqli_close($link);
?>