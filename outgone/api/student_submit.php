<?php

$servername = "localhost";
$username = "mahendrg_mahendr";
$password = "Kvuglbmp9e4;";
$dbname = "mahendrg_mahendra";

$error   = array('error'=>1);
$message['data'] = array();

if(!empty($_REQUEST['order_id'])){

		$reg_no     = isset($_REQUEST['reg_no'])?$_REQUEST['reg_no']:"";
		$role_no    = isset($_REQUEST['role_no'])?$_REQUEST['role_no']:"";
		$name       = isset($_REQUEST['name'])?$_REQUEST['name']:"";
		$fname      = isset($_REQUEST['fname'])?$_REQUEST['fname']:"";
		$dob        = isset($_REQUEST['dob'])?$_REQUEST['dob']:"";
		$gender     = isset($_REQUEST['gender'])?$_REQUEST['gender']:"";
		$email      = isset($_REQUEST['email'])?$_REQUEST['email']:"";
		$college_id = isset($_REQUEST['college_id'])?$_REQUEST['college_id']:"";
		$department = isset($_REQUEST['department'])?$_REQUEST['department']:"";
		$out_year   = isset($_REQUEST['out_year'])?$_REQUEST['out_year']:"";
		$fee_type   = isset($_REQUEST['fee_type'])?$_REQUEST['fee_type']:"";		
		$mobile     = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
		$amount     = isset($_REQUEST['amount'])?$_REQUEST['amount']:"";
		$terms      = isset($_REQUEST['agree_terms'])?$_REQUEST['agree_terms']:"";
        $order_id   = isset($_REQUEST['order_id'])?$_REQUEST['order_id']:"";
		$cust_id    = '';

		/*if($fee_type=='fee')
			$order_id = 'OF'.date('Ymdhis');

		if($fee_type=='exam')
			$order_id = 'OE'.date('Ymdhis');*/

		if(!empty($reg_no) && empty($cust_id))
			$cust_id = isset($_REQUEST['reg_no'])?$_REQUEST['reg_no']:"";

		if(!empty($role_no) && empty($cust_id))
			$cust_id = isset($_REQUEST['role_no'])?$_REQUEST['role_no']:"";

		if(!empty($name) && empty($cust_id))
			$cust_id = isset($_REQUEST['name'])?$_REQUEST['name']:"";		

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if($conn->connect_error) {
		  $message['data'] = $error;
		}else{

			$osd = "SELECT * FROM out_student_details WHERE (reg_no='".$reg_no."') OR (roll_no='".$role_no."') AND status=1";
			$isExist = $conn->query($osd);
			
			if($isExist->num_rows > 0){
				//Update Data
				$osd = "UPDATE out_student_details SET `roll_no`= '$role_no', `name`='$name', `f_name`='$fname', `mobile`='$mobile', `dob`='$dob', `gender`='$gender', `email`='$email', `college_name`='$college_id', `dep_name`='$department',`outgone_year`='$out_year', `fee_type`='$fee_type',`agree_terms`='$terms' WHERE reg_no='".$reg_no."'";
				$conn->query($osd);
			}else{
				//Insert Data
				$osd = "INSERT INTO `out_student_details`(`reg_no`, `roll_no`, `name`, `f_name`, `mobile`, `dob`, `gender`, `email`, `college_name`, `dep_name`,`outgone_year`, `fee_type`, `amount`,`agree_terms`) VALUES ('$reg_no','$role_no','$name','$fname','$mobile','$dob','$gender','$email','$college_id','$department','$out_year','$fee_type','$amount','$terms')";
				$conn->query($osd);
			}

			$sql = "SELECT * FROM out_student_payments WHERE order_id like '%".$order_id."%' AND record_status=1";
			$result = $conn->query($sql);
		
			if ($result->num_rows > 0) {
				$message['data'] = array('isRecord'=>1);
			} else {

				$sql = "INSERT INTO `out_student_payments`(`order_id`, `college`, `cust_id`,`name`, `dob`,`gender`,`mobile`, `email`) VALUES ('$order_id', '$college_id', '$cust_id', '$name','$dob','$gender','$mobile', '$email')";
				$result = $conn->query($sql);

                if(!$result){
                    $message['data'] = array('error'=>0);
                    $message['data'] = array('success'=>1);
                }else{
					$message['data'] = array('success'=>0);
			 	}

			 }
			$conn->close();
		}
}else{
	$message['data'] = $error;
}

echo json_encode($message);

?>