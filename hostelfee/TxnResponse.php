<?php 


function payment_success_exec($data) {
	
    $encrypted_message = '';

	if(isset($_REQUEST) && isset($data['Transaction_Amount']) && isset($data['Response_Code'])) {		
		
		// Create connection
		$con = new mysqli($servername, $username, $password, $dbname);		
			
        $ref_no = isset($data['ReferenceNo'])?$data['ReferenceNo']:'';
        $qry1 = "SELECT college_id FROM hostel_payment WHERE order_id like '%$ref_no%' AND record_status=1";
        $r = $con->query($qry1);	
        $res = $r->fetch_assoc();

        $qry2 = "SELECT merchant_key FROM `gateway_merchent_details` WHERE college='".$res['college_id']."' AND gateway='ICICI' AND status=1";
        $isRecords = $con->query($qry2);	
        $eazyPay = $isRecords->fetch_assoc();
 
        if(isset($eazyPay['merchant_key']) && isset($res['college_id'])){
            
            $aes_key_for_payment_success = $eazyPay['merchant_key'];
            // Same encryption key that we gave for generating the URL
            $SIGNATURE_VERIFICATION = $data['ID'].'|'.$data['Response_Code'].'|'.$data['Unique_Ref_Number'].'|'.
                $data['Service_Tax_Amount'].'|'.$data['Processing_Fee_Amount'].'|'.$data['Total_Amount'].'|'.
                $data['Transaction_Amount'].'|'.$data['Transaction_Date'].'|'.$data['Interchange_Value'].'|'.
                $data['TDR'].'|'.$data['Payment_Mode'].'|'.$data['SubMerchantId'].'|'.$data['ReferenceNo'].'|'.
                $data['TPS'].'|'.$aes_key_for_payment_success;

            $encrypted_message = hash('sha512', $SIGNATURE_VERIFICATION);
		
		}else{
            return false;
			exit;
        }		

		if($encrypted_message == $data['RS']) {

			$_SESSION['student']['payment'] = "";
			$_SESSION['student']['update'] = "";

			if(!empty($data['ReferenceNo'])){
				
				$ORDERID 		= $data['ReferenceNo'];
				$ORDER_ID 		= isset($data['ReferenceNo'])?"'".$data['ReferenceNo']."'":'';
				$MID 			= isset($data['ID'])?"'".$data['ID']."'":'';
				$PAYMENTMODE 	= isset($data['Payment_Mode'])?"'".$data['Payment_Mode']."'":''; 
				$TXNID			= isset($data['Unique_Ref_Number'])?"'".$data['Unique_Ref_Number']."'":'';
				$TXNAMOUNT		= isset($data['Transaction_Amount'])?"'".$data['Transaction_Amount']."'":'';
				$TXNDATE		= isset($data['Transaction_Date'])?"'".$data['Transaction_Date']."'":'';
				$RESPCODE  		= isset($data['Response_Code'])?"'".$data['Response_Code']."'":'';
				$BANKTXNID		= isset($data['Unique_Ref_Number'])?"'".$data['Unique_Ref_Number']."'":'';		
				
				$res_data = array(
					'ORDERID' => isset($data['ReferenceNo'])?$data['ReferenceNo']:'NULL',
					'MID' => isset($data['ID'])?$data['ID']:'NULL',
					'PAYMENTMODE' => isset($data['Payment_Mode'])?$data['Payment_Mode']:'NULL',
					'TXNID'=> isset($data['Unique_Ref_Number'])?$data['Unique_Ref_Number']:'NULL',
					'TXNAMOUNT'=> isset($data['Transaction_Amount'])?$data['Transaction_Amount']:'NULL',
					'RESPCODE'=> isset($data['Response_Code'])?$data['Response_Code']:'NULL',
					'BANKTXNID'=> isset($data['Unique_Ref_Number'])?$data['Unique_Ref_Number']:'NULL'
				);
				
				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				// Check connection
				if ($conn->connect_error) {
				  $update_message = "Connection failed. Unable to update payment details.";
				} else {
									
					$sql = "SELECT * FROM hostel_payment WHERE order_id like '%".$ORDERID."%' AND record_status=1";
					$result = $conn->query($sql);	
						
					if ($result->num_rows > 0) {	
						
						$sql = "UPDATE `hostel_payment` SET `txnid`=$TXNID, `banktxnid`=$BANKTXNID, `orderid`=$ORDER_ID, `txnamount`=$TXNAMOUNT, `respcode`=$RESPCODE, `mid`=$MID, `paymentmode`=$PAYMENTMODE, `txndate`=$TXNDATE WHERE `order_id` like '%".$ORDERID."%'";

						$result2 = $conn->query($sql);				
							
						if($result2){
							
							$payment = $result->fetch_assoc();						
							$this_college = $payment["college_id"];
							$roll_no = $payment["cust_id"];
							$mobile = $payment["mobile"];
							$_SESSION["MOBILE"] = $mobile;
	
							$col = "SELECT * FROM hostel_candidate WHERE `college_name` = '".$this_college."' AND (`hostel_code` = '".$roll_no."' OR `roll_no` = '".$roll_no."')";
							
							$college = $conn->query($col);	
	
							$row = $college->fetch_assoc();							
							
							$_SESSION["COLLEGE"] 	 = $row["college_name"];							
							$_SESSION["HOSTEL_CODE"] = $row["hostel_code"];
							$_SESSION["ROLL_NO"] 	 = $row["roll_no"];
							$_SESSION["NAME"] 		 = $row["name"];						
							$_SESSION["EMAIL"] 		 = $row["email"];
							$_SESSION["MONTHS"]      = $row["months"];
							
							$_SESSION['student']["payment"] = $res_data;
							$pay_query = "SELECT months FROM hostel_payment WHERE order_id like '%$ORDERID%' AND record_status=1";
							$pay_result = $conn->query($pay_query);		
							
							while($r = $pay_result->fetch_assoc()) {								
								$_SESSION["MONTHS"] = $r["months"];	
								echo $r["months"];
							}
							
							$array = explode(",",$_SESSION["MONTHS"]);
							
							if(str_replace("'", "", $RESPCODE)=='E000'){	
                                for($i=0;$i<=count($array);$i++){
                                    $sql2 = "UPDATE hostel_candidate SET `payment_status` = 1 WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `hostel_code` = '".$roll_no."') AND `month_year` = '".$array[$i]."' ";
                                    $result2 = $conn->query($sql2);
                                }
						    }					
	
						    $update_message = "Details updated successfully";
	
						}else{
	
							$update_message = "Error on update details";
						}
					} 
					else {
						$update_message = "Order ID not exist. Unable to update payment details.";
					}
	
					$conn->close();	
				}
	
				$_SESSION['hostel']['update'] = $update_message;
			}
			
			return true;

		} else {
			return false;
		}

	} else {
		return false;
	}
}

function response_code($code) {
	$rc=array('E000' =>'"Payment Successful."', 
	'E001' =>'Unauthorized Payment Mode', 
	'E002' =>'Unauthorized Key', 
	'E003' =>'Unauthorized Packet', 
	'E004' =>'Unauthorized Merchant', 
	'E005' =>'Unauthorized Return URL', 
	'E006' =>'"Transaction Already Paid, Received Confirmation from the Bank, Yet to Settle the transaction with the Bank', 
	'E007' =>'Transaction Failed', 
	'E008' =>'Failure from Third Party due to Technical Error', 
	'E009' =>'Bill Already Expired', 
	'E0031' =>'Mandatory fields coming from merchant are empty', 
	'E0032' =>'Mandatory fields coming from database are empty', 
	'E0033' =>'Payment mode coming from merchant is empty', 
	'E0034' =>'PG Reference number coming from merchant is empty', 
	'E0035' =>'Sub merchant id coming from merchant is empty', 
	'E0036' =>'Transaction amount coming from merchant is empty', 
	'E0037' =>'Payment mode coming from merchant is other than 0 to 9', 
	'E0038' =>'Transaction amount coming from merchant is more than 9 digit length', 
	'E0039' =>'Mandatory value Email in wrong format', 
	'E00310' =>'Mandatory value mobile number in wrong format', 
	'E00311' =>'Mandatory value amount in wrong format', 
	'E00312' =>'Mandatory value Pan card in wrong format', 
	'E00313' =>'Mandatory value Date in wrong format', 
	'E00314' =>'Mandatory value String in wrong format', 
	'E00315' =>'Optional value Email in wrong format', 
	'E00316' =>'Optional value mobile number in wrong format', 
	'E00317' =>'Optional value amount in wrong format', 
	'E00318' =>'Optional value pan card number in wrong format', 
	'E00319' =>'Optional value date in wrong format', 
	'E00320' =>'Optional value string in wrong format', 
	'E00321' =>'Request packet mandatory columns is not equal to mandatory columns set in enrolment or optional columns are not equal to optional columns length set in enrolment', 
	'E00322' =>'Reference Number Blank', 
	'E00323' =>'Mandatory Columns are Blank', 
	'E00324' =>'Merchant Reference Number and Mandatory Columns are Blank', 
	'E00325' =>'Merchant Reference Number Duplicate', 
	'E00326' =>'Sub merchant id coming from merchant is non numeric', 
	'E00327' =>'Cash Challan Generated', 
	'E00328' =>'Cheque Challan Generated', 
	'E00329' =>'NEFT Challan Generated', 
	'E00330' =>'Transaction Amount and Mandatory Transaction Amount mismatch in Request URL', 
	'E00331' =>'UPI Transaction Initiated Please Accept or Reject the Transaction', 
	'E00332' =>'Challan Already Generated, Please re-initiate with unique reference number', 
	'E00333' =>'Referer value is null / invalid Referer', 
	'E00334' =>'Value of Mandatory parameter Reference No and Request Reference No are not matched', 
	'E00335' =>'Payment has been cancelled',
	'E0801' =>'FAIL', 
	'E0802' =>'User Dropped', 
	'E0803' =>'Canceled by user', 
	'E0804' =>'User Request arrived but card brand not supported', 
	'E0805' =>'Checkout page rendered Card function not supported', 
	'E0806' =>'Forwarded / Exceeds withdrawal amount limit', 
	'E0807' =>'PG Fwd Fail / Issuer Authentication Server failure', 
	'E0808' =>'Session expiry / Failed Initiate Check, Card BIN not present', 
	'E0809' =>'Reversed / Expired Card', 
	'E0810' =>'Unable to Authorize', 
	'E0811' =>'Invalid Response Code or Guide received from Issuer', 
	'E0812' =>'Do not honor', 
	'E0813' =>'Invalid transaction', 
	'E0814' =>'Not Matched with the entered amount', 
	'E0815' =>'Not sufficient funds', 
	'E0816' =>'No Match with the card number', 
	'E0817' =>'General Error', 
	'E0818' =>'Suspected fraud', 
	'E0819' =>'User Inactive', 
	'E0820' =>'ECI 1 and ECI6 Error for Debit Cards and Credit Cards', 
	'E0821' =>'ECI 7 for Debit Cards and Credit Cards', 
	'E0822' =>'System error. Could not process transaction', 
	'E0823' =>'Invalid 3D Secure values', 
	'E0824' =>'Bad Track Data', 
	'E0825' =>'Transaction not permitted to cardholder', 
	'E0826' =>'Rupay timeout from issuing bank', 
	'E0827' =>'OCEAN for Debit Cards and Credit Cards', 
	'E0828' =>'E-commerce decline', 
	'E0829' =>'This transaction is already in process or already processed', 
	'E0830' =>'Issuer or switch is inoperative', 
	'E0831' =>'Exceeds withdrawal frequency limit', 
	'E0832' =>'Restricted card', 
	'E0833' =>'Lost card', 
	'E0834' =>'Communication Error with NPCI', 
	'E0835' =>'The order already exists in the database', 
	'E0836' =>'General Error Rejected by NPCI', 
	'E0837' =>'Invalid credit card number', 
	'E0838' =>'Invalid amount', 
	'E0839' =>'Duplicate Data Posted', 
	'E0840' =>'Format error', 
	'E0841' =>'SYSTEM ERROR', 
	'E0842' =>'Invalid expiration date', 
	'E0843' =>'Session expired for this transaction', 
	'E0844' =>'FRAUD - Purchase limit exceeded', 
	'E0845' =>'Verification decline', 
	'E0846' =>'Compliance error code for issuer', 
	'E0847' =>'Caught ERROR of type:[ System.Xml.XmlException ] . strXML is not a valid XML string', 
	'E0848' =>'Incorrect personal identification number', 
	'E0849' =>'Stolen card', 
	'E0850' =>'Transaction timed out, please retry', 
	'E0851' =>'Failed in Authorize - PE', 
	'E0852' =>'Cardholder did not return from Rupay', 
	'E0853' =>'Missing Mandatory Field(s)The field card_number has exceeded the maximum length of', 
	'E0854' =>'Exception in CheckEnrollmentStatus: Data at the root level is invalid. Line 1, position 1.', 
	'E0855' =>'CAF status = 0 or 9', 
	'E0856' =>'412', 
	'E0857' =>'Allowable number of PIN tries exceeded', 
	'E0858' =>'No such issuer', 
	'E0859' =>'Invalid Data Posted', 
	'E0860' =>'PREVIOUSLY AUTHORIZED', 
	'E0861' =>'Cardholder did not return from ACS', 
	'E0862' =>'Duplicate transmission', 
	'E0863' =>'Wrong transaction state', 
	'E0864' =>'Card acceptor contact acquirer');
    return $rc[$code];
}

?>

<html>

<head>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <title>Online Payment</title>
</head>

<body>
    <div class="container">
        <?php 

		session_start();
		$data = array();

		if(!empty($_REQUEST)):
			//$data = unserialize(base64_decode($_REQUEST['TxnResponse']));
			$data = $_REQUEST;			
		else:
			echo 'Invalid Access or Invalid Parameters..';
		endif;

		 if(payment_success_exec($data)): ?>
        <?php 
			$response = response_code($data['Response_Code']);
			header('Location:payment_status.php?status_code='.$response);
		?>
        <?php else: ?>
        <?php 
			$response = response_code($data['Response_Code']);
			if(!empty($data['ReferenceNo'])){
				$ORDERID 		= $data['ReferenceNo'];
				$ORDER_ID 		= isset($data['ReferenceNo'])?"'".$data['ReferenceNo']."'":'';
				$MID 			= isset($data['ID'])?"'".$data['ID']."'":'';
				$RESPCODE  		= isset($data['Response_Code'])?"'".$data['Response_Code']."'":'';

				// Create connection
				$conn = new mysqli("localhost","root","","mahendra_org");
				// Check connection
				if ($conn->connect_error) {
					echo "Connection failed. Unable to update payment details.";
				} else {

					$sql = "SELECT * FROM hostel_payment WHERE order_id like '%".$ORDERID."%' AND record_status=1";
					$result = $conn->query($sql);									

					if ($result->num_rows > 0) {	
						$sql = "UPDATE `hostel_payment` SET `orderid`=$ORDER_ID, `respcode`=$RESPCODE, `mid`=$MID, `status`='$response' WHERE `order_id` like '%".$ORDERID."%'";
						$result2 = $conn->query($sql);
					}
				}	
			}

			echo "<div style='margin-top:100px;text-align:center; width: 100%;font-size:20px;'><p>Transaction failed due to some reason. Please check with your bank if amount has been deducted.</p> 
			<p>Sorry for the inconvenience caused.</p>";
			echo "<p>Your bank has returned the following error message: <b>".$response."</b></p>";
			echo "<p> Click <a href='https://mahendra.org/online_payment/hostelfee/'><i class='fa fa-home'></i> here</a> to return to the home page</p></div>";
		?>
        <?php endif; ?>
    </div>
</body>

</html>