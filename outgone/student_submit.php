<?php

$servername = "localhost";
$username 	= "mahendrg_mahendr";
$password 	= "Kvuglbmp9e4;";
$dbname 	= "mahendrg_mahendra";

$error = 1;
$message = "";

/*echo '<pre>';
print_r($_REQUEST);
die;*/

if(!empty($_REQUEST)){

		$reg_no 		= isset($_REQUEST['reg_no'])?$_REQUEST['reg_no']:"";
		$role_no 		= isset($_REQUEST['role_no'])?$_REQUEST['role_no']:"";
		$name 			= isset($_REQUEST['name'])?$_REQUEST['name']:"";
		$fname 			= isset($_REQUEST['fname'])?$_REQUEST['fname']:"";
		$dob 			= isset($_REQUEST['dob'])?$_REQUEST['dob']:"";
		$gender 		= isset($_REQUEST['gender'])?$_REQUEST['gender']:"";
		$email 			= isset($_REQUEST['email'])?$_REQUEST['email']:"";
		$college_id 	= isset($_REQUEST['college'])?$_REQUEST['college']:"";
		$department 	= isset($_REQUEST['department'])?$_REQUEST['department']:"";
		$out_year 		= isset($_REQUEST['out_year'])?$_REQUEST['out_year']:"";
		$fee_type 		= isset($_REQUEST['fee_type'])?$_REQUEST['fee_type']:"";		
		$mobile 		= isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
		$amount 		= isset($_REQUEST['amount'])?$_REQUEST['amount']:"";
		$gateway 		= isset($_REQUEST['gateway'])?$_REQUEST['gateway']:"";
		$agree_terms 	= isset($_REQUEST['agree_terms'])?$_REQUEST['agree_terms']:"";
		$cust_id 		= '';

		if($fee_type=='fee')
			$order_id = 'OF'.date('Ymdhis');

		if($fee_type=='exam')
			$order_id = 'OE'.date('Ymdhis');

		if(!empty($reg_no) && empty($cust_id))
			$cust_id = isset($_REQUEST['reg_no'])?$_REQUEST['reg_no']:"";

		if(!empty($role_no) && empty($cust_id))
			$cust_id = isset($_REQUEST['role_no'])?$_REQUEST['role_no']:"";

		if(!empty($name) && empty($cust_id))
			$cust_id = isset($_REQUEST['name'])?$_REQUEST['name']:"";
		

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		  $message = "Connection failed";
		}else{

			$osd = "SELECT * FROM out_student_details WHERE (reg_no='".$reg_no."') OR (roll_no='".$reg_no."') AND status=1 LIMIT 0,1";
			$isExist = $conn->query($osd);

			
			
			if($isExist->num_rows > 0){
				//Update Data
				$osd = "UPDATE out_student_details SET `roll_no`= '$role_no', `name`='$name', `f_name`='$fname', `mobile`='$mobile', `dob`='$dob', `gender`='$gender', `email`='$email', `college_name`='$college_id', `dep_name`='$department',`outgone_year`='$out_year', `fee_type`='$fee_type',`agree_terms`='$agree_terms' WHERE reg_no='".$reg_no."'";
				$conn->query($osd);
			}else{
				//Insert Data
				$osd = "INSERT INTO `out_student_details`(`reg_no`, `roll_no`, `name`, `f_name`, `mobile`, `dob`, `gender`, `email`, `college_name`, `dep_name`,`outgone_year`, `fee_type`, `amount`,`agree_terms`) VALUES ('$reg_no','$role_no','$name','$fname','$mobile','$dob','$gender','$email','$college_id','$department','$out_year','$fee_type','$amount','$agree_terms')";
				$conn->query($osd);
			}			

			$sql = "SELECT * FROM out_student_payments WHERE order_id like '%".$order_id."%' AND record_status=1";
			$result = $conn->query($sql);
		
			if ($result->num_rows > 0) {
				$message = "Order ID exist. Try again!";
			}else{
				$sql = "INSERT INTO `out_student_payments`(`order_id`, `college`, `cust_id`,`name`, `dob`,`gender`,`mobile`, `email`, `gateway_type`) VALUES ('$order_id', '$college_id', '$cust_id', '$name','$dob','$gender','$mobile', '$email', '$gateway')";
				$result = $conn->query($sql);
				// echo $sql.'<pre>';
				// print_r(mysqli_fetch_assoc($result));
				// die;

				if($result){
					$error = 0;
					$message = '|	
					<input class="form-control" type="hidden" name="ORDER_ID" id="order_id" value="'.$order_id.'">					
					<input class="form-control" type="hidden" name="COLLEGE_ID" id="cust_id" value="'.$college_id.'">
					<input class="form-control" type="hidden" name="CUST_ID" id="cust_id" value="'.$cust_id.'">
					<input class="form-control" type="hidden" name="MOBILE_NO" id="mobile" maxlength="10" value="'.$mobile.'">
					<input class="form-control" type="hidden" name="EMAIL" id="order_id" value="'.$email.'">							
					<input class="form-control" type="hidden" name="TXN_AMOUNT" id="amount" value="'.$amount.'">
					<input class="form-control" type="hidden" name="PAGE_FROM" id="page_from" value="payment_online">';		
				}
				else{
					$message = "Error on adding details";
				}
			}
			$conn->close();
		}
}
else{
	$message = "Invalid access!";
}

echo $error."|".$message;
?>