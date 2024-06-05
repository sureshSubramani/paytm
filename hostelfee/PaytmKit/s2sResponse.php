<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";
/*echo '<pre>';
print_r($_POST);
echo '</pre>'; exit;*/
$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application?s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	//echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		//echo "<b>Please wait...</b>" . "<br/>";
		//echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
	}
	else {
		//echo "<b>Transaction status is failure</b>" . "<br/>";
	}

	//if (isset($_POST) && count($_POST)>0 )
	if (isset($_POST['ORDERID']) && $_POST['ORDERID']!="")
	{ 
		/*foreach($_POST as $paramName => $paramValue) {
				echo "<br/>" . $paramName . " = " . $paramValue;
		}*/ 

		//SEND MAIL
		require_once("../classes/class.phpmailer.php"); // include the class name

		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPAutoTLS = true;
		$mail->CharSet = 'windows-1250';
		$mail->ContentType = 'text/plain';
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		//$mail->SMTPSecure = 'tls';  // secure transfer enabled REQUIRED for GMail
		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Set the encryption mechanism to use - STARTTLS or SMTPS
		//$mail->Host = 'localhost';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; //465 or 587
		$mail->IsHTML(true);		
		
		$subject = "Paytm - S2S Response received";
		$messageBody = "<html>
		<body>
		<h4>We received a S2S response for transaction ID ".$_POST['TXNID']." on ".$_POST['TXNDATE']."</h4> 
		<h5 style='text-transform: uppercase;text-decoration:underline;'>Payment Details</h5>
		<table border='1'>
		<tbody>
			<tr>
				<td>Order ID</td>
				<td>".str_replace("'","",$_POST['ORDERID'])."</td>
			</tr>
			<tr>
				<td>Merchant ID</td>
				<td>".str_replace("'","",$_POST['MID'])."</td>
			</tr>
			<tr>
				<td>Transaction ID</td>
				<td>".str_replace("'","",$_POST['TXNID'])."</td>
			</tr>
			<tr>
				<td>Amount</td>
				<td>".str_replace("'","",$_POST['TXNAMOUNT'])."</td>
			</tr>
			<tr>
				<td>Payment Status</td>
				<td>".str_replace("'","",$_POST['RESPMSG'])."</td>
			</tr>
			<tr>
				<td>Bank Name</td>
				<td>".str_replace("'","",$_POST['BANKNAME'])."</td>
			</tr>
			<tr>
				<td>Payment Mode</td>
				<td>".str_replace("'","",$_POST['PAYMENTMODE'])."</td>
			</tr>
		</tbody>
		</table>				
		</body>
		</html>";

		$mail->Username = "mahendraeducation@gmail.com";
		$mail->Password = "blswinwjxexdqmvg";
		$mail->SetFrom("mahendraeducation@gmail.com");
		
		$mail->Subject = $subject;
		$mail->Body = $messageBody;

		//$mail->AddAddress('ramsmnw@gmail.com', 'Mahendra Admission');
		$mail->AddAddress('ragunathan.c@mnxw.org', 'Mahendra Admission');
		//$mail->AddAddress('suresh.mnw1877@gmail.com', 'Mahendra Admission');
		//$mail->AddCC('', 'Mahendra Admission');									

		if(!$mail->Send()){
			$mail_error = "Mailer Error: " . $mail->ErrorInfo;
		}else{
			$mail_error = "Message has been sent successfully.";
		}
		//echo $mail_error;
		/*echo '<pre>';
		print_r($_POST);
		echo '</pre>'; exit;*/
		?>
		<form class="form col-md-12" method="post" action="hostelPaymentTxnStatus.php" name="admissionTxnStatus">
            <table border="1">
               <tbody>
                  <input class="form-control" type="hidden" name="ORDER_ID" placeholder="Order ID / Number" value="<?php echo $_POST['ORDERID']; ?>">                  
                  <input class="form-control btn btn-success" type="submit" value="PAY NOW" style="display: none">
               </tbody>
            </table>
            <script type="text/javascript">
               document.admissionTxnStatus.submit();
            </script>
         </form>

		<?php

	}
	else{
		//echo "<b>Order ID not received.</b>";
	}
	

}
else {
	//echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>