<?php
if(session_id() == '') {
    session_start();
}
include('header.php');

$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);

?>
<style>

	.pay-success {
		position: relative;
		bottom: -5px;
		color: #000000;
		font-size: 14px;
	}
	
	.pay-details {
		bottom: 30px;
		color: #a9a9a9;
		font-size: 12px;
		position: relative;
		float: right;
	}

	.payment_icon1 {
		font-size: 20pt;
		background: #4caf50;
		border-radius: 50px;
		color: #ffffff;
		position: relative;
	}

	.payment_icon2 {
		font-size: 20pt;
		background: #bf2626;
		border-radius: 50px;
		color: #ffffff;
		position: relative;
	}
	
</style>

<!-- Start main-content -->
<div class="main-content" style="height: 100%; min-height: 500px; width: 100%; max-width: 700px; margin: auto; ">
	<?php

	if(isset($_SESSION['student'])){
		
		$payment = $_SESSION['student']["payment"];
		$update  = $_SESSION['student']["update"];

		/*echo "<pre>";
		print_r($_SESSION);
		echo '<br><br>';
		print_r($payment);
		echo "</pre>";
		die;*/
		
		if(isset($payment["RESPCODE"]) && $payment["RESPCODE"]==01 || $payment["RESPCODE"]=='E000' ){ ?>
			<div class='text-center'>
				<i class="fa fa-check payment-success payment_icon1"></i>
				<p class='bg-success text-center' style='color:white;background-color: #2e9005 !important;padding: 2px;'>Payment Success</p>
			</div>
		<?php }else{ ?>
			<div class='text-center'>
				<i class="fa fa-exclamation payment-failure payment_icon2"></i>	
				<p class='bg-danger text-center' style='color:white;background-color: #bf2626 !important;padding: 2px;'> Payment Failure</p>
			</div>
		<?php } ?>
		<div class="table-responsive">
			<table class="table table-bordered" style="width: 100%;max-width: 700px;background:#ffffff">
				<thead>
			        <tr>
			            <th colspan="2" >
			            	<span style="text-transform: uppercase;">Payment Details</span>
			            </th>
			        </tr>
			    </thead>
			    <tbody>
			        <tr>
			            <td>Order ID</td>
			            <td><?php echo isset($payment["ORDERID"])?$payment["ORDERID"]:""; ?></td>
			        </tr>
					<tr>
			            <td>Transaction ID</td>
			            <td><?php echo isset($payment["TXNID"])?$payment["TXNID"]:""; ?></td>
			        </tr>
			        <tr>
			            <td>College_Name</td>
			            <td><?php echo isset($_SESSION["COLLEGE"])?$_SESSION["COLLEGE"]:""; ?></td>
			        </tr>
					<tr>
			            <td>Register_Number</td>
			            <td>
							<?php echo isset($_SESSION["CUST_ID"])?$_SESSION["CUST_ID"]:""; ?>
							<?php echo isset($_SESSION["REG_NO"])?$_SESSION["REG_NO"]:""; ?>
						</td>
			        </tr>
					<tr>
			            <td>Name</td>
			            <td><?php echo isset($_SESSION["NAME"])?$_SESSION["NAME"]:""; ?></td>
			        </tr>
					<tr>
			            <td>Mobile</td>
			            <td><?php echo isset($_SESSION["MOBILE"])?$_SESSION["MOBILE"]:""; ?></td>
			        </tr>
					<tr>
			            <td>Email</td>
			            <td><?php echo isset($_SESSION["EMAIL"])?$_SESSION["EMAIL"]:""; ?></td>
			        </tr>
			        <tr>
			            <td>Amount</td>
			            <td><?php echo isset($payment["TXNAMOUNT"])?$payment["TXNAMOUNT"]:""; ?></td>
			        </tr>
			        <tr>
			            <td>Payment_Status</td>
			            <td>
							<?php echo isset($_REQUEST["status_code"])?$_REQUEST["status_code"]:""; ?>
							<?php echo isset($payment["RESPMSG"])?$payment["RESPMSG"]:""; ?>
						</td>
			        </tr>
			        <tr>
			            <td>Bank_Name</td>
			            <td><?php echo isset($payment["BANKNAME"])?$payment["BANKNAME"]:""; ?></td>
			        </tr>
			        <tr>
			            <td>Payment_Mode</td>
			            <td><?php echo isset($payment["PAYMENTMODE"])?$payment["PAYMENTMODE"]:""; ?></td>
			        </tr>
			    </tbody>
			</table>
			</div>
			<div class="text-left">
				<p><span class="pay-success"><strong>Note:</strong> Please Take the print out for the future reference.</span> </p>
				<p><span class="pay-details"><?php echo $update; ?></span> </p>
			</div>
			<br/>	
			<div class='text-center'>
				<p class='text-center'> <a href="javascript:" onclick="window.print()" class="btn btn-sm btn-primary">Print <i class="fa fa-print"></i></a> &nbsp; &nbsp; 
				<a href="/<?php echo $uri_segments[1].'/'.$uri_segments[2]?>" class="btn btn-sm btn-danger">Home <i class="fa fa-home"></i></a>		</p>
			</div>	
		
	<?php
		//REMOVE SESSION
		foreach($_SESSION['student']["payment"] as $k=>$v){
			unset($_SESSION['student']["payment"][$k]);
		}
		unset($_SESSION['MOBILE']);
		unset($_SESSION['COLLEGE']);
		unset($_SESSION['REG_NO']);
		unset($_SESSION['ROLL_NO']);
		unset($_SESSION['NAME']);
		unset($_SESSION['EMAIL']);
		unset($_SESSION['CUST_ID']);
	}
	else{
		header("Location:https://mahendra.org/online_payment/outgone/");
	}
	?>
</div>