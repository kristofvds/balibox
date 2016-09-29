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

	if ($_POST["newsletter"] == "1") {
		/* Add subscription to database */
		$query2 = "INSERT INTO subscriptions (
						`id`,
						`created_dt`,
						`email`,
						`firstname`,
						`lastname`,
						`email-md5`
					) VALUES (
						NULL,
						NOW(),
						'".$_POST["email"]."',
						'".$_POST["firstname"]."',
						'".$_POST["lastname"]."',
						'".md5(strtolower($_POST['email']))."'
					)";

		$result2 = mysqli_query($link, $query2);

		if ($result2) {
			//Subscription table updated, now contacting MailChimp.

			// https://stackoverflow.com/questions/30481979/adding-subscribers-to-a-list-using-mailchimps-api-v3/32956160#32956160
			$data = [
			    'email'     => $_POST["email"],
			    'status'    => 'subscribed',
			    'firstname' => $_POST["firstname"],
			    'lastname'  => $_POST["lastname"]
			];

			try {
			    $apiKey = 'c0f6a7958046552795baf0bf790c4cb6-us14';
			    $listId = '8c3bdd03db';

			    $memberId = md5(strtolower($data['email']));
			    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
			    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

			    $json = json_encode([
			        'email_address' => $data['email'],
			        'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
			        'merge_fields'  => [
			            'FNAME'     => $data['firstname'],
			            'LNAME'     => $data['lastname']
			        ]
			    ]);

			    $ch = curl_init($url);

			    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
			    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

			    $curlResult = curl_exec($ch);
			    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			    curl_close($ch);

			    if ($httpCode == "200") {
			    	//MailChimp DB updated
			    } else {
			    	//MailChimp DB could not be updated
			    }
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			}

		} else {
			printf("Error: %s\n", mysqli_error($link));
		}
	}

	// Save order details to session for later use
	$_SESSION['clientFirstName'] = $_POST["firstname"];
	$_SESSION['clientLastName'] = $_POST["lastname"];
	$_SESSION['clientEmail'] = $_POST["email"];
	$_SESSION['product'] = $_POST["product"];
	$_SESSION['price'] = "$30.00";
	if ($_SESSION['product'] == "3") {
		$_SESSION['price'] = "$84.00";
	} else if ($_SESSION['product'] == "6") {
		$_SESSION['price'] = "$156.00";
	}

	header('Content-Type: application/json');
	echo json_encode(array('id' => $_SESSION['orderID']));

} else {

	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	printf("Error: %s\n", mysqli_error($link));

}

mysqli_close($link);
?>