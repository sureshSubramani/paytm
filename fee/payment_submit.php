<?php

$servername = "localhost";
$username 	= "root";
$password 	= "";
$dbname 	= "mahendra_org";

$error = 1;
$message = "";

if(isset($_POST['order_id']) && $_POST['order_id']){

		$order_id 	= isset($_POST['order_id'])?$_POST['order_id']:"";
		$college_id = isset($_POST['college_id'])?$_POST['college_id']:"";
		$cust_id 	= isset($_POST['cust_id'])?$_POST['cust_id']:"";
		$mobile 	= isset($_POST['mobile'])?$_POST['mobile']:"";
		$email 		= isset($_POST['email'])?$_POST['email']:"";
		$gateway 	= isset($_POST['gateway'])?$_POST['gateway']:"";

		if($order_id && $cust_id && $mobile){
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
				$message = "Connection failed";
			} else {

				//$sql = "SELECT * FROM student_payment WHERE order_id=".$order_id." AND record_status=1";
				$sql = "SELECT * FROM student_payment WHERE order_id like '%".$order_id."%' AND record_status=1";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					$message = "Order ID exist. Try again!";
				} else {
					$sql = "INSERT INTO `student_payment`(`order_id`, `college_id`, `cust_id`, `mobile`, `email`, `gateway_type`) VALUES ('$order_id', '$college_id', '$cust_id', '$mobile', '$email', '$gateway')";

					$result = $conn->query($sql);
					if($result){
						$error = 0;
						$message = "";
					}
					else{
						$message = "Error on adding details";
					}
				}
				$conn->close();
			}
			
		} else {
			$message = "Required value missing!";
		}

} else {
	$message = "Invalid access!";
}

echo $error."|".$message;
?>