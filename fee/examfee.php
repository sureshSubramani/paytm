<?php 
include('header.php');
require('config.php');
?>
<style type="text/css">
    
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

    .modal {
        top: 35% !important;
    }

    #mob-verify i {
        color: green;
    }

    table {
        font-family: 'Josefin Sans', sans-serif;
    }

    h1.header-title {
        color: #ffffff;
        font-family: 'Josefin Sans', sans-serif;
        font-size: 2.5rem;
    }

    .header {
        background: #2f2e58;
        color: white;
        opacity: 0.95;
        background-image: linear-gradient(45deg, #2e315c, #345daf, #2d305b);
    }

    .mt-1 {
        margin-top: 10px;
    }

    .table>tbody>tr>td {
        vertical-align: initial !important;
    }

    h1 {
        margin: 0 auto;
    }

    .pay-title {
        padding-top: 10px;
        font-size: 15px;
        color: #3f51b5;
        text-align: center;
        background-image: linear-gradient(0deg, #673ab7, #2196f3);
        -webkit-font-smoothing: antialiased;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 16pt;
        font-weight: 600;
    }

    /* Begin Initiate Spinner */
    ._loader{
        width: 100px;
        height: 100px;
        border-radius: 100%;
        position: relative;
        margin: 0 auto;
    }

    /* Initiate Spinner  */
    .spinner span {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 100%;
        background-color: #ffffff;
        margin: 0px 5px;
        opacity: 0;
    }

    .spinner span:nth-child(1){
        animation: opacitychange 1s ease-in-out infinite;
    }

    .spinner span:nth-child(2){
        animation: opacitychange 1s ease-in-out 0.33s infinite;
    }

    .spinner span:nth-child(3){
        animation: opacitychange 1s ease-in-out 0.66s infinite;
    }

    @keyframes opacitychange{
        0%, 100%{ opacity: 0; }
        60%{ opacity: 1; }
    }

    #amount {
        width:60%;
        height:auto
    }

    .error {
        color: #f44336;
        display: inline-block;
        margin-bottom: 10px;
    }

    #next{
        top: 2px;
        position: relative;
    }

    .paytm span{
        position:relative;  
        display:inline;      
    }

    .eazypay span{
        position:relative;  
        display:inline;      
    }
    
    .paytm img {
        width: 40px; 
    }

    .eazypay img {
        width: 40px;
    }
  
</style>

<!-- Start main-content -->
<div class="main-content" style="height: 100%; min-height: 500px">
	<div class="col-md-4 col-lg-4 col-md-offset-4" style="margin-top:20px">
    <div class="form-wrap">
        <form action="PaytmKit/paymentRedirect.php" method="post" name="payment_form" class="payment_form pay-form" id="payment_form">
            <table class="table borderless" style="margin-top:0px !important; border:transparent">
                <tr class='form_content'><td colspan="2"><h3 class="pay-title text-center">Online Exam Fee</h3></td></tr>
                <tr class='form_content'>
                    <td>Roll Number</td>
                    <td>
                      <input type="text" class="form-control" name="CUST_ID" id="roll_no" placeholder="Your Roll / Register Number" class="select_college" onblur="get_candidate(this.value)" required>
                    </td>
                </tr>
                <tr class='form_content'>
                    <td>Mobile Number</td>
                    <td>
                        <input type="text" class="form-control" name="MOBILE_NO" id="mobile" minlength="10" maxlength="10" class="select_college" placeholder="Maximum 10 Dgits" required>&nbsp;
                        <span id="mob-verify"><i class="fa fa-check"></i></span>
                    </td>
                </tr>
                <tr class="candidate_info month_info" style="display: none;">
                    <td colspan="2">
                        <h3 class="text-center month_info pay-title">Online Exam Fee </h3>
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
                <tr><span id="form-error" style="text-align: center; font-weight: bold"></span></tr>
                <tr class="candidate_info" style="display: none; border-bottom: 1px solid #ccc;">
                    <td>Amount</td>
                    <td> <input type="text" name="TXN_AMOUNT" class="form-control" id="amount" placeholder="Rs." required> </td>
                </tr>
                <tr class="table borderless candidate_info" style="display: none">
                    <table class="table borderless candidate_info" style="display: none">
                        <tbody>
                            <td align='center'><strong class="month_info" style="font-size:14px">COVID 19</strong> 
                            <span style="font-size:12px"> Be Responsible &nbsp;|&nbsp; Stay Home  &nbsp;|&nbsp;  Be Safe.</span></td>
                        </tbody>
                    </table>
                </tr>
                <tr>
                    <table class="table borderless">
                        <td colspan="2" style="text-align: center; paddig-top:-30px; position:relative;">
                            <span id="form-msg" style="color:red;display:block"></span>
                            <input type="hidden" name="EMAIL" id="email" required>
                            <input type="hidden" name="COLLEGE_ID" id="college_id" required>                            
                            <input type="hidden" name="ORDER_ID" id="order_id" value="<?php echo 'E'.date("Ymdhis"); ?>" required>
                            <input type="hidden" name="MOBILE_NO" id="mobile_no" required>
                            <input type="submit" id="paynow_btn" name="EXAM" style="display: none;" value="">
                            <div class="selecotr-item" id="paynow_btn2" style="display: none;">
                              <button type="button" id="paytm" name="type_payment" class="selector-item_label" onclick="validate_payment_form('paytm')" >
                                <span id="loader1"><span></span><span></span><span></span></span>
                              </button>
                              <button type="button" id="eazypay" name="type_payment" class="selector-item_label" onclick="validate_payment_form('icici')">
                                <span id="loader2"><span></span><span></span><span></span></span>
                             </button>
                            </div>
                            <button type="button" id="next" class="selector-item_label" onclick="check_candidate()">
                                 <span id="loader"><span></span><span></span><span></span></span>
                            </button>
                        </td>
                    </table>
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

$('#next').append('<span class="signature">NEXT</span>');
$('#paytm').append('<span class="paytm"><span><img src="images/icons/paytm.png" alt="Pay via Paytm" /></span> <span>PAY NOW</span></span>');
$('#eazypay').append('<span class="eazypay"><span><img src="images/icons/icici.gif" alt="Pay via ICICI" /></span><span>PAY NOW</span></span>');
$(".candidate_info").hide();

function check_candidate() {

    var roll_no = $('#roll_no').val();

    if (roll_no) {

        $.ajax({
            type: 'post',
            url: 'exam_candidate.php',
            data: 'roll_no=' + roll_no,
            contenttype: "application/json; charset=utf-8",
            datatype: JSON,
            beforeSend: function() {
                $('.signature').css('display','none');
                $('#loader').addClass("spinner");  
                $("#next").prop('disabled',true);
                setTimeout(() => {
                    $('.signature').css('display','block');
                    $('#loader').removeClass("spinner");
                    $("#next").prop('disabled',false);
                }, 5000 );                
            },
            success: function(e) {
                data = jQuery.parseJSON(e);  
                console.log(data)             

                if (data[0]['error'] == 1) {
                    $("#form-msg").html('<span class="label label-danger">'+data[1]+'</span>');
                    $("#next").prop('disabled',true);
                    setTimeout(() => {
                        $('.signature').css('display','block');
                        $('#loader').removeClass("spinner");
                    }, 500);
                }

                if (data[0]['payment'] == 1) {
                    $('#form-error').html('<div class="row"><div class="col-sm-offset-0 col-lg-12"><div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><h2 style="color:#dc0000"><strong>'+data[1]+'</strong></h2></div></div></div>').css('color','red');
                    $("#next").prop('disabled',true);
                    setTimeout(() => {
                        $('.signature').css('display','block');
                        $('#loader').removeClass("spinner");
                    }, 500);
                }

                setTimeout(function(){ 
                    $("#form-msg").empty();
                    $("#next").prop('disabled',false); 
                    }, 2000);
            },
            error: function(e){
                    $('#loader').removeClass("spinner");
                }
        });

    } else {
        $("#next").prop('disabled',false);
        $("#form-msg").html('<span class="label label-danger">Please Enter your Register / Roll Number</span>');
        $("#roll_no").focus();        
    }

}

function get_candidate(roll_no) {

    var roll_no = $('#roll_no').val();

    if (roll_no) {
        $.ajax({
            type: 'post',
            url: 'exam_candidate.php',
            data: 'roll_no=' + roll_no,
            contenttype: "application/json; charset=utf-8",
            datatype: JSON,
            beforeSend: function() {
                $('.signature').css('display','none');
                $('#loader').addClass("spinner");  
                $("#next").prop('disabled', true);
            },
            success: function(e) {
                data = jQuery.parseJSON(e);
                //console.log(data);  return;
                var college_name,mobile, name, dep_name, email, fname;
                if (data[0]['error'] == 0) {

                    for (let i = 1; i < data.length; i++) {
                       college_name = data[i]['college_name'];
                       mobile       = data[i]['mobile'];
                       dep_name     = data[i]['dep_name'];
                       name         = data[i]['name'];
                       fname        = data[i]['f_name'];
                       email        = data[i]['email'];
                    }

                    setTimeout(() => {                     
                        $('#loader').removeClass("spinner"); 
                        $('.signature').css('display','inline');
                        $("#next").prop('disabled', false);
                    }, 1000 );

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
                    $("#form-msg").html('<span class="label label-danger">'+data[1]+'</span>').css('color','red');            
                    setTimeout(() => {
                        $('#form-error').html('<div class="row"><div class="col-sm-offset-0 col-lg-12"><div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><h2 style="color:#dc0000"><strong>'+data[1]+'</strong></h2></div></div></div>').css('color','red');
                        $('#loader').removeClass("spinner"); 
                        $('.signature').css('display','inline');
                        $("#next").prop('disabled', true);
                        $("#roll_no").focus();
                        $("#mobile").val('');  
                    }, 500 );
                    
                }

            }
        });

    } else {

        $("#form-msg").html('<span class="label label-danger">Please Enter your Register / Roll Number</span>').css('color','red');
        $("#roll_no").focus();

    }

}

$(document).ready(function() {

    $("#next").click(function() {

        $("#form-msg").empty();

        var mob = $("#mobile").val();

        if( mob != '' && mob.length == 10 ){

            $.ajax({

                url: 'otp_generation.php',
                type: 'POST',
                data: '',
                error: function() {
                    alert('Something is wrong..');
                },
                beforeSend: function(){
                    $('.signature').css('display','none');
                    $('#loader').addClass("spinner");  
                    $("#next").prop('disabled', true);
                },
                success: function(response) {

                    $("#otp_number").val(response);

                    /*
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

                      //xmlhttp.open("GET", "http://hpsms.dial4sms.com/api/web2sms.php?username=maharajads&password=Admin@18&to="+mob+"&sender=MMPLEX&message="+otp+"&response=Y", true);
                      //xmlhttp.open("GET", "https://sms.nettyfish.com/vendorsms/pushsms.aspx?apikey=28781b87-f68e-41e5-8956-305b33520415&clientId=7ee36543-dd6f-4c64-a42b-4b9784f9bcef&msisdn=" + mob + "&sid=MEIADM&msg=" + otp + "&fl=0&gwid=2", true);
					xmlhttp.open("GET", "https://sms.nettyfish.com/api/v2/SendSMS?ApiKey=28781b87-f68e-41e5-8956-305b33520415&ClientId=7ee36543-dd6f-4c64-a42b-4b9784f9bcef&MobileNumbers=" + mob + "&SenderId=MEIADM&message=" + otp + "&response=Y", true);

                      xmlhttp.send();*/

                      setTimeout(() => {
                      $('.signature').css('display','inline-block');
                      $('#loader').removeClass("spinner");  
                      $("#next").prop('disabled', false);

                      setTimeout(() => {
                        $('#otpModal').modal({
                          show: true,
                          backdrop: 'static',
                          keyboard: false,
                          mouse: false
                        });
                      }, 300);
                      
                    }, 500);

                }

            });

        } else {

            $('.signature').css('display','none');
              $('#loader').addClass("spinner");  
              $("#next").prop('disabled', true);
              $("#mobile").focus();
              setTimeout(() => {
                $("#form-msg").html('<span class="label label-danger">Please Enter Valid 10 Digits Mobile Number?</span>').css('color', 'red');
                $('.signature').css('display','inline');
                $('#loader').removeClass("spinner");  
                $("#next").prop('disabled', false);
              }, 1000);
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

function validate_payment_form(type) {

    $("#form-msg").empty();
    var order_id = $("#order_id").val();
    var college_id = $("#college_id").val();
    var cust_id = $("#roll_no").val();
    var mobile = $("#mobile").val();
    var email = $("#email").val();
    var amount = $("#amount").val();

    if (order_id && college_id && cust_id && mobile && email && amount >= 1) {

        $.ajax({
            type: 'post',
            url: 'payment_submit.php',
            data: 'order_id=' + order_id + '&college_id=' + college_id + '&cust_id=' + cust_id + '&mobile=' + mobile + '&email=' + email + '&amount=' + amount + '&gateway=' + type,
            beforeSend: function() {
                $('#eazypay').css('display','none');
                $('.paytm').css('display','none');
                $('#loader1').addClass("spinner");  
                $("#paytm").prop('disabled', true);       
            },
            success: function(e) {
                console.log(e);
                var res = e.split("|");
                //res[0] = Error [1 = Error, 0 = No error]
                //res[1] = Message
                if (res[0] != undefined && res[0] == 0) {
                    if(type == 'paytm'){
                        $("#payment_form").attr("action", "PaytmKit/paymentRedirect.php")
                      }
                      
                      if(type == 'icici'){
                        $("#payment_form").attr("action", "TxnRedirect.php")
                      }

                      setTimeout(() => {
                        $("#paynow_btn").click();
                    }, 500);
                }
                //Message
                if (res[1] != undefined) {
                    setTimeout(() => {
                        $("#form-msg").html('<span class="label label-danger">'+res[1]+'</span>');
                        $('#eazypay').css('display','none');
                        $('.paytm').css('display','none');
                        $('#loader1').addClass("spinner");  
                        $("#paytm").prop('disabled', true);
                    }, 1000);
                } else {
                    setTimeout(() => {
                        $("#form-msg").html('<span class="label label-danger">'+e+'</span>'); 
                        $('#eazypay').css('display','inline-block');
                        $('.paytm').css('display','inline-block');
                        $('#loader1').removeClass("spinner");  
                        $("#paytm").prop('disabled', false);
                    }, 1000);
                }
            }
        });

    } else if (order_id == "" || order_id == undefined) {

        $('#eazypay').css('display','none');
        $('.paytm').css('display','none');
        $('#loader1').addClass("spinner");  
        $("#paytm").prop('disabled', true);
        setTimeout(() => {
            $("#form-msg").html('<span class="label label-danger">Order ID missing..</span>'); 
            $('#eazypay').css('display','inline-block');
            $('.paytm').css('display','inline-block');
            $('#loader1').removeClass("spinner");  
            $("#paytm").prop('disabled', false);
        }, 1000);

    } else if (college_id == "" || college_id == undefined) {

        $('#eazypay').css('display','none');
        $('.paytm').css('display','none');
        $('#loader1').addClass("spinner");  
        $("#paytm").prop('disabled', true);
        setTimeout(() => {
            $("#form-msg").html('<span class="label label-danger">College ID missing..</span>'); 
            $('#eazypay').css('display','inline-block');
            $('.paytm').css('display','inline-block');
            $('#loader1').removeClass("spinner");  
            $("#paytm").prop('disabled', false);
        }, 1000);

    } else if (cust_id == "" || cust_id == undefined) {          
        $('#eazypay').css('display','none');
        $('.paytm').css('display','none');
        $('#loader1').addClass("spinner");  
        $("#paytm").prop('disabled', true);
        setTimeout(() => {
            $("#form-msg").html('<span class="label label-danger">Customer ID missing..</span>');
            $('#eazypay').css('display','inline-block');
            $('.paytm').css('display','inline-block');
            $('#loader1').removeClass("spinner");  
            $("#paytm").prop('disabled', false);
        }, 1000);
    } else if (mobile == "" || mobile == undefined) {

        $('#eazypay').css('display','none');
        $('.paytm').css('display','none');
        $('#loader1').addClass("spinner");  
        $("#paytm").prop('disabled', true);
        setTimeout(() => {
            $("#form-msg").html('<span class="label label-danger">Mobile number missing.</span>');
            $('#eazypay').css('display','inline-block');
            $('.paytm').css('display','inline-block');
            $('#loader1').removeClass("spinner");  
            $("#paytm").prop('disabled', false);
        }, 1000);

    } else if (email == "" || email == undefined) {

        $('#eazypay').css('display','none');
        $('.paytm').css('display','none');
        $('#loader1').addClass("spinner");  
        $("#paytm").prop('disabled', true);
        setTimeout(() => {
            $("#form-msg").html('<span class="label label-danger">Mobile number missing.</span>');
            $('#eazypay').css('display','inline-block');
            $('.paytm').css('display','inline-block');
            $('#loader1').removeClass("spinner");  
            $("#paytm").prop('disabled', false);
        }, 1000);

    } else if (amount < 1 || amount == "" || amount == undefined) {          
        $('#eazypay').css('display','none');
        $('.paytm').css('display','none');
        $('#loader1').addClass("spinner");  
        $("#paytm").prop('disabled', true);
        setTimeout(() => {
            $("#form-msg").html('<span class="label label-danger">Enter amount (Minimum Rs.1)</span>'); 
            $('#eazypay').css('display','inline-block');
            $('.paytm').css('display','inline-block');
            $('#loader1').removeClass("spinner");  
            $("#paytm").prop('disabled', false);
        }, 1000);
    } else {

        $('#eazypay').css('display','none');
        $('.paytm').css('display','none');
        $('#loader1').addClass("spinner");  
        $("#paytm").prop('disabled', true);
        setTimeout(() => {
            $("#form-msg").html('<span class="label label-danger">Unable submit data!</span>'); 
            $('#eazypay').css('display','inline-block');
            $('.paytm').css('display','inline-block');
            $('#loader1').removeClass("spinner");  
            $("#paytm").prop('disabled', false);
        }, 1000);
    }
}

$('#otpModal').modal({
    show: false,
    backdrop: 'static',
    keyboard: false,
    mouse: false
});

$(document).ready(function() {
    setTimeout(function(){ $('.alert').fadeOut(3000); }, 8000);
});
</script>

