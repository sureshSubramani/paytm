<?php
if(session_id() == '') {
    session_start();
}
include('header.php');
?>
<style>
.pay-details {
    position: relative;
    bottom: 40px;
    color: #9c9c9c;
    float: right;
    font-size: 11px;
}

@import url('https://fonts.googleapis.com/css2?family=Comfortaa&family=Josefin+Sans&display=swap');
body{
	font-family: 'Josefin Sans', sans-serif !important;
}

.pay-details {
    position: relative;
    bottom: 40px;
    color: #9c9c9c;
    float: right;
    font-size: 11px;
}

table td, table tr, table th, p{
	color:#000000;
}


</style>

<!-- Start main-content -->
<div class="main-content" style="height: 100%; min-height: 500px; width: 100%; max-width: 700px; margin: auto; ">
	<?php

	if(isset($_SESSION['hostel'])){
		
		$payment = $_SESSION['hostel']["payment"];
		$update  = $_SESSION['hostel']["update"];		
	
		if(isset($payment["RESPCODE"]) && $payment["RESPCODE"]==01 || isset($payment["RESPCODE"]) && $payment["RESPCODE"]=='E000' ){ ?>
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
			
			<table class="table table-bordered" style="width: 100%; background-color: white; max-width:700px">
				<thead>
			        <tr>
			            <th colspan="2" >
			            	<span style="text-transform:uppercase;">Payment Details</span>
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
			            <td>Hostel_Code</td>
			            <td><?php echo isset($_SESSION["HOSTEL_CODE"])?$_SESSION["HOSTEL_CODE"]:""; ?></td>
			        </tr>
					<tr>
			            <td>Roll_Number</td>
			            <td><?php echo isset($_SESSION["ROLL_NO"])?$_SESSION["ROLL_NO"]:""; ?></td>
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
			            <td>Months</td>
			            <td><?php echo isset($_SESSION["MONTHS"])?$_SESSION["MONTHS"]:""; ?></td>
			        </tr>
			        <tr>
			            <td>Amount</td>
			            <td><?php echo isset($payment["TXNAMOUNT"])?$payment["TXNAMOUNT"]:""; ?></td>
			        </tr>
			        <tr>
			            <td>Payment_Status</td>
			            <td>
							<?php echo isset($payment["RESPMSG"])?$payment["RESPMSG"]:""; ?>
							<?php echo isset($_REQUEST["status_code"])?$_REQUEST["status_code"]:""; ?>
							<?php 
								if($payment["RESPCODE"]=='E000'){
									echo isset($_REQUEST["status_code"])?$_REQUEST["status_code"]:""; 
								}
							?>
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
			<div class="text-center">
				<p style="display:inline-block;float:left;"><strong>Note:</strong> Please Take the print out for the future reference. </p>
				<p style="display:inline-block;float:right;color:#cccccc"><?php echo $update; ?></p>
			</div>

			<div class="text-center" style="display:inline-block;width:100%">
				<a href="javascript:" class="btn btn-info btn-sm" onclick="window.print()">Print <i class="fa fa-print"></i></a> &nbsp; &nbsp; 
				<a href="https://mahendra.org/online_payment/hostelfee/" class="btn btn-danger btn-sm">Home <i class="fa fa-home"></i></a>				
			</div>
			<br/>
						
		</div>
		
		
		<?php
		//REMOVE SESSION
		foreach($_SESSION['hostel']["payment"] as $k=>$v){
			unset($_SESSION['hostel']["payment"][$k]);
		}
		unset($_SESSION['hostel']);

	}
	else{
		header("Location:https://mahendra.org/online_payment/hostelfee/");
	}
	?>
</div>