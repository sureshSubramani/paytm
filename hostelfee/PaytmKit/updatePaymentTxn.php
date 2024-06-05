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
	
	if(isset($_REQUEST["ORDER_ID"]) && $_REQUEST["ORDER_ID"] != ""){

		$ORDER_ID = $_REQUEST["ORDER_ID"];

		// Create an array having all required parameters for status query.
		$requestParamList = array("MID" => PAYTM_MERCHANT_MID , "ORDERID" => $ORDER_ID);  
		
		$StatusCheckSum = getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY);
		
		$requestParamList['CHECKSUMHASH'] = $StatusCheckSum;

		// Call the PG's getTxnStatusNew() function for verifying the transaction status.
		$responseParamList = getTxnStatusNew($requestParamList);

		$error 	= 1;
		$data	= array();

		if(isset($responseParamList) && count($responseParamList)>0 ){ 

			$TXNID 			= isset($responseParamList["TXNID"])?"'".$responseParamList["TXNID"]."'":"NULL";
			$BANKTXNID  	= isset($responseParamList["BANKTXNID"])?"'".$responseParamList["BANKTXNID"]."'":"NULL";
			$ORDERID 		= isset($responseParamList["ORDERID"])?"'".$responseParamList["ORDERID"]."'":"NULL";
			$TXNAMOUNT  	= isset($responseParamList["TXNAMOUNT"])?"'".$responseParamList["TXNAMOUNT"]."'":"NULL";
			$STATUS 		= isset($responseParamList["STATUS"])?"'".$responseParamList["STATUS"]."'":"NULL";
			$TXNTYPE 		= isset($responseParamList["TXNTYPE"])?"'".$responseParamList["TXNTYPE"]."'":"NULL";
			$GATEWAYNAME 	= isset($responseParamList["GATEWAYNAME"])?"'".$responseParamList["GATEWAYNAME"]."'":"NULL";
			$RESPCODE 		= isset($responseParamList["RESPCODE"])?"'".$responseParamList["RESPCODE"]."'":"NULL";
			$RESPMSG 		= isset($responseParamList["RESPMSG"])?"'".$responseParamList["RESPMSG"]."'":"NULL";
			$BANKNAME 		= isset($responseParamList["BANKNAME"])?"'".$responseParamList["BANKNAME"]."'":"NULL";
			$MID 			= isset($responseParamList["MID"])?"'".$responseParamList["MID"]."'":"NULL";
			$PAYMENTMODE 	= isset($responseParamList["PAYMENTMODE"])?"'".$responseParamList["PAYMENTMODE"]."'":"NULL";
			$REFUNDAMT 		= isset($responseParamList["REFUNDAMT"])?"'".$responseParamList["REFUNDAMT"]."'":"NULL";
			$TXNDATE 		= isset($responseParamList["TXNDATE"])?"'".$responseParamList["TXNDATE"]."'":"NULL";

			$_SESSION['hostel']['payment'] = $responseParamList;

			// live host db authentications
			
			$SERVER     = "localhost";
			$USERNAME   = "mahendrg_mahendr";
			$PASSWORD   = "Kvuglbmp9e4;";
			$DBNAME     = "mahendrg_mahendra";

			// Create connection
			$conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DBNAME);
			// Check connection
			if($conn->connect_error){
			  $update_message = "Connection failed. Unable to update payment details.";
			}else{
		
				$sql = "SELECT * FROM hostel_payment WHERE order_id='$ORDER_ID' AND record_status=1";		
				
				$result = $conn->query($sql);	

				if ($result->num_rows > 0) {

					$sql = "UPDATE `hostel_payment` SET `txnid`=$TXNID, `banktxnid`=$BANKTXNID, `orderid`=$ORDERID, `txnamount`=$TXNAMOUNT, `status`=$STATUS, `txntype`=$TXNTYPE, `gatewayname`=$GATEWAYNAME, `respcode`=$RESPCODE, `respmsg`=$RESPMSG, `bankname`=$BANKNAME, `mid`=$MID, `paymentmode`=$PAYMENTMODE, `refundamt`=$REFUNDAMT, `txndate`=$TXNDATE WHERE `order_id` = '".$ORDER_ID."'";	

					$result2 = $conn->query($sql);

					if($result2){

						$error = 0;
						
						$payment       	= $result->fetch_assoc();						
						$this_college  	= $payment["college_id"];
						$roll_no		= $payment["cust_id"];

						$col = "SELECT * FROM hostel_candidate WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `hostel_code` = '".$roll_no."')";;
						
						$college = $conn->query($col);											

						$pay_query = "SELECT months FROM hostel_payment WHERE order_id='$ORDER_ID' AND record_status=1";	

						$pay_result = $conn->query($pay_query);
						while($row = $pay_result->fetch_assoc()) {							
						     $data["months"] = $row["months"];							
						}

						while($row = $college->fetch_assoc()) {
							$data['info'] = array(
								'COLLEGE'		=>	$row["college_name"],
								'HOSTEL_CODE' 	=>	$row["hostel_code"],
								'ROLL_NO'		=>	$row["roll_no"],
								'NAME'			=>	$row["name"],
								'MOBILE'		=>	$row['mobile'],
								'EMAIL'			=>	$row["email"],
								'MONTHS'		=>	$data["months"]
							);						
						}

						$arrayinmonths = explode(",",$data["months"]);

						if(str_replace("'", "", $RESPCODE)=='01'){	
							for($i=0;$i<count($arrayinmonths);$i++){

							$sql2 = "UPDATE hostel_candidate SET `payment_status` = 1 WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `hostel_code` = '".$roll_no."') AND `month_year` = '".$arrayinmonths[$i]."' ";
							$result2 = $conn->query($sql2);

							}
						}

						$data['payments'] = $responseParamList;
						$data = array_merge($data['info'],$data['payments']);

						$update_message = "Details updated successfully..";

					}else{

						$update_message = "Error on update details..";
					}

				}else {
					$update_message = "Order ID not exist. Unable to update payment details..";
				}

				$conn->close();
			}

		}else{
			$update_message = "Payment not found..";
		}

	}else{
		$update_message = "Order ID missing..";
	}

	echo json_encode(array('error'=>$error,'message'=>$update_message,'data'=>$data));
?>
