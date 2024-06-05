
<?php

$servername = "localhost";
$username = "mahendrg_mahendr";
$password = "Kvuglbmp9e4;";
$dbname = "mahendrg_mahendra";


$error = 1;
$message = "";

if(isset($_REQUEST['order_id']) && $_REQUEST['order_id']){

	$order_id   = isset($_REQUEST['order_id'])?$_REQUEST['order_id']:"";
	$college_id = isset($_REQUEST['college_id'])?$_REQUEST['college_id']:"";
	$cust_id    = isset($_REQUEST['cust_id'])?$_REQUEST['cust_id']:"";
	$mobile     = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
	$email      = isset($_REQUEST['email'])?$_REQUEST['email']:"";

	if($order_id && $cust_id && $mobile){
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		  $message = "Connection failed";
		}
		else{

			$sql = "SELECT * FROM student_payment WHERE order_id like '%".$order_id."%' AND record_status=1";
			$result = $conn->query($sql);

			// echo $sql.'<pre>';
			// print_r(mysqli_fetch_assoc($result));
			// die;
		
			if ($result->num_rows > 0) {
				$message = "Order ID exist. Try again!";
			} else {
				$sql = "INSERT INTO `student_payment`(`order_id`, `college_id`, `cust_id`, `mobile`, `email`) VALUES ('$order_id', '$college_id', '$cust_id', '$mobile', '$email')";
				$result = $conn->query($sql);
				if($result){
					$error = 0;
					$message = "Please wait...";
				}
				else{
					$message = "Error on adding details";
				}
			}
			$conn->close();
		}
	}
	else{
		$message = "Required value missing!";
	}
}
else{
	$message = "Invalid access!";
}

echo json_encode(array('error'=>$error,'message'=>$message));

?>