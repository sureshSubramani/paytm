<?php

$SERVER = "localhost";
$USERNAME = "mahendrg_mahendr";
$PASSWORD = "Kvuglbmp9e4;";
$DBNAME = "mahendrg_mahendra";

$error = array('error'=>1);
$message = array();

if(isset($_REQUEST['order_id']) && $_REQUEST['order_id']){

	$order_id 	= isset($_REQUEST['order_id'])?$_REQUEST['order_id']:"";
	$college_id = isset($_REQUEST['college_id'])?$_REQUEST['college_id']:"";
	$cust_id 	= isset($_REQUEST['cust_id'])?$_REQUEST['cust_id']:"";
	$mobile 	= isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
	$email 		= isset($_REQUEST['email'])?$_REQUEST['email']:"mahendraeducation@gmail.com";
	$months 	= isset($_REQUEST['months'])?$_REQUEST['months']:"";

	if($order_id && $cust_id && $mobile){

		// Create connection
		$conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DBNAME);
		// Check connection
		if ($conn->connect_error) {
		  $message = array('message'=>'Connection failed');
		}else{

			$sql = "SELECT * FROM hostel_payment WHERE order_id='$order_id' AND record_status=1";

			$result = $conn->query($sql);

			if($result->num_rows > 0){
				$message = array('message'=>'Order ID exist. Try again!');
			} else {

				$sql = "INSERT INTO `hostel_payment`(`order_id`, `college_id`, `cust_id`, `mobile`, `email`, `months`) VALUES ('$order_id', '$college_id', '$cust_id', '$mobile', '$email', '$months')";
				$result = $conn->query($sql);

				if($result){
					$error = array('error'=>0);
					$message = array('message'=>'Added details');
				}else{
					$message = array('message'=>'Error on adding details');
				}

			}
			$conn->close();
		}

	}else{
		$message = array('message'=>'Required value missing!');
	}

}else{
	$message = array('message'=>'Invalid access!');
}

echo json_encode(array_merge($error,$message));

?>