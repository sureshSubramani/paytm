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
        background-image: url("https://mahendra.org/hostelfee/images/payment-bg.jpg");
        background-position: 50% 50%;
        background-attachment: fixed;
        background-size: cover;
    }

    .table {
        width: 100%;
        max-width: 500px;
        border-top: none;
        margin: 0px auto;
        margin-top: 0px;
        margin-bottom: 0px;
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
		font-size: 2.5rem;
    }
    .header {
        background: #2f2e58;
        color: white;
        opacity: 0.95;
    }
    .mt-1{
        margin-top:10px;
    }
    .table>tbody>tr>td{
        vertical-align: initial !important;
    }

</style>

<!-- Start main-content -->
<div class="main-content" style="height: 100%; min-height: 500px">
    <div class="col-md-4 col-lg-4 col-md-offset-4">
        <div class="form-wrap mt-1">
            <form action="PaytmKit/hostelPaymentRedirect.php" method="post" name="payment_form" class="payment_form" id="payment_form">
                <table class="table borderless" style="margin-top:0px !important">
                    <tr class='form_content'>
                        <td colspan="3">
                            <h3 class="text-center">Online Payment - Mess Bill</h3>
                        </td>
                    </tr>
                    <tr class='form_content'>
                        <td>College Name</td>
                        <td>
                            <select name="COLLEGE_ID" id="college_name" class="form-control select_college">
                                <option value="">-- Select --</option>
                            <?php
                                $query = "SELECT DISTINCT college_name FROM hostel_candidate"; // ORDER BY college_name DESC";
                                $result = mysqli_query($con,$query) Or die('<p class="alert">' . mysqli_error() . '</p>');
                                // *** How many records do we have.
                                $num_records = mysqli_num_rows($result);

                                for ($j = 1; $j <= $num_records; $j++) {
                                    $row = mysqli_fetch_array($result);
                                    $col_name = $row['college_name'];
                                    echo "<option value=\"$col_name\">$col_name</option>\n";
                                }                                
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr class='form_content'>
                        <td>College Roll No / Hostel Code</td>
                        <td>
                            <input type="text" name="CUST_ID" id="roll_no" placeholder="Enter roll no / Hostel code" class="form-control select_college" onblur="get_candidate(this.value)" required>
                        </td>
                    </tr>
                    <tr class='form_content'>
                        <td>Mobile</td>
                        <td>
                            <input type="text" name="MOBILE_NO" id="mobile" minlength="10" maxlength="10" class="form-control select_college" placeholder="Max. 10 digits" required>&nbsp;

                        </td>
                    </tr>
                    <tr class="candidate_info month_info" style="display: none;">
                        <td colspan="2">
                            <h3 class="text-left month_info">Online Payment - Mess Bill</h3>
                        </td>
                    </tr>
                    <tr class="candidate_info name_info" style="display: none">
                        <td>Stundent Name</td>
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
                    <tr class="candidate_info dep_info" style="display: none; border-bottom: 1px solid #ccc;">
                        <td>Department</td>
                        <td><span id="dep_name"></span></td>
                    </tr>
                    <tr class="month_year_info">
                        <table class="table table-striped table-bordered candidate_info" id="no_of_mess_amt" style="display: none">
                            <tr>
                                <td colspan="5"><strong>Pending Mess Bills for the Month of</strong></td>
                            </tr>
                        </table>
                    </tr>
                    <tr class="candidate_amount">
                        <table class="table table-striped table-bordered candidate_info" style="display: none">
                            <tr style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
                                <td colspan="5" style="font-weight:bold;text-align:right">Total Amount</td>
                                <td class="text-right candidate_amount" style="font-weight:bold;">Rs. <span id="candidate_amount"></span></td>
                            </tr>
                        </table>
                    </tr>
                    <tr class="candidate_info" style="display: none">
                        <table class="table borderless candidate_info" style="display: none">
                            <tbody>
                                <td><strong class="month_info" style="font-size:14px">COVID Disclaimer:</strong>
                                    <span style="font-size:12px"> Be Responsible &nbsp;|&nbsp; Stay Home &nbsp;|&nbsp; Be
                                        Safe.</span>
                                </td>
                            </tbody>
                        </table>
                    </tr>
                    <tr>
                        <table class="table borderless">
                            <td colspan="2" style="text-align: center; paddig-top:-30px; position:relative;">
                                <div id="form-msg" style="text-align: center; font-weight: bold; padding: 10px 0"></div>
                                <input type="hidden" name="MONTHS" id="months" required>
                                <input type="hidden" name="EMAIL" id="email" required>
                                <input type="hidden" name="TXN_AMOUNT" id="amount" required>
                                <input type="hidden" name="ORDER_ID" id="order_id" value="<?php echo date("Ymdhis") ?>" required>
                                <input type="submit" id="paynow_btn" style="display: none;" value="">
                                <input type="button" id="paynow_btn2" class="btn btn-sm btn-success" onclick="validate_payment_form()" style="display: none;" value="Pay Now">
                                <input type="button" id="next" class="btn btn-sm btn-success" onclick="check_candidate()" value="Next">
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
                <form id="otp_form" method="post" name="form">
                    <div class="col-md-12">
                        <h5 class="otp-title text-center">Verify OTP Number</h5>
                        <div class="form-check-inline">
                            <div class="form-check-inline col-md-8">
                                <input type="text" class="form-control" id="otp_number" name="otp_number" maxlength="6" value="" placeholder="<?php  echo isset($_SESSION[" mobile_otp "])?$_SESSION[" mobile_otp "]:" "; ?>" autocomplete="off" required />
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
<?php //include('footer.php');?>

<script>

    $(".candidate_info").hide();

    function check_candidate() {

        var college_name = $('#college_name').val();
        var roll_no = $('#roll_no').val();

        if (roll_no && college_name) {
            $.ajax({
                type: 'post',
                url: 'hostel_get_candidate.php',
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
        } else if (!college_name) {
            $("#form-msg").html('Select College Name').css('color', 'red');;
            $("#college_name").focus();
        } else if (!roll_no) {
            $("#form-msg").html('Enter Collge Roll Number/Hostel Code').css('color', 'red');;
            $("#roll_no").focus();
        }

    }

    function get_candidate(roll_no) {

        var college_name = $('#college_name').val();
        var roll_no = $('#roll_no').val();

        if (college_name && roll_no) {
            $.ajax({
                type: 'post',
                url: 'hostel_get_candidate.php',
                data: 'roll_no=' + roll_no + '&college_name=' + college_name,
                contenttype: "application/json; charset=utf-8",
                datatype: JSON,
                beforeSend: function() {
                    $("#form-msg").html("<img src='images/spinner/spinner.gif' width='50' />");
                },
                success: function(e) {
                    //console.log(e);
                    data = jQuery.parseJSON(e);
                    let mobile, name, dep_name, email, fname, listOfMonths = [];
                    let no_of_mess_amt = "";
                    let sum = 0;
                    $('#next').slideDown();
                    $("#form-msg").empty();
                    if (data[0]['error'] == 0) {

                        no_of_mess_amt = "<tr class='thead-light'><th width='170'>Month&Year</th><th width='100'>Mess Bill</th><th width='100'>Other Fee</th><th width='100'>Fee</th><th width='100'>Sub Total</th></tr>";

                        for (let i = 1; i < data.length; i++) {
                            var sub_total = 0, total_fine = 0;
                            mobile = data[i]['mobile'];
                            dep_name = data[i]['dep_name'];
                            name = data[i]['name'];
                            fname = data[i]['f_name'];
                            email = data[i]['email'];
                            //listOfMonths = listOfMonths + data[i]['month_year'];
                            listOfMonths.push(data[i]['month_year']);
                            listOfMonths.join(',');

                            if (data[i]['payment_status'] == 0) {

                                total_fine = parseInt(data[i]['fine']) - parseInt(data[i]['concession_fee']);
                                sub_total += parseInt(data[i]['amount']) + parseInt(data[i]['other_fee']) + total_fine;
                                sum += sub_total;

                                no_of_mess_amt += '<tr><td class="enableCheckbox"><input type="checkbox" class="checkbox" style="display:inline-block" Checked/> &nbsp;<span class="month_year">' + data[i]['month_year'] + '</span></td><td>' + data[i]['amount'] + '</td><td>' + data[i]['other_fee'] + '</td><td>' + total_fine + '</td> + <td align="right">Rs. <span class="sub_total">' + sub_total + '</span></td></tr>';
                            }
                        }

                        $("#mobile").val(mobile);
                        $("#name").val(name);
                        $("#dep_name").val(dep_name);
                        $("#email").val(email);
                        $('#months').val(listOfMonths);
                        $("#amount").val(sum);
                        $("#candidate_college").html(college_name);
                        $("#candidate_roll_no").html(roll_no);
                        $("#candidate_mobile").html(mobile);
                        $("#candidate_name").html(name);
                        $("#dep_name").html(dep_name);
                        $("#father_name").html(fname);

                        if (sum > 0) {
                            $("#candidate_amount").html(sum);
                            $("#form-msg").empty();
                            $('#next').slideDown();
                        } else {
                            $("#candidate_amount").html('');
                            $("#form-msg").html('No pending payment!').css('color', 'red');
                            $('#next').slideUp();
                        }
                        $('#no_of_mess_amt').append(no_of_mess_amt);

                    } else {
                        $("#form-msg").html(data[1]).css('color', 'red');;
                    }

                }
            });
        } else if (!college_name) {
            $("#form-msg").html('Select College Name').css('color', 'red');;
            $("#college_name").focus();
        } else if (!roll_no) {
            $("#form-msg").html('Enter Collge Roll Number/Hostel Code').css('color', 'red');;
            $("#roll_no").focus();
        }

    }

    $('table').on('click', 'td.enableCheckbox,.checkbox', function() {
        var row = $(this).closest('tr');
        var sum = 0;
        var listOfMonths = $('#months').val();
        var splitArray = listOfMonths.split(',');

        if (row.find(".checkbox").is(':checked')) {
            row.find(".checkbox").prop("checked", false);
            sum = parseInt($('#candidate_amount').text()) - parseInt($(row).find('.sub_total').text());

            for (var i = 0; i <= splitArray.length; i++) {
                if (splitArray[i] == $(row).find('.month_year').text()) {
                    splitArray.splice(splitArray.indexOf($(row).find('.month_year').text()), 1);
                }
            }


        } else {
            row.find(".checkbox").prop("checked", true);
            $('#months').val('');
            sum = parseInt($('#candidate_amount').text()) + parseInt($(row).find('.sub_total').text());
            splitArray.push($(row).find('.month_year').text());
            splitArray.join(',');
        }

        $('#candidate_amount').html(sum);
        $('#months').val(splitArray);
        $('#amount').val(sum);

    });


    $('table').on('change', '.checkbox', function() {
        var row = $(this).closest('tr');
        var sum = 0;
        var listOfMonths = $('#months').val();
        var splitArray = listOfMonths.split(',');

        if (row.find(".checkbox").is(':checked')) {
            $('#months').val('');
            sum = parseInt($('#candidate_amount').text()) + parseInt($(row).find('.sub_total').text());
            splitArray.push($(row).find('.month_year').text());
            splitArray.join(',');
        } else {
            sum = parseInt($('#candidate_amount').text()) - parseInt($(row).find('.sub_total').text());
            for (var i = 0; i <= splitArray.length; i++) {
                if (splitArray[i] == $(row).find('.month_year').text()) {
                    splitArray.splice(splitArray.indexOf($(row).find('.month_year').text()), 1);
                }
            }
        }

        $('#candidate_amount').html(sum);
        $('#months').val(splitArray);
        $('#amount').val(sum);

    });

    $(document).ready(function() {

        $("#next").click(function() {

            let mob = $("#mobile").val();

            if (mob != '') {

                $.ajax({

                    url: 'otp_generation.php',
                    type: 'POST',
                    data: '',
                    error: function() {
                        alert('Something is wrong');
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
                data: {
                    'otp_val': otp_no
                },
                error: function() {
                    alert('Something is wrong');
                },
                success: function(response) {

                    //console.log(response);
                    var res = response.split("|");

                    if (res[0] == 1) {
                        $(".candidate_info").show();
                        $("#no_of_mess_amt").show();
                        $("#otpModal").modal("toggle").hide();
                        $("#next").hide();
                        $("#mob-verify").show(300);
                        $("#college_name").attr("readonly", true);
                        $("#roll_no").attr("readonly", true);
                        $("#mobile").attr("readonly", true);
                        $(".form_content").slideUp("Fast");
                        $("#paynow_btn2").slideDown("Fast");
                        $("#mobile_verify_btn").slideUp("Fast");
                        $("#form-msg").html('');
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
        var college_id = $("#college_name").val();
        var cust_id = $("#roll_no").val();
        var name = $("#name").val();
        var mobile = $("#mobile").val();
        var email = $("#email").val();
        var amount = $("#amount").val();
        var months = $("#months").val();

        if (order_id && college_id && cust_id && mobile && months && email && amount >= 1) {

            $.ajax({
                type: 'post',
                url: 'hostel_payment_submit.php',
                data: 'order_id=' + order_id + '&college_id=' + college_id + '&cust_id=' + cust_id + '&name=' +
                    name + '&mobile=' + mobile + '&email=' + email + '&amount=' + amount + '&months=' + months,
                beforeSend: function() {
                    $("#form-msg").html('Please wait...');
                },
                success: function(e) {
                    var res = e.split("|");
                    console.log(e);
                    console.log(res);
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
        } else if (months == "" || months == undefined) {
            $("#form-msg").html('<span style="color:#f96">Months missing.</span>');
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