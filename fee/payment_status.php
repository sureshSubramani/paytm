<?php
if(session_id() == '') {
    session_start();
}
include('header.php');
?>
<style>
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

	if(isset($_SESSION['student'])){
		
		$payment = $_SESSION['student']["payment"];
		$update = $_SESSION['student']["update"];
		    
			//echo $_REQUEST['status_code'];
			//echo "<pre>";
			//print_r($_SESSION);
			//echo '<br><br>';
			//print_r($payment);
			//echo "</pre>";
			
			//die;
		
		if(isset($payment["RESPCODE"]) && $payment["RESPCODE"]==01 || isset($payment["RESPCODE"]) && $payment["RESPCODE"]=='E000'){ ?>
			<div class='text-center'>
				<i class="fa fa-check payment-success"></i>
				<p class='bg-success text-center' style='color:white;background-color: #2e9005 !important;padding: 2px;'>Payment Success</p>
			</div>
		<?php }else{ ?>
			<div class='text-center'>
				<i class="fa fa-exclamation payment-failure"></i>	
				<p class='bg-danger text-center' style='color:white;background-color: #bf2626 !important;padding: 2px;'> Payment Failure</p>
			</div>
		<?php } ?>

		<br>
		<div class="table-responsive">
			<table class="table table-bordered" style="width: 100%; background-color: white;">
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
			            <td><?php echo isset($_SESSION["REG_NO"])?$_SESSION["REG_NO"]:""; ?></td>
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
			            <td>Amount</td>
			            <td><?php echo isset($payment["TXNAMOUNT"])?$payment["TXNAMOUNT"]:""; ?></td>
			        </tr>
			        <tr>
			            <td>Payment_Status</td>
			            <td>
							<?php 
								if($payment["RESPCODE"]=='E000'){
								  	echo isset($payment["RESPMSG"])?$payment["RESPMSG"]:""; 
								}
								echo isset($_REQUEST['status_code'])?$_REQUEST['status_code']:"";
								echo isset($payment["RESPMSG"])?$payment["RESPMSG"]:""; 
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
			</div>
			<div class="text-left">
				<p><strong>Note:</strong> Please Take the print out for the future reference. </p>
				<p><span class="pay-details"><?php echo $update; ?></span> </p>
			</div>
			<br/>	
			<?php 

			if(isset($payment["ORDERID"])){

				unset($_SESSION['redirect']);
				
				$string = $payment["ORDERID"];
				$ch = $string[0];

				if(strtolower($ch)=='e')
				$_SESSION['redirect'] = 'examfee.php';
				
			}
			
			?>
			<div class='text-center'>
				<p class='text-center'> <a href="javascript:" onclick="window.print()" class="btn btn-sm btn-primary">Print <i class="fa fa-print"></i></a> &nbsp; &nbsp; 
				<a href="<?=isset($_SESSION['redirect'])?$_SESSION['redirect']:'https://mahendra.org/online_payment/fee/'?>" class="btn btn-sm btn-danger">Home <i class="fa fa-home"></i></a>		</p>
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
	}
	else{
		header("Location:payment_form.php");
	}
	?>
</div>