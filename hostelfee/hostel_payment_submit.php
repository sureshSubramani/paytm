<?php
$SERVER 	= "localhost";
$USERNAME 	= "mahendrg_mahendr";
$PASSWORD 	= "Kvuglbmp9e4;";
$DBNAME 	= "mahendrg_mahendra";

$error = 1;
$message = "";

if(isset($_POST['order_id']) && $_POST['order_id']){

	$order_id 	= isset($_POST['order_id'])?'H'.$_POST['order_id']:"";
	$college_id = isset($_POST['college_id'])?$_POST['college_id']:"";
	$cust_id 	= isset($_POST['cust_id'])?$_POST['cust_id']:"";
	$mobile 	= isset($_POST['mobile'])?$_POST['mobile']:"";
	$email 		= isset($_POST['email'])?$_POST['email']:"";
	$months 	= isset($_POST['months'])?$_POST['months']:"";
	$gateway 	= isset($_POST['gateway'])?$_POST['gateway']:"";

	if($order_id && $cust_id && $mobile){
		// Create connection
		$conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DBNAME);
		//$conn = new mysqli("localhost", "mahendrg_mahendr", "Kvuglbmp9e4;", "mahendrg_mahendra");
		// Check connection
		if ($conn->connect_error) {
		  $message = "Connection failed";
		}else{

			$sql = "SELECT * FROM hostel_payment WHERE order_id='$order_id' AND record_status=1";

			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				$message = "Order ID exist. Try again!";
			} 
			else {
				$sql = "INSERT INTO `hostel_payment`(`order_id`, `college_id`, `cust_id`, `mobile`, `email`, `months`, `gateway_type`) VALUES ('$order_id', '$college_id', '$cust_id', '$mobile', '$email', '$months', '$gateway')";

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
	}
	else{
		$message = "Required value missing!";
	}
}
else{
	$message = "Invalid access!";
}

echo $error."|".$message;
?>