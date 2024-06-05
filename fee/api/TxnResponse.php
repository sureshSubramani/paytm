<?php 

$data = unserialize(base64_decode($_REQUEST['TxnResponse']));

/*echo '<pre>';
print_r($data);
die;*/

function payment_success_exec($data) {

	if(isset($_REQUEST) && isset($data['Transaction_Amount']) && isset($data['Response_Code'])) {
		
		// Same encryption key that we gave for generating the URL
		$aes_key_for_payment_success = '1311141514701518';

		$SIGNATURE_VERIFICATION = $data['ID'].'|'.$data['Response_Code'].'|'.$data['Unique_Ref_Number'].'|'.
				$data['Service_Tax_Amount'].'|'.$data['Processing_Fee_Amount'].'|'.$data['Total_Amount'].'|'.
				$data['Transaction_Amount'].'|'.$data['Transaction_Date'].'|'.$data['Interchange_Value'].'|'.
				$data['TDR'].'|'.$data['Payment_Mode'].'|'.$data['SubMerchantId'].'|'.$data['ReferenceNo'].'|'.
				$data['TPS'].'|'.$aes_key_for_payment_success;

		$encrypted_message = hash('sha512', $SIGNATURE_VERIFICATION);

		if($encrypted_message == $data['RS']) {

			$res_data['student']['payment'] = array();
			$res_data['student']['update'] = array();

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

				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "mahendra_org";
				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				// Check connection
				if ($conn->connect_error) {
				  $update_message = "Connection failed. Unable to update payment details.";
				} else {
									
					$sql = "SELECT * FROM student_payment WHERE order_id like '%".$ORDERID."%' AND record_status=1";
					$result = $conn->query($sql);									
	
					if ($result->num_rows > 0) {	
						
						$sql = "UPDATE `student_payment` SET `txnid`=$TXNID, `banktxnid`=$BANKTXNID, `orderid`=$ORDER_ID, `txnamount`=$TXNAMOUNT, `respcode`=$RESPCODE, `mid`=$MID, `paymentmode`=$PAYMENTMODE, `txndate`=$TXNDATE WHERE `order_id` like '%".$ORDERID."%'";

						$result2 = $conn->query($sql);
	
						if($result2){
							
							$payment = $result->fetch_assoc();						
							$this_college = $payment["college_id"];
							$roll_no = $payment["cust_id"];
							$mobile = $payment["mobile"];
							$_SESSION["MOBILE"] = $mobile;
	
							$col = "SELECT * FROM student_details WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `reg_no` = '".$roll_no."')";
							
							$college = $conn->query($col);	
	
							$row = $college->fetch_assoc();
	
							$res_data["COLLEGE"] 	= $row["college_name"];							
							$res_data["REG_NO"] 	= $row["reg_no"];
							$res_data["ROLL_NO"] 	= $row["roll_no"];
							$res_data["NAME"] 		= $row["name"];						
							$res_data["EMAIL"] 		= $row["email"];
							
							$res_data['student']["payment"] = $res_data;

							if(empty($row["amount"])){
								$amount = 0;
							}else{
								$amount = $row["amount"];
							}
	
							$sum = $amount + $data['Total_Amount'];		
							
							if(str_replace("'", "", $RESPCODE)=='E000'){					
	
								$sql2 = "UPDATE student_details SET `amount` = $sum, `payment_status` = 1 WHERE `college_name` = '".$this_college."' AND (`roll_no` = '".$roll_no."' OR `reg_no` = '".$roll_no."')";
								$result2 = $conn->query($sql2);	
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
	
				$res_data['student']['update'] = $update_message;
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

 if(payment_success_exec($data)): 
	echo json_encode($data);
 else: 
	$response = response_code($data['Response_Code']);
	echo json_encode(array('message'=>$response));
 endif; 
 
 ?>
