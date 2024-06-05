function validate_form(action) {

    var mobile = $("#mobile").val();
    var otp_val = $("#" + action + "_captcha_val").val();

    if (mobile && mobile.length == 10 && otp_val) {

        captcha_for = action;

        $.ajax({
            type: 'post',
            url: 'otp_validation.php',
            data: 'captcha_val=' + captcha_val + '&captcha_for=' + captcha_for,
            beforeSend: function() {
                $("#error").html("Please wait...");
            },
            success: function(e) {
                if (e == 1) {

                    $("#error").html("<span style='color: #4caf50'>Captcha matched</span>");

                    $.ajax({
                        type: 'post',
                        url: 'register.php',
                        data: 'name=' + name + '&email=' + email + '&mobile=' + mobile + '&whatsapp=' + whatsapp + '&graduation=' + graduation + '&course=' + course + '&how_known=' + how_known + '&action=' + action,
                        beforeSend: function() {
                            $(".error").html("Validating...");
                        },
                        success: function(e) {

                            var res = (e).split("|");

                            if (res[0] == 1) {
                                $("#error").html("<span style='color: #09690d'>" + res[1] + "</span>");
                                window.location.reload();
                            } else {
                                $(".error").html(res[1]);
                            }
                        }
                    });
                } else {
                    $("#error").html("<span style='color: #f44'>" + e + "</span>");
                }
            }
        });
    }

    return false;
}

$(document).ready(function() {

    $("#msg").hide();

    $("#radio1").click(function() {
        var location = $(this).val();
        $("#location").val(location);

        $(".ms_form").hide(500);
        $(".ms_form").show(1000);
        $("#eng").show();
    });

    $("#radio2").click(function() {
        var location = $(this).val();
        $("#location").val(location);

        $(".ms_form").hide(500);
        $(".ms_form").show(1000);
        $("#bank_details").show(1000);
        $("#eng").show();
        //$("#arts").show();
        $("#dip").hide();
        $("#eng_col_1").hide();
        $("#eng_col_2").hide();
        $("#arts_college").show();
        $("#dip_col").hide();
    });

    $("#radio3").click(function() {
        var location = $(this).val();
        $("#location").val(location);

        $(".ms_form").hide(500);
        $(".ms_form").show(1000);
        $("#bank_details").show(1000);
        $("#eng").show();
        //$("#arts").hide();
        $("#dip").hide();
        $("#eng_col_1").hide();
        $("#eng_col_2").hide();
        $("#arts_college").hide();
        $("#dip_col").show();
    });

    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".next").click(function() {

        // Custom method to validate username
        $.validator.addMethod("usernameRegex", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]*$/i.test(value);
        }, "Username must contain only letters, numbers");

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
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                },
                role_number: {
                    required: true,
                },
                name: {
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
                year: {
                    required: true,
                },
                order_id: {
                    required: true,
                },
                cust_id: {
                    required: true,
                },
                mobile_no: {
                    required: true,
                    digits: true,
                    minlength: 10,
                },
                email_id: {
                    required: true,
                },
                TXN_AMOUNT: {
                    required: true,
                    //min: 1000,
                },
                agree_terms: {
                    required: true,
                }
            },
            messages: {
                mobile: {
                    required: "Your mobile number required *",
                    digit: true,
                },
                role_number: {
                    required: "Your role number required *",
                },
                name: {
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
                year: {
                    required: "Year required *",
                },
                amount: {
                    required: "Enter amount *",
                },
                order_id: {
                    required: "Order ID missing *",
                },
                cust_id: {
                    required: "Customer ID missing *",
                },
                mobile_no: {
                    required: "Mobile number missing *",
                },
                email_id: {
                    required: "Email ID missing *",
                },
                TXN_AMOUNT: {
                    required: "Enter amount *",
                },
                agree_terms: {
                    required: "Please agree terms & conditions *",
                }
            }
        });

        if (form.valid() === true) {

            if ($('#per_info').is(":visible")) {

                var id = $("#s_no").val();
                var location = $("#location").val();
                var orderid = $("#orderid").val();
                var mobile = $("#mobile").val();
                var role_no = $("#role_number").val();
                var name = $("#name").val();
                var dob = $("#dob").val();
                var gender = $("#gender").val();
                var email = $("#email").val();
                var year = $("#year").val();
                //var amount = $("#amount").val();

                var per = 'id=' + id + '&orderid=' + orderid + '&location=' + location + '&mobile=' + mobile + '&role_no=' + role_no + '&name=' + name + '&dob=' + dob + '&gender=' + gender + '&email=' + email + '&year=' + year;

                $.ajax({
                    type: 'POST',
                    url: 'bed_register.php',
                    data: per + '&form=personal',
                    success: function(e) {
                        console.log(e);

                        $('#order_id').val(orderid);
                        $('#cust_id').val(role_no);
                        $('#mobile_no').val(mobile);
                        $('#email_id').val(email);

                        var val = e.split("|");

                        if (val[0] == 1) {

                            // $.get('destroy_session.php', function () {
                            // 	//alert("Session Destroyed..");									
                            // });

                            if (val[2] != undefined) {
                                $("#s_no").val(val[2]);
                            }
                        } else {
                            alert(val[1]);
                        }
                    }
                });

                current_fs = $('#per_info');
                next_fs = $('#pay_info');
                if (animating) return false;
                animating = true;
                $("#progressbar_2 li").eq($("fieldset").index(next_fs)).addClass("active");

            }

            next_fs.show();
            current_fs.hide();
        }

        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50) + "%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale(' + scale + ')',
                    'position': 'absolute'
                });
                next_fs.css({
                    'left': left,
                    'opacity': opacity
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


change_captcha('admission');

function validate_captcha(captcha_for) {
    var captcha_val = $("#" + captcha_for + "_captcha_val").val();
    if (captcha_val) {
        $.ajax({
            type: 'post',
            url: 'captcha_validation.php',
            data: 'captcha_val=' + captcha_val + '&captcha_for=' + captcha_for,
            beforeSend: function() {
                $("#" + captcha_for + "_captcha_result").html("Please wait...");
            },
            success: function(e) {
                if (e == 1) {
                    $("#" + captcha_for + "_captcha_result").html("<span style='color: #4caf50'>Captcha matched</span>");
                    $("#submit-btn").click();
                } else {
                    $("#" + captcha_for + "_captcha_result").html("<span style='color: #f44'>" + e + "</span>");
                }
            }
        });
    } else {
        $("#" + captcha_for + "_captcha_result").html("<span style='color: #f44'>Enter answer</span>");
    }
}

function change_captcha(captcha_for) {
    $.ajax({
        type: 'post',
        url: 'captcha_validation.php',
        data: 'captcha_for=' + captcha_for,
        beforeSend: function() {
            $("#" + captcha_for + "_captcha_result").html("<span style='color: #f90'>Captcha loading ...</span>");
        },
        success: function(e) {
            $("#" + captcha_for + "_captcha_wrap").html(e);
            $("#" + captcha_for + "_captcha_result").html("&nbsp;");
        }
    });
}

$(document).ready(function() {

    $('#agree_terms').click(function() {

        if ($(this).prop("checked") == false) {
            //$('.enableAgree_Terms').prop('disabled', true);
            $('.enableAgree_Terms').css('opacity', '0.5');
        } else {
            //$('.enableAgree_Terms').prop('disabled', false);
            $('.enableAgree_Terms').css('opacity', '1');
        }
    });

    $("#radio1").click(function() {

        $("#otpModal").hide();
        var mobile = $("#mobile").val();

        if (mobile.length == 10 && mobile != "") {

            $.ajax({

                url: 'otp_generation.php',
                type: 'POST',
                data: {
                    'mobile': mobile
                },
                error: function() {
                    alert('Something is wrong');
                },
                success: function(response) {
                    //console.log(response);
                }
            });

            $('#otpModal').modal({
                show: false,
                backdrop: "static",
                keyboard: false,
                mouse: false
            });

            setTimeout(function() {
                $("#otpModal").modal("toggle");
            }, 2000);
        }

    });

    $("#radio3").click(function() {

        $("#otpModal").hide();
        var mobile = $("#mobile").val();

        if (mobile.length == 10 && mobile != "") {

            $.ajax({

                url: 'otp_generation.php',
                type: 'POST',
                data: {
                    'mobile': mobile
                },
                error: function() {
                    alert('Something is wrong');
                },
                success: function(response) {
                    //console.log(response);
                }
            });

            $('#otpModal').modal({
                show: false,
                backdrop: "static",
                keyboard: false,
                mouse: false
            });

            setTimeout(function() {
                $("#otpModal").modal("toggle");
            }, 2000);
        }

    });

    $("#radio2").click(function() {

        $("#otpModal").hide();
        var mobile = $("#mobile").val();

        if (mobile.length == 10 && mobile != "") {

            $.ajax({

                url: 'otp_generation.php',
                type: 'POST',
                data: {
                    'mobile': mobile
                },
                error: function() {
                    alert('Something is wrong');
                },
                success: function(response) {
                    //console.log(response);
                }
            });

            $('#otpModal').modal({
                show: false,
                backdrop: "static",
                keyboard: false,
                mouse: false
            });

            setTimeout(function() {
                $("#otpModal").modal("toggle");
            }, 2000);
        }

    });
});