<?php
	if(session_id() == '') {
	    session_start();
	}
	header("Pragma: no-cache");
	header("Cache-Control: no-cache");
	header("Expires: 0");

	// following files need to be included
	require_once("./lib/config_paytm.php");
	require_once("./lib/encdec_paytm.php");

	$ORDER_ID = "";
	$requestParamList = array();
	$responseParamList = array();

	
	if (isset($_REQUEST["ORDER_ID"]) && $_REQUEST["ORDER_ID"] != "") {

		$_SESSION['hostel']['payment'] = "";
		$_SESSION['hostel']['update'] = "";
		// In Test Page, we are taking parameters from POST request. In actual implementation these can be collected from session or DB. 
		$ORDER_ID = $_REQUEST["ORDER_ID"];

		// Create an array having all required parameters for status query.
		$requestParamList = array("MID" => PAYTM_MERCHANT_MID , "ORDERID" => $ORDER_ID);  
		
		$StatusCheckSum = getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY);
		
		$requestParamList['CHECKSUMHASH'] = $StatusCheckSum;

		// Call the PG's getTxnStatusNew() function for verifying the transaction status.
		$responseParamList = getTxnStatusNew($requestParamList);

		if (isset($responseParamList) && count($responseParamList)>0 )
		{ 
			/*echo "<pre>";
			print_r($responseParamList);
			echo "</pre>"; exit;*/

			$TXNID = isset($responseParamList["TXNID"])?"'".$responseParamList["TXNID"]."'":"NULL";
			$BANKTXNID = isset($responseParamList["BANKTXNID"])?"'".$responseParamList["BANKTXNID"]."'":"NULL";
			$ORDERID = isset($responseParamList["ORDERID"])?"'".$responseParamList["ORDERID"]."'":"NULL";
			$TXNAMOUNT = isset($responseParamList["TXNAMOUNT"])?"'".$responseParamList["TXNAMOUNT"]."'":"NULL";
			$STATUS = isset($responseParamList["STATUS"])?"'".$responseParamList["STATUS"]."'":"NULL";
			$TXNTYPE = isset($responseParamList["TXNTYPE"])?"'".$responseParamList["TXNTYPE"]."'":"NULL";
			$GATEWAYNAME = isset($responseParamList["GATEWAYNAME"])?"'".$responseParamList["GATEWAYNAME"]."'":"NULL";
			$RESPCODE = isset($responseParamList["RESPCODE"])?"'".$responseParamList["RESPCODE"]."'":"NULL";
			$RESPMSG = isset($responseParamList["RESPMSG"])?"'".$responseParamList["RESPMSG"]."'":"NULL";
			$BANKNAME = isset($responseParamList["BANKNAME"])?"'".$responseParamList["BANKNAME"]."'":"NULL";
			$MID = isset($responseParamList["MID"])?"'".$responseParamList["MID"]."'":"NULL";
			$PAYMENTMODE = isset($responseParamList["PAYMENTMODE"])?"'".$responseParamList["PAYMENTMODE"]."'":"NULL";
			$REFUNDAMT = isset($responseParamList["REFUNDAMT"])?"'".$responseParamList["REFUNDAMT"]."'":"NULL";
			$TXNDATE = isset($responseParamList["TXNDATE"])?"'".$responseParamList["TXNDATE"]."'":"NULL";

			//echo (str_replace("'", "", $RESPCODE)=='01'); exit;

			$_SESSION['hostel']['payment'] = $responseParamList;
			$SERVER 	= "localhost";
			$USERNAME 	= "mahendrg_mahendr";
			$PASSWORD 	= "Kvuglbmp9e4;";
			$DBNAME 	= "mahendrg_mahendra";
			
			// Create connection
			$conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DBNAME);
			//$conn = new mysqli("localhost", "mahendrg_mahendr", "Kvuglbmp9e4;", "mahendrg_mahendra");
			// Check connection
			if ($conn->connect_error) {
			  $update_message = "Connection failed. Unable to update payment details.";
			}else{
		
				$sql = "SELECT * FROM hostel_payment WHERE order_id='$ORDER_ID' AND record_status=1";		
				
				$result = $conn->query($sql);	

				if ($result->num_rows > 0) {

					$sql = "UPDATE `hostel_payment` SET `txnid`=$TXNID, `banktxnid`=$BANKTXNID, `orderid`=$ORDERID, `txnamount`=$TXNAMOUNT, `status`=$STATUS, `txntype`=$TXNTYPE, `gatewayname`=$GATEWAYNAME, `respcode`=$RESPCODE, `respmsg`=$RESPMSG, `bankname`=$BANKNAME, `mid`=$MID, `paymentmode`=$PAYMENTMODE, `refundamt`=$REFUNDAMT, `txndate`=$TXNDATE WHERE `order_id` = '".$ORDER_ID."'";	

					$result2 = $conn->query($sql);

					if($result2){
						
						$payment = $result->fetch_assoc();						
						$this_college = $payment["college_id"];
						$roll_no = $payment["cust_id"];

						$col = "SELECT * FROM hostel_candidate WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `hostel_code` = '".$roll_no."')";;
						
						$college = $conn->query($col);	

						while($row = $college->fetch_assoc()) {
							$_SESSION["COLLEGE"] = $row["college_name"];							
							$_SESSION["HOSTEL_CODE"] = $row["hostel_code"];
							$_SESSION["ROLL_NO"] = $row["roll_no"];
							$_SESSION["NAME"] = $row["name"];
							$_SESSION["MOBILE"] = $row["mobile"];
							$_SESSION["EMAIL"] = $row["email"];				
						}

						$pay_query = "SELECT months FROM hostel_payment WHERE order_id='$ORDER_ID' AND record_status=1";	

						$pay_result = $conn->query($pay_query);
						while($row = $pay_result->fetch_assoc()) {							
							$_SESSION["MONTHS"] = $row["months"];						
						}

						$array = explode(",",$_SESSION["MONTHS"]);

						if(str_replace("'", "", $RESPCODE)=='01'){	
							for($i=0;$i<=count($array);$i++){
							$sql2 = "UPDATE hostel_candidate SET `payment_status` = 1 WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `hostel_code` = '".$roll_no."') AND `month_year` = '".$array[$i]."' ";
							$result2 = $conn->query($sql2);
							}
						}

						$update_message = "Details updated successfully";
					}else{
						$update_message = "Error on update details";
					}
				}else{
					$update_message = "Order ID not exist. Unable to update payment details.";
				}

				$conn->close();

			}

			$_SESSION['hostel']['update'] = $update_message;

			/*?>
			<h2><?php echo $message; ?></h2>
			<table style="border: 1px solid nopadding" border="0">
				<tbody>
					<?php
					foreach($responseParamList as $paramName => $paramValue) { ?>
						<tr >
							<td style="border: 1px solid"><label><?php echo $paramName?></label></td>
							<td style="border: 1px solid"><?php echo $paramValue?></td>
						</tr>
						<?php
					} ?>
				</tbody>
			</table>
			<?php */
		}else{
			$_SESSION['hostel']['payment'] = "Payment not found</h2>";
		}
		
		header("Location:../payment_status.php");
	}else{
		header("Location:../index.php");
	}
?>

<?php /* <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Transaction status query</title>
<meta name="GENERATOR" content="Evrsoft First Page">
</head>
<body>
	<h2>Transaction status query</h2>
	<form method="post" action="">
		<table border="1">
			<tbody>
				<tr>
					<td><label>ORDER_ID::*</label></td>
					<td><input id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="<?php echo $ORDER_ID ?>">
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input value="Status Query" type="submit"	onclick=""></td>
				</tr>
			</tbody>
		</table>
		<br/></br/>		
	</form>
</body>
</html> */ ?>