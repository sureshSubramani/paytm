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

$paramList = $_POST;
// echo '<pre>';
// print_r($paramList);
// die;

$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

if($isValidChecksum == "TRUE") {
	//echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Please wait...</b>" . "<br/>";
		//echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
	}

	//if (isset($_POST) && count($_POST)>0 )
	if (isset($_POST['ORDERID']) && $_POST['ORDERID']!="")
	{ 
		/*foreach($_POST as $paramName => $paramValue) {
				echo "<br/>" . $paramName . " = " . $paramValue;
		}*/ ?>
		<form class="form col-md-12" method="post" action="paymentTxnStatus.php" name="paymentTxnStatus">
            <table border="1">
               <tbody>
			   	  <input class="form-control" type="hidden" name="COLLEGE_ID" value="<?php echo $_REQUEST['COLLEGE_ID']; ?>">
                  <input class="form-control" type="hidden" name="ORDER_ID"  value="<?php echo $_REQUEST['ORDERID']; ?>">                  
                  <input class="form-control btn btn-success" type="submit" value="PAY NOW" style="display: none">
               </tbody>
            </table>
            <script type="text/javascript">
               document.paymentTxnStatus.submit();
            </script>
         </form>

		<?php

	}
	else{
		echo "<b>Order ID not received.</b>";
	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>