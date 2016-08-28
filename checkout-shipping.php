<?php
session_start();

$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

/* check connection */
if (mysqli_connect_errno()) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* insert new record */
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

$result = mysqli_query($link, $query);

if ($result) {
	$orderID = mysqli_insert_id($link);
	$_SESSION['orderID'] = $orderID;
	header('Content-Type: application/json');
	echo json_encode(array('id' => $_SESSION['orderID']));
} else {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	printf("Error: %s\n", mysqli_error($link));
}

mysqli_close($link);
?>