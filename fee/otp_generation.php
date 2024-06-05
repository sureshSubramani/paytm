<?php

//include 'config.php';

if(session_id() == '') {
    session_start();
}

if(isset($_POST['otp_val']) && $_POST['otp_val'] != ""){

    //VALIDATION
    if(isset($_POST['otp_val']) && $_POST['otp_val'] != ""){	

        $otp_val = $_POST['otp_val'];
        
        if(isset($_SESSION['mobile_otp']) && $_SESSION['mobile_otp'] == $otp_val){
            echo "1|OTP Matched";
        }
        else{
            
            echo "2|Invalid OTP";
            //echo "<span style='color: #f44;font-size:12px;'>OTP does not matched</span>";
        }
    }
    else{

        echo "3|Enter your OTP";
    }
}

else{
    
    //OTP 6 digit random generation
    $randomid = mt_rand(100000,999999);
    $_SESSION["mobile_otp"] = $randomid;
    echo $_SESSION["mobile_otp"];

}

?>