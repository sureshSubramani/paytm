<?php include 'config.php';

date_default_timezone_set('Asia/Kolkata');

try {				 

	$yesterday_date = date('Y-m-d', strtotime("-1 day")); 

    $query = "SELECT * FROM hostel_payment p LEFT JOIN hostel_candidate h ON (h.hostel_code = p.cust_id OR h.roll_no = p.cust_id) WHERE  p.respcode = 01 AND DATE(p.created_on) = '".$yesterday_date."' GROUP BY h.roll_no ORDER BY p.created_on desc";	

	$data = mysqli_query($con,$query);
		
	$num_of_rows = mysqli_num_rows($data);

	if(!empty($num_of_rows)){

		for($i=0; $i<=$num_of_rows;$i++){

			$count = 1;	

			while($row = $data->fetch_array(MYSQLI_ASSOC)) {				
				
				$reData[$i++] = array('s_no'=>$count++,'college_name'=>$row['college_name'], 'dep_name'=>$row['dep_name'], 'hostel_code'=>$row['hostel_code'],'roll_no'=>$row['roll_no'],'name'=>$row['name'],'f_name'=>$row['f_name'],'mobile'=>$row['mobile'],'payment_status'=>$row['payment_status'],'order_id'=>$row['order_id'],'cust_id'=>$row['cust_id'],'txnid'=>$row['txnid'],'banktxnid'=>$row['banktxnid'],'txnamount'=>$row['txnamount'],'status'=>$row['status'],'txntype'=>$row['txntype'],'gatewayname'=>$row['gatewayname'],'respcode'=>$row['respcode'],'respmsg'=>$row['respmsg'],
				'bankname'=>$row['bankname'],'mid'=>$row['mid'],'paymentmode'=>$row['paymentmode'],'refundamt'=>$row['refundamt'],'txndate'=>$row['txndate'], 'created_on'=>$row['created_on'],'record_status'=>$row['record_status']
			    );
 
			}
		}

	}

	if(!empty($num_of_rows)){

			// Open temp file open-pointer
			if (!$output = fopen('php://temp', 'w+')) return FALSE;
	
			fputcsv($output, array('S_No', 'College Name', 'Department Name', 'Hostel Code', 'Role Number', 'Name', 'Father Name', 'Mobile No', 'Payment Status', 'Order ID', 'Custom ID', 'Transaction ID', 'Bank TXN ID', 'Transaction Amount', 'Transaction Status', 'Transaction Type', 'Name of Bank Gateway', 'Response Code', 'Response MSG', 'Bank Name', 'Mahendra ID', 'Payment Mode', 'Refundable','Transaction Date', 'Record Status'));
	
		    //Loop data get and write to csv file pointer
			for($i=0; $i<$num_of_rows;$i++){ 

				fputcsv($output,$reData[$i]);					
				
			}


			// Place stream pointer at beginning
			rewind($output);

			//assign the data as csv format
			$csvData = stream_get_contents($output);	 
		
			require_once("classes/class.phpmailer.php"); // include the class name

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
			
			$subject = "Mahendra Hostel Mess Fees Payments On ".date('d-m-Y', strtotime("-1 days"));
			$messageBody = "<html>
			<body>
			<h3>Greetings,</h3>
			<h4>Mahendra Hostel Mess Fees Payment ".date('Y')." <span>(Applications) On ".date('d-m-Y', strtotime("-1 days"))."</span>.</h4> 				
			</body>
			</html>";

			$mail->Username = "mahendraeducation@gmail.com";
			$mail->Password = "blswinwjxexdqmvg";
			$mail->SetFrom("mahendraeducation@gmail.com");
			
			$filename = "Mahendra_Hostel_Mess_Fees_Payments_".date('Y-m-d').".csv";

			$mail->AddStringAttachment($csvData, $filename, 'base64', 'application/x-csv');
			$mail->Subject = $subject;
			$mail->Body = $messageBody;
			//$mail->AddAddress('prasanna@mahendra.org', 'Mahendra Admissions');
			//$mail->AddAddress('kannan@mahendra.org', 'Mahendra Admissions');
			//$mail->AddAddress('ajay@mahendra.org', 'Mahendra Admissions');

			//$mail->AddAddress('ragunathan.c@mnxw.org', 'Mahendra Admission');
			//$mail->AddAddress('mahendragunaa@gmail.com', 'Mahendra Admission');
			$mail->AddAddress('ramesh@mahendra.org', 'Mahendra Admission');
			//$mail->AddCC($row1['other_email']);

			if(!$mail->Send()){
				echo "Mailer Error: " . $mail->ErrorInfo;
			}else{
				echo "Message has been sent successfully";
			}
		}
	}catch(Exception $e){
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


?>