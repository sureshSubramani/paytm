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
	    //echo $_REQUEST["ORDER_ID"]; exit;
		$_SESSION['student']['payment'] = "";
		$_SESSION['student']['update'] = "";
		// In Test Page, we are taking parameters from POST request. In actual implementation these can be collected from session or DB. 
		$ORDER_ID = $_REQUEST["ORDER_ID"];

		//echo $ORDER_ID; die;

		// Create an array having all required parameters for status query.
		$requestParamList = array("MID" => PAYTM_MERCHANT_MID , "ORDERID" => $ORDER_ID);  
		
		$StatusCheckSum   = getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY);
		
		$requestParamList['CHECKSUMHASH'] = $StatusCheckSum;

		// Call the PG's getTxnStatusNew() function for verifying the transaction status.
		$responseParamList = getTxnStatusNew($requestParamList);

        $error 	= 1;
		$data	= array();
		$sum 	= 0;

		if (isset($responseParamList) && count($responseParamList)>0 ){ 
			/*echo "<pre>";
			print_r($responseParamList);
			echo "</pre>"; 
            exit;*/

			$TXNID          = isset($responseParamList["TXNID"])?"'".$responseParamList["TXNID"]."'":"NULL";
			$BANKTXNID      = isset($responseParamList["BANKTXNID"])?"'".$responseParamList["BANKTXNID"]."'":"NULL";
			$ORDERID        = isset($responseParamList["ORDERID"])?"'".$responseParamList["ORDERID"]."'":"NULL";
			$TXNAMOUNT      = isset($responseParamList["TXNAMOUNT"])?"'".$responseParamList["TXNAMOUNT"]."'":"NULL";
			$STATUS         = isset($responseParamList["STATUS"])?"'".$responseParamList["STATUS"]."'":"NULL";
			$TXNTYPE        = isset($responseParamList["TXNTYPE"])?"'".$responseParamList["TXNTYPE"]."'":"NULL";
			$GATEWAYNAME    = isset($responseParamList["GATEWAYNAME"])?"'".$responseParamList["GATEWAYNAME"]."'":"NULL";
			$RESPCODE       = isset($responseParamList["RESPCODE"])?"'".$responseParamList["RESPCODE"]."'":"NULL";
			$RESPMSG        = isset($responseParamList["RESPMSG"])?"'".$responseParamList["RESPMSG"]."'":"NULL";
			$BANKNAME       = isset($responseParamList["BANKNAME"])?"'".$responseParamList["BANKNAME"]."'":"NULL";
			$MID            = isset($responseParamList["MID"])?"'".$responseParamList["MID"]."'":"NULL";
			$PAYMENTMODE    = isset($responseParamList["PAYMENTMODE"])?"'".$responseParamList["PAYMENTMODE"]."'":"NULL";
			$REFUNDAMT      = isset($responseParamList["REFUNDAMT"])?"'".$responseParamList["REFUNDAMT"]."'":"NULL";
			$TXNDATE        = isset($responseParamList["TXNDATE"])?"'".$responseParamList["TXNDATE"]."'":"NULL";

			//$_SESSION['student']['payment'] = $responseParamList;
			
			// live host login authentications
			$servername = "localhost";
			$username   = "mahendrg_mahendr";
			$password   = "Kvuglbmp9e4;";
			$dbname     = "mahendrg_mahendra";
			
			// Create connection
			$con = new mysqli($servername, $username, $password, $dbname);
			// Check connection

			if ($con->connect_error) {
			  $update_message = "Connection failed. Unable to update payment details.";
			}else{

				$sql = "SELECT * FROM out_student_payments WHERE order_id like '%".$ORDER_ID."%' AND record_status=1";				
				$result = $con->query($sql);									

				if ($result->num_rows > 0) {

					$sql = "UPDATE `out_student_payments` SET `txnid`=$TXNID, `banktxnid`=$BANKTXNID, `orderid`=$ORDERID, `txnamount`=$TXNAMOUNT, `status`=$STATUS, `txntype`=$TXNTYPE, `gatewayname`=$GATEWAYNAME, `respcode`=$RESPCODE, `respmsg`=$RESPMSG, `bankname`=$BANKNAME, `mid`=$MID, `paymentmode`=$PAYMENTMODE, `refundamt`=$REFUNDAMT, `txndate`=$TXNDATE WHERE `order_id` like '%".$ORDER_ID."%'";						

					$result2 = $con->query($sql);

					if($result2){

						$error = 0;
						
						$payment            = $result->fetch_assoc();						
						$this_college       = $payment["college"];
						$roll_no            = $payment["cust_id"];
						$mobile             = $payment["mobile"];						

						$col = "SELECT * FROM out_student_details WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `reg_no` = '".$roll_no."')";
						
						$college = $con->query($col);
						$row 	 = $college->fetch_assoc();

						$data['info'] = array(
							'COLLEGE'	=>	$row["college_name"],
							'REG_NO' 	=>	$row["college_name"],
							'ROLL_NO'	=>	$row["roll_no"],
							'NAME'		=>	$row["name"],
							'EMAIL'		=>	$row["email"]
						);

						if(str_replace("'", "", $RESPCODE)=='01'){					
							
							$sql2 = "UPDATE out_student_details SET `amount` = $TXNAMOUNT, `payment_status` = 1 WHERE `reg_no` = '".$roll_no."' OR `roll_no` = '".$roll_no."' OR `name` = '".$roll_no."' AND status=1";
							$result2 = $conn->query($sql2);

						}

						$data['fee'] = $responseParamList;
						$data = array_merge($data['info'],$data['fee']);
						$update_message = "Details updated successfully";
					}
					else{

						$update_message = "Error on update details";
					}
				} 
				else {
					$update_message = "Order ID not exist. Unable to update payment details.";
				}
				$con->close();
			}

		}else{
            $update_message = "Payment not found";
		}
		
	}else{
		$update_message = "Order ID is missing..";
	}

    echo json_encode(array('error'=>$error,'message'=>$update_message,'data'=>$data));
?>
