<?php 
include('header.php');
require('config.php');
?>
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Comfortaa&family=Josefin+Sans&display=swap');

    body {
        font-family: 'Josefin Sans', sans-serif;
        background: transparent;
        height: 100%;
        background-image: url("http:///mahendra.org/hostelfee/images/payment-bg.jpg");
        background-position: 50% 50%;
        background-attachment: fixed;
        background-size: cover;
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
    
    .modal {
        top: 35% !important;
    }
    
    #mob-verify i {
        color: green;
    }
    table{
        font-family: 'Josefin Sans', sans-serif;
    }
    h1.header-title {
        color: #ffffff;
        font-family: 'Josefin Sans', sans-serif;
        font-size:2.5rem;
    }
	
    .header {
		background: #2f2e58;
		color: white;
		opacity: 0.95;
		background-image: linear-gradient(45deg, #2e315c, #345daf, #2d305b);
	}
    .mt-1{
        margin-top:10px;
    }
    .table>tbody>tr>td{
        vertical-align: initial !important;
    }
	
	h1{
           margin:0 auto;
	}

</style>

<!-- Start main-content -->
	<div class="main-content" style="height: 100%; min-height: 500px">	
		<div class="col-md-4 col-lg-4 col-md-offset-4" style="margin-top:20px">
    		<div class="form-wrap">		                        
            <form action="PaytmKit/paymentRedirect.php" method="post" name="payment_form" class="payment_form pay-form" id="payment_form">
            <table class="table borderless" border="1px" cellspacing='0' cellpadding='0'>
                <tr class='form_content'><td colspan="2"><h3 class="text-center"><u>Online Fee Payment</u></h3></td></tr>
                <tr class='form_content'>
                    <td>Roll No.</td>
                    <td>
                      <input type="text" autocomplete="off" name="CUST_ID" id="roll_no" placeholder="Your Register/Roll Number" class="form-control select_college" onblur="get_candidate(this.value)" required>
                    </td>
                </tr>
                <tr class='form_content'>
                    <td>Mobile Number</td>
                    <td>
                        <input type="text" autocomplete="off" name="MOBILE_NO" id="mobile" minlength="10" maxlength="10" class="form-control select_college" placeholder="Max. 10 digits" required>&nbsp;
                        <span id="mob-verify"><i class="fa fa-check"></i></span>
                    </td>
                </tr>
                <tr class="candidate_info month_info" style="display: none;">
                    <td colspan="2">
                        <h3 class="text-center month_info">Online Fee Payment</h3>
                    </td>
                </tr>
                <tr class="candidate_info name_info" style="display: none; border-top: 1px solid #ccc;">
                    <td>Student Name</td>
                    <td><span id="candidate_name"></span></td>
                </tr>
				<tr class="father_info name_info" style="display: none">
                    <td>Father Name</td>
                    <td><span id="father_name"></span></td>
                </tr>
                <tr class="candidate_info" style="display: none">
                    <td>College Name</td>
                    <td><span id="candidate_college"></span></td>
                </tr>
                <tr class="candidate_info" style="display: none">
                    <td>Roll Number</td>
                    <td><span id="candidate_roll_no"></span></td>
                </tr>
                <tr class="candidate_info" style="display: none">
                    <td>Mobile Number</td>
                    <td><span id="candidate_mobile"></span></td>
                </tr>                
                <tr class="candidate_info dep_info" style="display: none;">
                    <td>Department</td>
                    <td><span id="dep_name"></span></td>
                </tr>
                <tr class="candidate_info" style="display: none; border-bottom: 1px solid #ccc;">
                    <td>Amount</td>
                    <td> <input type="text" name="TXN_AMOUNT" id="amount" placeholder="Rs." required> </td>
                </tr>
                <tr class="text-center">
                        <td colspan="2" style="text-align: center; paddig-top:-30px; position:relative;">
                            <div id="form-msg" style="text-align: center; font-weight: bold; padding: 10px 0"></div>
                            <input type="hidden" name="EMAIL" id="email" required>
                            <input type="hidden" name="COLLEGE_ID" id="college_id" required>                            
                            <input type="hidden" name="ORDER_ID" id="order_id" value="<?php echo date("Ymdhis"); ?>" required>
                            <input type="hidden" name="MOBILE_NO" id="mobile_no" required>
                            <input type="submit" id="paynow_btn" style="display: none;" value="">
                            <input type="button" id="paynow_btn2" class="btn btn-sm btn-success" onclick="validate_payment_form()" style="display: none;" value="Pay Now">
                            <input type="button" id="next" class="btn btn-sm btn-success" onclick="check_candidate()" value="Next">
					</td>

                </tr>

            </table>
        </form>
    </div>
		</div>
</div>

<!-- Modal -->
<div class="modal fade" id="otpModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <form id="otp_form" method="post" name="form" action="">
                    <div class="col-md-12">
                        <h5 class="otp-title text-center">Verify OTP Number</h5>
                        <div class="form-check-inline">
                            <div class="form-check-inline col-md-8">
                                <input type="text" class="form-control" id="otp_number" name="otp_number" maxlength="6" value="" placeholder="<?php  echo isset($_SESSION["mobile_otp"])?$_SESSION["mobile_otp"]:""; ?>"
                                    autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-check-inline col-lg-2">
                            <input type="button" id="verify-btn" class="btn btn-md btn-success" onclick="verify_mobile()" value="Verify">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!--- End Modal --->

<!-- end main-content -->

<script>

$(".candidate_info").hide();

function check_candidate() {

    var roll_no = $('#roll_no').val();

    if (roll_no) {

        $.ajax({
            type: 'post',
            url: 'get_candidate.php',
            data: 'roll_no=' + roll_no,
            contenttype: "application/json; charset=utf-8",
            datatype: JSON,
            beforeSend: function() {
                $("#form-msg").html("<img src='images/spinner/spinner.gif' width='50' />");
            },
            success: function(e) {
                $("#form-msg").html("<img src='images/spinner/spinner.gif' width='50' />");
            }
        });

    } else {
        $("#form-msg").html('Please Enter your Register/Roll Number');
        $("#roll_no").focus();
    }

}

function get_candidate(roll_no) {

    var roll_no = $('#roll_no').val();

    if (roll_no) {
        $.ajax({
            type: 'post',
            url: 'get_candidate.php',
            data: 'roll_no=' + roll_no,
            contenttype: "application/json; charset=utf-8",
            datatype: JSON,
            beforeSend: function() {
                $("#form-msg").html("<img src='images/spinner/spinner.gif' width='50' />");
            },
            success: function(e) {
                //console.log(e);
                data = jQuery.parseJSON(e);
                //console.log(data);  return;
                var college_name,mobile, name, dep_name, email, fname;

                if (data[0]['error'] == 0) {

                    for (let i = 1; i < data.length; i++) {

                        college_name = data[i]['college_name'];
                        mobile = data[i]['mobile'];
                        dep_name = data[i]['dep_name'];
                        name = data[i]['name'];
						fname= data[i]['f_name'];
                        email = data[i]['email'];
                    }
                    $("#next").prop('disabled',false);
                    $("#form-msg").html('');
                    $("#college_id").val(college_name);
                    $("#mobile").val(mobile);
                    $("#mobile_no").val(mobile);
                    $("#name").val(name);
                    $("#dep_name").val(dep_name);
                    $("#email").val(email);
                    //$("#amount").val(sum);
                    $("#candidate_college").html(college_name);
                    $("#candidate_roll_no").html(roll_no);
                    $("#candidate_mobile").html(mobile);
                    $("#candidate_name").html(name);
					$("#dep_name").html(dep_name);
					$("#father_name").html(fname);

                } else {   
                    $("#form-msg").html(data[1]).css('color','red')               
                    setTimeout(function(){ $("#form-msg").html(''),$("#next").prop('disabled',false); }, 2000);
                    $("#next").prop('disabled',true);
                    $("#roll_no").focus();
                    $("#mobile").val('');
                }

            }
        });

    } else {

        $("#form-msg").html('Please Enter Register/Roll Number').css('color','red');
        $("#roll_no").focus();

    }

}

$(document).ready(function() {

    $("#next").click(function() {

        $("#form-msg").html('');

        var mob = $("#mobile").val();

        if( mob != '' && mob.length == 10 ){

            $.ajax({

                url: 'otp_generation.php',
                type: 'POST',
                data: '',
                error: function() {
                    alert('Something is wrong..');
                },
                success: function(response) {

                    //$("#otp_number").val(response);

                      var otp = response;

                      var d = new Date();

                      var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                      var yyyy = d.getFullYear();
                      var mm = months[d.getMonth()];
                      var dd = d.getDate();

                      var otp = "OTP is " + response + " valid for this transaction on  " + dd + "/" + mm + "/" + yyyy + ". Please Do not share this OTP to anyone for security reasons. MAHENDRA";
                      //console.log(otp);

                      if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                      } else { // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                      }

                      xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                          alert("Alert message has been sent!");
                        }
                      }

                     
                      //xmlhttp.open("GET", "https://sms.nettyfish.com/vendorsms/pushsms.aspx?apikey=28781b87-f68e-41e5-8956-305b33520415&clientId=7ee36543-dd6f-4c64-a42b-4b9784f9bcef&msisdn=" + mob + "&sid=MEIADM&msg=" + otp + "&fl=0&gwid=2", true);
					
					xmlhttp.open("GET", "https://sms.nettyfish.com/api/v2/SendSMS?ApiKey=28781b87-f68e-41e5-8956-305b33520415&ClientId=7ee36543-dd6f-4c64-a42b-4b9784f9bcef&MobileNumbers=" + mob + "&SenderId=MEIADM&message=" + otp + "&response=Y", true);

                      xmlhttp.send();

                    $('#otpModal').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false,
                        mouse: false
                    });

                }

            });

        } else {

            $("#form-msg").html('Please Enter Valid 10 Digits Mobile Number?').css('color','red');
            $("#mobile").focus();

        }


    });

});


$("#mob-verify").hide();

function verify_mobile() {

    var mobile = $("#mobile").val();
    var otp_no = $("#otp_number").val();

    if (otp_no != '') {

        $.ajax({

            url: 'otp_generation.php',
            type: 'POST',
            data: {'otp_val': otp_no},
            error: function() {
                alert('Something is wrong');
            },
            success: function(response) {

                console.log(response);
                var res = response.split("|");

                if (res[0] == 1) {
                    $(".candidate_info").show();
                    $("#no_of_mess_amt").show();
                    $("#otpModal").modal("toggle").hide();
                    $("#next").hide();
                    $(".mob-verify").show(300);
                    $("#college_id").attr("readonly", true);
                    $("#roll_no").attr("readonly", true);
                    $("#mobile").attr("readonly", true);
                    $(".form_content").slideUp("Fast");
                    $("#paynow_btn2").slideDown("Fast");
                    $("#mobile_verify_btn").slideUp("Fast");
                    $("#form-msg").html('');
                    $("#candidate_mobile").html(mobile);
                    $("#mobile_no").val(mobile);
                    $(".amount_info").slideDown("Fast");
                    $(".name_info").slideDown("Fast");
                    $(".month_info").slideDown("Fast");
                    $(".month_year_info").slideDown("Fast");
                } else {

                    $(".name_info").slideUp("Fast");
                    $(".amount_info").slideUp("Fast");

                    if (res[0] == 3 || res[0] == 2) {
                        alert(res[1]);
                    }

                }

            }

        });

    } else {
        alert('Please enter valid OTP number.');
    }

}

function validate_payment_form() {

    $("#form-msg").html('');
    var order_id = $("#order_id").val();
    var college_id = $("#college_id").val();
    var cust_id = $("#roll_no").val();
    //var name = $("#name").val();
    var mobile = $("#mobile").val();
    var email = $("#email").val();
    var amount = $("#amount").val();

    if (order_id && college_id && cust_id && mobile && email && amount >= 1) {

        $.ajax({
            type: 'post',
            url: 'payment_submit.php',
            data: 'order_id=' + order_id + '&college_id=' + college_id + '&cust_id=' + cust_id + '&mobile=' + mobile + '&email=' + email + '&amount=' + amount,
            beforeSend: function() {
                $("#form-msg").html("<img src='images/spinner/spinner.gif' width='50' />");
            },
            success: function(e) {
                console.log(e);
                var res = e.split("|");
                //res[0] = Error [1 = Error, 0 = No error]
                //res[1] = Message
                if (res[0] != undefined && res[0] == 0) {
                    $("#paynow_btn").click();
                }
                //Message
                if (res[1] != undefined) {
                    $("#form-msg").html(res[1]);
                } else {
                    $("#form-msg").html(e);
                }
            }
        });

    } else if (order_id == "" || order_id == undefined) {
        $("#form-msg").html('<span style="color:#f96">Order ID missing.</span>');
    } else if (college_id == "" || college_id == undefined) {
        $("#form-msg").html('<span style="color:#f96">College ID missing.</span>');
    } else if (cust_id == "" || cust_id == undefined) {
        $("#form-msg").html('<span style="color:#f96">Customer ID missing.</span>');
    } else if (mobile == "" || mobile == undefined) {
        $("#form-msg").html('<span style="color:#f96">Mobile number missing.</span>');
    } else if (email == "" || email == undefined) {
        $("#form-msg").html('<span style="color:#f96">Email ID missing.</span>');
    } else if (amount < 1 || amount == "" || amount == undefined) {
        $("#form-msg").html('<span style="color:#f96">Enter amount (Minimum Rs.1).</span>');
    } else {
        $("#form-msg").html('<span style="color:#f96">Unable submit data!</span>');
    }
}

$('#otpModal').modal({
    show: false,
    backdrop: 'static',
    keyboard: false,
    mouse: false
});

</script>

