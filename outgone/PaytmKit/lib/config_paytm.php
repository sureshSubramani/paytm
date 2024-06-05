<?php
/*
- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.
*/

if(isset($_REQUEST['COLLEGE_ID']) && $_REQUEST['COLLEGE_ID']!=""){
	
	$merchant_key = $merchant_id = "";
	
	// Create connection
	$con = new mysqli("localhost", "mahendrg_mahendr", "Kvuglbmp9e4;", "mahendrg_mahendra");
	// Check connection
	if ($con->connect_error) {
	  $update_message = '<h3 style="margin: 5% auto; text-align: center; color: #e80a0a">Connection failed. Unable to get merchant details.</h3>'; 
	  exit;
	}
	else{

		$sql = "SELECT * FROM `gateway_merchent_details` WHERE college='".$_REQUEST['COLLEGE_ID']."'  AND gateway='PAYTM' AND status=1";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$val = $result->fetch_assoc();
			if(!empty($val)){
				$merchant_key = (isset($val['merchant_key']) && $val['merchant_key'])?$val['merchant_key']:"";
				$merchant_id = (isset($val['merchant_id']) && $val['merchant_id'])?$val['merchant_id']:"";
			}
		}

		$con->close();

	}

	if($merchant_key && $merchant_id){

		define('PAYTM_ENVIRONMENT', 'PROD'); // PROD
		define('PAYTM_MERCHANT_KEY', $merchant_key); //Change this constant's value with Merchant key received from Paytm.
		define('PAYTM_MERCHANT_MID', $merchant_id); //Change this constant's value with MID (Merchant ID) received from Paytm.		
		define('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING'); //Change this constant's value with Website name received from Paytm. for WEB:DIYtestingweb / for WAP:DIYtestingwap
		define('INDUSTRY_TYPE_ID', 'Retail'); // Retail
		define('CHANNEL_ID', 'WEB'); // WEB/ WAP

		$PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/order/status';
		$PAYTM_TXN_URL='https://securegw-stage.paytm.in/order/process';

		if(PAYTM_ENVIRONMENT == 'PROD') {
			$PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
			$PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
		}

		define('PAYTM_REFUND_URL','');
		define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
		define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
		define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
	}
	else{
		echo '<h3 style="margin: 5% auto; text-align: center; color: #e80a0a">KEY & MID is Not found for '.$_REQUEST['COLLEGE_ID'].'</h3>'; exit;
	}

}else{
	echo '<h3 style="margin: 5% auto; text-align: center; color: #e80a0a">Required value missing!</h3>'; exit;
}

?>
