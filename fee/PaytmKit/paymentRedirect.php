<?php

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$checkSum 	= "";
$paramList 	= array();
$MSISDN 	= isset($_POST["MOBILE_NO"])?$_POST["MOBILE_NO"]:"";
$EMAIL 		= isset($_POST["EMAIL"])?$_POST["EMAIL"]:"";
$COLLEGE_ID = isset($_POST["COLLEGE_ID"])?$_POST["COLLEGE_ID"]:"";
$ORDER_ID 	= isset($_POST["ORDER_ID"])?$_POST["ORDER_ID"]:"";
$CUST_ID 	= isset($_POST["CUST_ID"])?$_POST["CUST_ID"]:"";
$TXN_AMOUNT = isset($_POST["TXN_AMOUNT"])?$_POST["TXN_AMOUNT"]:"";

if($COLLEGE_ID && $MSISDN && $EMAIL && $ORDER_ID && $CUST_ID && $TXN_AMOUNT){
	//echo PAYTM_MERCHANT_MID.' | '; echo PAYTM_MERCHANT_KEY; die;
	// Create an array having all required parameters for creating checksum.
	$paramList["MID"] = PAYTM_MERCHANT_MID;
	$paramList["ORDER_ID"] = $ORDER_ID;
	//$paramList["COLLEGE_ID"] = $COLLEGE_ID;
	$paramList["CUST_ID"] = $CUST_ID;
	$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
	$paramList["INDUSTRY_TYPE_ID"] = INDUSTRY_TYPE_ID;
	$paramList["CHANNEL_ID"] = CHANNEL_ID;
	$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

	#$paramList["CALLBACK_URL"] = "https://mahendra.org/online_payment/fee/PaytmKit/paymentResponse.php?COLLEGE_ID=".$COLLEGE_ID;
	$paramList["CALLBACK_URL"] = "http://localhost/online_payment/fee/PaytmKit/paymentResponse.php?COLLEGE_ID=".$COLLEGE_ID;
	$paramList["MSISDN"] = $MSISDN; //Mobile number of customer
	$paramList["EMAIL"] = $EMAIL; //Email ID of customer
	$paramList["VERIFIED_BY"] = "MSISDN"; // EMAIL or MSISDN
	$paramList["IS_USER_VERIFIED"] = "YES"; // YES / NO
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";
	// exit;
	//Here checksum string will return by getChecksumFromArray() function.
	$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);

	?>
	<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Merchant Check Out Page</title>
	<link rel="stylesheet" href="https://mahendra.org/assets/css/ui.css">
	<link rel="stylesheet" href="https://mahendra.org/assets/css/ui.progress-bar.css">
	<script src="https://mahendra.org/assets/js/jquery.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://mahendra.org/assets/js/progress.js" type="text/javascript" charset="utf-8"></script>
	<style>
		body {
			background: #0975cb;
			opacity: 0.9;
			background-image: linear-gradient(90deg, #3f51b5, #0975cb, #3f51b5);
		}
	</style>
	</head>
	<body>
		<div id="container">
			<div style="text-align:center;"><h1>Please do not refresh this page...</h1></div>
			<!-- Progress bar -->
			<div id="progress_bar" class="ui-progress-bar ui-container">
				<div class="ui-progress" style="width: 79%;">
        			<span class="ui-label" style="display:none;">Processing <b class="value">79%</b></span>
      			</div>
			</div>			
		</div>
		<form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
			<table border="1">
				<tbody>
				<?php
				foreach($paramList as $name => $value) {
					echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
				}
				?>
				<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
				</tbody>
			</table>
			<!-- <script type="text/javascript">
				document.f1.submit();
			</script> -->
		</form>
	</body>
	</html>
	<?php
}
else{
	echo '<h4>Required value missing!</h4>';
}?>