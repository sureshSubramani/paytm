$('#submit').append('<span class="signature">NEXT</span>');
$('#paytm').append('<span class="paytm"><span><img src="images/logo/paytm.png" alt="Pay via Paytm" /></span> <span>PAY NOW</span></span>');
$('#eazypay').append('<span class="eazypay"><span><img src="images/logo/eazypay.png" alt="Pay via ICICI" /></span><span>PAY NOW</span></span>');

$('.form-group').html(function(i, h) {
    return h.replace(/&nbsp;/g, '');
});

$("#submit").click(function(e) {

    e.preventDefault();

    var mobile = $("#mobile").val();

    if (mobile.length == 10 && mobile != "") {
        $.ajax({

            url: 'otp_generation.php',
            type: 'POST',
            data: { 'mobile': mobile },
            beforeSend: function() {
                $("#msg").empty();
                $('.signature').css('display', 'none');
                $('#loader').addClass("spinner");
                $("#submit").prop('disabled', true);
            },
            error: function() {
                alert('Something is wrong');
            },
            success: function(response) {
                console.log(response);
                //$("#otp_number").attr("placeholder", response);
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

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        alert("Alert message has been sent!");
                    }
                }

                //xmlhttp.open("GET", "http://hpsms.dial4sms.com/api/web2sms.php?username=maharajads&password=Admin@18&to="+mobile+"&sender=MMPLEX&message="+otp+"&response=Y", true);
                xmlhttp.open("GET", "https://sms.nettyfish.com/vendorsms/pushsms.aspx?apikey=28781b87-f68e-41e5-8956-305b33520415&clientId=7ee36543-dd6f-4c64-a42b-4b9784f9bcef&msisdn=" + mobile + "&sid=MEIADM&msg=" + otp + "&fl=0&gwid=2", true);

                xmlhttp.send();

                setTimeout(() => {
                    $('.signature').css('display', 'block');
                    $('#loader').removeClass("spinner");
                    $("#otpModal").modal("toggle");
                    $("#mobile").focus();
                    $("#submit").prop('disabled', false);
                }, 600);
            }
        });

    } else {
        $('.signature').css('display', 'none');
        $('#loader').addClass("spinner");
        $("#submit").prop('disabled', true);
        setTimeout(() => {
            $('.signature').css('display', 'block');
            $('#loader').removeClass("spinner");
            $('#msg').html('<span class="label label-danger">Enter 10 digits valid mobile number</span>').show();
            $("#mobile").focus();
            $("#submit").prop('disabled', false);
        }, 600);
    }

});

$('#verify-btn').click(function(e) {

    e.preventDefault();

    $('#otpModal').modal({
        show: false,
        backdrop: "static",
        keyboard: false,
        mouse: false
    });

    var otp_number = $("#otp_number").val();
    var mobile = $("#mobile").val();

    $.ajax({

        url: 'otp_generation.php',
        type: 'POST',
        data: 'otp_val=' + otp_number + '&mobile=' + mobile,
        error: function() {
            alert('Something is wrong');
        },
        success: function(response) {
            console.log(response);
            var is_val = $.parseJSON(response);
            //console.log(is_val);

            $('#is-error').empty();

            if (is_val[0]['isMatched'] == 0)
                $('#is-error').html('<span class="label label-danger">Invalid OTP</span>');

            if (is_val[0]['isMatched'] == 2)
                $('#is-error').html('<span class="label label-danger">Please enter OTP</span>');

            if (is_val[0]['isMatched'] == 1) {

                $("#otpModal").modal("toggle").hide();
                $(".sp_form").slideUp(500);
                $("#msg").html('');
                $(".ms_form").show(1000);
                $("#phone").val($("#mobile").val());
                $('#paynow_btn').slideDown();

                if (is_val[1]['reg_no'])
                    $('#reg_no').val(is_val[1]['reg_no']);
                if (is_val[1]['roll_no'])
                    $('#role_no').val(is_val[1]['roll_no']);
                if (is_val[1]['name'])
                    $('#name').val(is_val[1]['name']);
                if (is_val[1]['f_name'])
                    $('#fname').val(is_val[1]['f_name']);
                if (is_val[1]['dob'])
                    $('#dob').val(is_val[1]['dob']);
                if (is_val[1]['gender'])
                    $('#gender').val(is_val[1]['gender']);
                if (is_val[1]['email'])
                    $('#email').val(is_val[1]['email']);
                if (is_val[1]['college_name'])
                    $('#college').val(is_val[1]['college_name']);
                if (is_val[1]['dep_name'])
                    $('#department').val(is_val[1]['dep_name']);
                if (is_val[1]['outgone_year'])
                    $('#out_year').val(is_val[1]['outgone_year']);
            }
        }

    });
});

$('#reg_no').blur(function(e) {

    e.preventDefault();

    var reg_no = $("#reg_no").val();

    if (reg_no) {
        $.ajax({

            url: 'get_candidate.php',
            type: 'POST',
            data: {
                'reg_no': reg_no
            },
            error: function() {
                alert('Something is wrong');
            },
            success: function(response) {
                //console.log(response);
                var json_obj = $.parseJSON(response);
                //console.log(json_obj);

                if (json_obj[0]['error'] == 0) {

                    if (json_obj[1]['roll_no'])
                        $('#role_no').val(json_obj[1]['roll_no']);
                    if (json_obj[1]['name'])
                        $('#name').val(json_obj[1]['name']);
                    if (json_obj[1]['f_name'])
                        $('#fname').val(json_obj[1]['f_name']);
                    if (json_obj[1]['dob'])
                        $('#dob').val(json_obj[1]['dob']);
                    if (json_obj[1]['gender'])
                        $('#gender').val(json_obj[1]['gender']);
                    if (json_obj[1]['email'])
                        $('#email').val(json_obj[1]['email']);
                    if (json_obj[1]['college_name'])
                        $('#college').val(json_obj[1]['college_name']);
                    if (json_obj[1]['dep_name'])
                        $('#department').val(json_obj[1]['dep_name']);
                    if (json_obj[1]['outgone_year'])
                        $('#out_year').val(json_obj[1]['outgone_year']);

                }
            }

        });
    }

});

$('#role_no').blur(function(e) {

    e.preventDefault();

    var roll_no = $("#role_no").val();

    if (roll_no) {
        $.ajax({

            url: 'get_candidate.php',
            type: 'POST',
            data: {
                'roll_no': roll_no
            },
            error: function() {
                alert('Something is wrong');
            },
            success: function(response) {
                //console.log(response);
                var json_obj = $.parseJSON(response);
                //console.log(json_obj);

                if (json_obj[0]['error'] == 0) {

                    if (json_obj[1]['reg_no'])
                        $('#reg_no').val(json_obj[1]['reg_no']);
                    if (json_obj[1]['name'])
                        $('#name').val(json_obj[1]['name']);
                    if (json_obj[1]['f_name'])
                        $('#fname').val(json_obj[1]['f_name']);
                    if (json_obj[1]['dob'])
                        $('#dob').val(json_obj[1]['dob']);
                    if (json_obj[1]['gender'])
                        $('#gender').val(json_obj[1]['gender']);
                    if (json_obj[1]['email'])
                        $('#email').val(json_obj[1]['email']);
                    if (json_obj[1]['college_name'])
                        $('#college').val(json_obj[1]['college_name']);
                    if (json_obj[1]['dep_name'])
                        $('#department').val(json_obj[1]['dep_name']);
                    if (json_obj[1]['outgone_year'])
                        $('#out_year').val(json_obj[1]['outgone_year']);

                }
            }

        });
    }

});

$('#otpModal').modal({
    show: false,
    backdrop: "static",
    keyboard: false,
    mouse: false
});