<?php
$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* insert new record */
$query = "INSERT INTO orders VALUES (NULL,
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
	'".$_POST["product"]."')";

mysqli_query($link, $query);
printf ("New Record has id %d.\n", mysqli_insert_id($link));

mysqli_close($link);
?>