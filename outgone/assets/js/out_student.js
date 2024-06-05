$(document).ready(function() {
    const input = document.getElementById('dob');
    const datepicker = new TheDatepicker.Datepicker(input);
    datepicker.render();
    $("#msg").hide();
    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".previous").click(function() {

        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar_2 li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1 - now) * 50) + "%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                position = 'relative';
                current_fs.css({
                    'left': left
                });
                previous_fs.css({
                    'transform': 'scale(' + scale + ')',
                    'opacity': opacity,
                    'position': position
                });
            },
            duration: 800,
            complete: function() {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $(".submit").click(function() {
        return true;
    });

});

function pay_now(gateway) {

    $('#offset').removeClass('col-md-offset-0').addClass('col-md-offset-4');
    $('#eazypay').css('display', 'none');
    $('.paytm').css('display', 'none');
    $('#loader1').addClass("spinner");
    $("#paytm").prop('disabled', true);

    var form = $("#msform");
	
    form.validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
        },
        rules: {
            reg_no: {
                required: true,
            },
            name: {
                required: true,
                //usernameRegex: true,
                //minlength: 6,
            },
            fname: {
                required: true,
                //usernameRegex: true,
                //minlength: 6,
            },
            dob: {
                required: true,
                //digits: true
            },
            gender: {
                required: true,
            },
            email: {
                required: true,
            },
            college: {
                required: true,
            },
            department: {
                required: true,
            },
            fee_type: {
                required: true,
            },
            out_year: {
                required: true,
            },
            email_id: {
                required: true,
            },
            amount: {
                required: true,
                //min: 1000,
            },
            agree_terms: {
                required: true,
            }
        },
        messages: {
            reg_no: {
                required: "Your role number required *",
            },
            name: {
                required: "Your Name required *",
            },
            fname: {
                required: "Your Name required *",
            },
            dob: {
                required: "Your DOB required *",
            },
            gender: {

                required: "Gender required *",
            },
            email: {
                required: "Your email required *",
            },
            college: {
                required: "College required *",
            },
            fee_type: {
                required: "Fee type required *",
            },
            department: {
                required: "Department required *",
            },
            out_year: {
                required: "Year required *",
            },
            amount: {
                required: "Enter amount *",
            },
            agree_terms: {
                required: "Please agree terms & conditions *",
            }
        }
    });

    if (form.valid() === true) {

        var mobile = $("#phone").val();
        var reg_no = $("#reg_no").val();
        var role_no = $("#role_no").val();
        var name = $("#name").val();
        var fname = $("#fname").val();
        var dob = $("#dob").val();
        var gender = $("#gender").val();
        var email = $("#email").val();
        var college = $("#college").val();
        var department = $("#department").val();
        var fee_type = $("#fee_type").val();
        var out_year = $("#out_year").val();
        var amount = $("#amount").val();

        var per = '&mobile=' + mobile + '&reg_no=' + reg_no + '&role_no=' + role_no + '&name=' + name + '&fname=' + fname + '&dob=' + dob + '&gender=' + gender + '&email=' + email + '&college=' + college + '&department=' + department + '&fee_type=' + fee_type + '&out_year=' + out_year + '&amount=' + amount + '&gateway=' + gateway;

        $.ajax({
            type: 'POST',
            url: 'student_submit.php',
            data: per + '&form=personal',
            beforeSend: function() {
                if ($('#offset').find('.col-md-offset-0')) {
                    $('#offset').removeClass('col-md-offset-0').addClass('col-md-offset-4');
                } else {
                    $('#offset').removeClass('col-md-offset-4').addClass('col-md-offset-0');
                }
                $('#eazypay').css('display', 'none');
                $('.paytm').css('display', 'none');
                $('#loader1').addClass("spinner");
                $("#paytm").prop('disabled', true);
            },
            success: function(e) {

                //console.log(e);

                if ($('#offset').find('.col-md-offset-0')) {
                    $('#offset').removeClass('col-md-offset-0').addClass('col-md-offset-4');
                } else {
                    $('#offset').removeClass('col-md-offset-4').addClass('col-md-offset-0');
                }

                var val = e.split("|");

                $("#gen_form").empty();

                if (val[0] == 0) {

                    $("#gen_form").append(val[2]);

                    if (gateway == 'paytm') {
                        $("#msform").attr("action", "PaytmKit/paymentRedirect.php")
                    }

                    if (gateway == 'icici') {
                        $("#msform").attr("action", "TxnRedirect.php")
                    }

                    $('#msform').submit();

                } else {

                    setTimeout(() => {
                        $('#eazypay').css('display', 'block');
                        $('.paytm').css('display', 'block');
                        $('#loader1').removeClass("spinner");
                        $("#paytm").prop('disabled', false);
                        $('#message').html(val[1]);
                    }, 500);
                }
            }
        });

    } else {
        setTimeout(() => {
            $('#offset').removeClass('col-md-offset-4').addClass('col-md-offset-0');
            $('#eazypay').css('display', 'block');
            $('.paytm').css('display', 'block');
            $('#loader1').removeClass("spinner");
            $("#paytm").prop('disabled', false);
        }, 500);
    }

}