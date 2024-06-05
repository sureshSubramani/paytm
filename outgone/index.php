<?php 
if(session_id() == '') {
    session_start();
}

include 'config.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Outgone Students</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.png" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="assets/css/datepicker.css" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/admission.css">
    <link rel="stylesheet" href="assets/css/google_fontawesome.css">
    <link rel="stylesheet" href="css/custom.css">
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/jquery.validate.js"></script>
    <script src="assets/js/additional-methods.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/datepicker.js"></script>
    <script src="assets/js/out_student.js"></script>
    
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Comfortaa&family=Josefin+Sans&display=swap');
        .boxshadow {
            -moz-box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Josefin Sans', sans-serif;
            background: transparent;
            height: 100%;
            background-image: url("./images/payment-bg.jpg");
            background-position: 50% 50%;
            background-attachment: fixed;
            background-size: cover;
        }

        .m-top {
            margin-top: 20px !important
        }

        .table {
            width: 100%;
            max-width: 500px;
            border: none;
            margin: 0px auto;
            margin-top: 0px;
            margin-bottom: 0px;
            border: 0px solid #fff;
        }

        .payment_form {
            background: #ffffff;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 5px 5px 15px #2f305b;
        }

        .table th,
        .table td {
            border-top: none !important;
        }

        .otp-title {
            top: -15px;
            position: relative;
        }

        #mob-verify i {
            color: green;
        }

        table {
            font-family: 'Josefin Sans', sans-serif;
        }

        .mt-1 {
            margin-top: 10px;
        }

        .table>tbody>tr>td {
            vertical-align: initial !important;
        }
		
		@media (max-width: 1068px){
			 #msform fieldset {
             padding: 0px 10px;   
			 width: 100%;
            }
		}
			
		@media (max-width: 768px){
            .col-md-offset-3 {
                margin-left: 0%;
            }
            .col-md-offset-4 {
                margin-left: 0%;
            }

            .col-sm-12 {
                padding-right: 0px;
                padding-left: 0px;
            }

            h1.header-title {
                font-size: 2.5rem;
            }

            #msform fieldset {
             padding: 0px 10px;   
			 width: 100%;
            }
        }
    </style>
</head>

<body>
<div id="wrapper" class="clearfix">
	<!-- Header -->
	<header class="header mt-5">
		<div class="header-nav navbar-scrolltofixed">
			<div class="header-nav-wrapper header">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 text-center">
							<h1 class="header-title">MEI ONLINE PAYMENT</h1>
						</div>                         
					</div>
				</div>
			</div>
		</div>
	</header>
</div>
<div class="container-fluid">
    <!-- Modal -->
     <div class="modal fade in" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                <div class="row">                
                    <form id="otp_form" method="post" name="form" action="">
                        <div class="col-sm-6 col-md-12 col-lg-12">
                            <h5 class="text-center">Verify Your OTP Number</h5>                        
                            <div class="form-check-inline">
                                <div class="form-check-inline col-md-8">   
                                    <input type="text" class="form-control" id="otp_number" name="otp_number" maxlength="6" value="" placeholder="6 Digits" autocomplete="off" required/>
									<span id="is-error"></span>
                                </div>                                             
                            </div>  
                            <div class="form-check-inline col-lg-2">    
                                <input type="button" id="verify-btn" class="btn btn-md btn-success"  name="type_degree" value="Verify">                            
                            </div>                                 
                        </div>
                    </form>
                </div>            
            </div>
            </div>
        </div>
    </div> 
    <!-- end Modal -->
    
    <div class="col-md-4 col-lg-4 col-md-offset-4 admission-legend-2 sp_form m_top">            
        <div class="field_set text-center">
            <legend class="legand"> &nbsp;Verify Your Mobile</legend>                        
            <div class="form-group col-lg-6 col-md-offset-1">
                <input type="text" name="mobile" id="mobile" maxlength="10" class="form-control mobile_validation text-left" placeholder="Enter mobile number" required/>
                <span id="msg"></span>              
            </div>
            <div class="form-group col-lg-4"> 
                <button type="button" id="submit" class="selector-item_label">
                    <span id="loader"><span></span><span></span><span></span></span>
                </button>             
            </div>
        </div>
    </div>  
              
    <div class="col-md-6 col-md-offset-3 ms_form" style="display:none; margin-bottom:60px;">
            <form id="msform" method="post" name="form" action="" class="m_top" onkeydown="return (event.keyCode!=13);">
                <div class="col-sm-12">
                    <!-- fieldsets -->                  
                    <fieldset id="per_info">
                        <h2 class="fs-title">Outgone Student Information</h2>
                        <h3 class="fs-subtitle"></h3>
                        <div class="form-group">
                            <input type="hidden" name="phone" maxlength="10" id="phone" />    
                        </div>
                        <div class="form-group">
                            <input type="text" name="reg_no" id="reg_no" placeholder="Enter your reg. number" autocomplete="off"/>
                        </div>                     
                        <div class="form-group">
                            <input type="text" name="role_no" id="role_no" placeholder="Enter your role number" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="name" id="name" placeholder="Enter your name" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="fname" id="fname" placeholder="Enter your father name" autocomplete="off"/>
                        </div>                        
                        <div class="form-group">
                            <input type="text" name="dob" id="dob" placeholder="DD-MM-YYYY" value="" />
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email" placeholder="Enter your email" value="" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <select name="gender" id="gender">
                                <option value="none" selected disabled>-- Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="college" id="college">
                                <option value="none" selected disabled>-- Select Your College --</option>
                                <option value="MEC">MEC</option>
                                <option value="MIT">MIT</option>
								<option value="MECW">MECW</option>
                                <?php/* 
                                <option value="MCE">MCE</option>
                                <option value="MIET">MIET</option>
                                <option value="MECW">MECW</option>
                                <option value="MPTC">MPTC</option>
                                <option value="MASC">MASC</option>  
                                */ ?>
                            </select>
                        </div>                                                                        
                        <div class="form-group">
                            <input type="text" name="department" id="department" placeholder="Enter your department" value="" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <select name="out_year" id="out_year">
                                <option value="none" selected disabled>Outgone Year</option>
                                <option value="2012-2013">2012-2013</option>
                                <option value="2013-2014">2013-2014</option>
                                <option value="2014-2015">2014-2015</option>
                                <option value="2015-2016">2015-2016</option>
                                <option value="2016-2017">2016-2017</option>
                                <option value="2017-2018">2017-2018</option>
                                <option value="2018-2019">2018-2019</option>
                                <option value="2019-2020">2019-2020</option>
                                <option value="2020-2021">2020-2021</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <select name="fee_type" id="fee_type">
                                <option value="none" selected disabled>-- Fee Selection --</option>
                                <option value="fee">Fees</option>
                                <option value="exam">Exam Fees</option>
                            </select>
                        </div>  
                        <div class="form-group">
                            <input type="text" name="amount" id="amount" placeholder="Rs." value="" />
                        </div> 
                        <div class="form-group">
                            <label class="agreeTerms label-2">                                    
                                <span class="agree_terms">I've read and agree the terms and conditions of<br> <span><strong data-toggle="modal" data-target="#agreeTerms">FEE PAYMENT PROCEDURE</strong></span></span>
                            </label>
                            <input type="checkbox" class="form-control check_box" name="agree_terms" id="agree_terms" />   
                            <span id="message"></span>                                                             
                        </div>                      
                        <div class="form-group">
                                <span id="gen_form"></span>
                                <div id="offset" class="row col-md-offset-0">
                                    <div class="col-md-6">
                                        <button type="button" id="paytm" name="type_payment" class="selector-item_label" onClick="pay_now('paytm')" >
                                            <span id="loader1"><span></span><span></span><span></span></span>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="eazypay" name="type_payment" class="selector-item_label" onClick="pay_now('icici')">
                                            <span id="loader2"><span></span><span></span><span></span></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>                    
                 </div>
            </form>
            </div>
       </div>
    </div>

    <!-- Modal -->
  <div class="modal fade" id="agreeTerms" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Terms and Conditions</strong></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0px 30px 0px 30px !important;">
                    <p><strong>FEE PAYMENT PROCEDURE</strong></p>
                    <p><strong>Exam Fee Payment</strong></p>
                    <ul class="terms_list">
                    <li>Exam fee payment can be made by Net Banking / Credit Cards / Debit Cards</li>
                    <li>Click the Checkbox (I have read and agree the terms and conditions and Click &quot;Accept and Continue&quot; Button.</li>
                    <li>You will be redirected to <a class="admission_url" href="http://mahendra.org/examfee.php" title="">http://mahendra.org/examfee.php</a> - Enter with your credentials to Login</li>
                    <li>Select the mode of payment such as Credit card / Debit card / Net Banking.</li>
                    <li>Kindly follow the instructions as applicable to your choice of payment.</li>
                    </ul>
                    <p><strong>Processing charges for each transaction</strong></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-stripped">
                        <tbody>
                            <tr>
                            <th>CREDIT CARD</th>
                            <th>NET BANKING</th>
                            <th>DEBIT CARD</th>
                            <th>WALLET</th>
                            <th>UPI</th>
                            <th>AMEX CARD</th>
                            </tr>
                            <tr>
                            <td>1.10%</td>
                            <td>INR 15/-</td>
                            <td>0.9% No charges for transactions less than 2000</td>
                            <td>1.50% No charges for transactions less than 2000</td>
                            <td>0.3%</td>
                            <td>1.60%</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <p><strong>Terms and Conditions</strong></p>
                    <ul class="terms_list">
                    <li>If the transaction is successful, it will be indicated in your student portal within two or three working days</li>
                    <li>Please check your card limit before proceeding to online payment.</li>
                    <li>If the transaction has FAILED for some reasons, you are REQUESTED TO WAIT for THREE DAYS before trying for payment again, please contact office accounts for any discrepancy of online fee faced by you with reference to any of your transaction.</li>
                    <li>In any case, make a note of Reference/Transaction Details in case of Net banking or card payment.</li>
                    </ul>
                    <p><strong>Privacy Policy</strong></p>
                    <ul class="terms_list">
                    <li>The details provided by you shall be utilized only for the purpose of receiving the payments to be made by you to the Institution. All data shall be kept secure, and shall not be divulged to anyone or utilized for any other purpose.</li>
                    </ul>
                    <p><strong>Cancellation/Refund Policy</strong></p>
                    <ul class="terms_list">
                    <li>There is no cancellation option for the end users.</li>
                    <li>In case of duplicate payment, end user to approach accounts department with proof of the transaction reference/ your bank statement.</li>
                    </ul>
                    <p><strong>Important</strong></p>
                    <ul class="terms_list">
                    <li>By submitting a payment through the online-payments site you are agreeing to these terms and conditions including any updated changes in terms and conditions from time to time through our website.</li>
                    <li>This website is owned and managed by Mahendra Educational Institutions.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
        </div>
      </div>
      
    </div>
  </div>

<script type="text/javascript" src="assets/js/student.js"></script>

</body>

</html>