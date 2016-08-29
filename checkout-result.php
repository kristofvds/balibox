<?php
session_start();

if (isset($_GET['done']) and isset($_GET['tx']))
{
	$tx = $_GET['tx'];
	$pdt_token = 'h2Tp-3-5wEg2Ev2uWiNC-phKCYaNmAGP8xuyufWC6pxie3I5o9o0ewCSCbK';

	$HTML = '
		<div class="main">
		    <div class="container">
		        <div class="row">
		        	<br>
		            <p>Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you. You may log into your account at www.paypal.com to view details of this transaction.</p>
		        </div>
		    </div>
		</div>
	';

	echo '<script>console.log("Building curl request");</script>';

	// Init cURL
	$request = curl_init();

	// Set request options
	curl_setopt_array($request, array
	(
	  CURLOPT_URL => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
	  CURLOPT_POST => TRUE,
	  CURLOPT_POSTFIELDS => http_build_query(array
	    (
	      'cmd' => '_notify-synch',
	      'tx' => $tx,
	      'at' => $pdt_token,
	    )),
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HEADER => FALSE,
	  // CURLOPT_SSL_VERIFYPEER => TRUE,
	  // CURLOPT_CAINFO => 'cacert.pem',
	));

	echo '<script>console.log("Executing curl query");</script>';

	// Execute request and get response and status code
	$response = curl_exec($request);
	$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

	// Close connection
	curl_close($request);

	echo '<script>console.log("Handling curl response");</script>';

	if($status == 200 AND strpos($response, 'SUCCESS') === 0)
	{
		// Remove SUCCESS part (7 characters long)
		$response = substr($response, 7);

		// URL decode
		$response = urldecode($response);

		// Turn into associative array
		preg_match_all('/^([^=\s]++)=(.*+)/m', $response, $m, PREG_PATTERN_ORDER);
		$response = array_combine($m[1], $m[2]);

		// Fix character encoding if different from UTF-8 (in my case)
		if(isset($response['charset']) AND strtoupper($response['charset']) !== 'UTF-8')
		{
		  foreach($response as $key => &$value)
		  {
		    $value = mb_convert_encoding($value, 'UTF-8', $response['charset']);
		  }
		  $response['charset_original'] = $response['charset'];
		  $response['charset'] = 'UTF-8';
		}

		// Sort on keys for readability (handy when debugging)
		ksort($response);

		echo '<script>console.log("Opening database");</script>';

		// Update status in database
		$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

		/* check connection */
		if (mysqli_connect_errno()) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}

		/* update database record */
		$query = "UPDATE orders
			SET `status` = '3',
				`modified_dt` = NOW(),
				`payment_type` = 'paypal',
				`payment_dt` = NOW(),
				`payer_email` = '" . $response['payer_email'] . "',
				`payer_id` = '" . $response['payer_id'] . "',
				`payment_fee` = '" . $response['payment_fee'] . "',
				`payment_amount` = '" . $response['payment_gross'] . "',
				`payment_status` = '" . $response['payment_status'] . "',
				`payment_trx_id` = '" . $response['txn_id'] . "'
			WHERE id = " . $response['custom'];

		echo '<script>console.log("Executing query");</script>';

		$result = mysqli_query($link, $query);

		if ($result) {
			// Echo HTML
			echo '<script>console.log("Record updated");</script>';
		} else {
			echo '<script>console.log('.mysqli_error($link).');</script>';
		}

		mysqli_close($link);

		// Database updated. Show PayPal response and success message
		echo '<script>console.log(' . json_encode($response) . ');</script>';
		echo $HTML;
	}
	else
	{
		// Payment failed or could not be verified
		echo '<script>console.log("Payment failed or could not be verified");</script>';

		// Update status in database
		$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

		/* check connection */
		if (mysqli_connect_errno()) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}

		/* update database record */
		$query = "UPDATE orders
			SET `status` = '5',
				`modified_dt` = NOW()
			WHERE id = " . $_SESSION['orderID'];

		echo '<script>console.log("Executing query");</script>';

		$result = mysqli_query($link, $query);

		if ($result) {
			// Echo HTML
			echo '<script>console.log("Record updated");</script>';
		} else {
			echo '<script>console.log("'.mysqli_error($link).'");</script>';
		}

		mysqli_close($link);

		// Database updated. Show cancelation message
		echo '
			<div class="main">
			    <div class="container">
			        <div class="row">
			            <p>PayPal transaction failed or could not be verified. No worries. Please contact us and we will assist you.</p>
			        </div>
			    </div>
			</div>
		';
		// Error
		echo '<script>console.log("' . $status . '");</script>';
		echo '<script>console.log("' . $response . '");</script>';
	}
}
else if (isset($_GET['cancel']))
{
	// Payment canceled
	echo '<script>console.log("Payment canceled");</script>';

	// Update status in database
	$link = mysqli_connect("10.3.0.67", "cgsowtqd_balibox", "balibox", "cgsowtqd_balibox");

	/* check connection */
	if (mysqli_connect_errno()) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

	/* update database record */
	$query = "UPDATE orders
		SET `status` = '4',
			`modified_dt` = NOW()
		WHERE id = " . $_SESSION['orderID'];

	echo '<script>console.log("Executing query");</script>';

	$result = mysqli_query($link, $query);

	if ($result) {
		// Echo HTML
		echo '<script>console.log("Record updated");</script>';
	} else {
		echo '<script>console.log('.mysqli_error($link).');</script>';
	}

	mysqli_close($link);

	// Database updated. Show cancelation message
	echo '
		<div class="main">
		    <div class="container">
		        <div class="row">
		            <p>Your order has been canceled.</p>
		        </div>
		    </div>
		</div>
	';
}

?>