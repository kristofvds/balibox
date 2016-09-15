<?php

$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

/* check connection */
if (mysqli_connect_errno()) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* Create new record */
$query = "INSERT INTO subscriptions (
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

$result = mysqli_query($link, $query);

if ($result) {
	echo "DB updated, now contacting MailChimp";

	// https://stackoverflow.com/questions/30481979/adding-subscribers-to-a-list-using-mailchimps-api-v3/32956160#32956160
	$data = [
	    'email'     => $_POST["email"],
	    'status'    => 'subscribed',
	    'firstname' => $_POST["firstname"],
	    'lastname'  => $_POST["lastname"]
	];

	try {
	    $apiKey = 'e3d3d4c561178697a7a8153cef2f39c2-us14';
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
	    	echo "MailChimp DB updated";
	    } else {
	    	echo "MailChimp DB could not be updated";
	    }
	} catch (Exception $e) {
	    echo 'Caught exception: ',  $e->getMessage(), "\n";
	}

} else {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	printf("Error: %s\n", mysqli_error($link));
}

mysqli_close($link);

?>