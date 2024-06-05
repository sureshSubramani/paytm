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

        echo "3|Enter the OTP";
    }
}

else{

    unset($_SESSION["mobile_otp"]);
    //OTP 6 digit random generation
    $randomid = mt_rand(100000,999999);
    $_SESSION["mobile_otp"] = $randomid;
    //echo $_SESSION["mobile_otp"];
    // sms($randomid,'8248182544');
    sms($randomid,$_POST['mobile']);

}

function sms($otp,$mobile_no){

    // $key = "MDnJqWhC917suhaa";
    // $senderid="MEIADM";
    // $templateid="1707161528992948300";
    // $mob = $mobile_no;
    $date = date('d/F/Y');



    // echo "<pre>";print_r($key.'||'.$senderid.'||'.$templateid.'||'.$mob);die;

    // $message_content=urlencode("Please verirfy mobile number, Your OTP is ".$otp." and expired within 7 mins.");
    // echo "<pre>";print_r($key.'||'.$senderid.'||'.$templateid.'||'.$mob.'||'.$message_content);

    $url = "http://bulksms.2020sms.com/vb/apikey.php?apikey=MDnJqWhC917suhaa&senderid=MEIADM&templateid=1707161528992948300&number=".$mobile_no."&message=OTP is ".$otp." valid for this transaction on ".$date.". Please Do not share this OTP to anyone for security reasons. MAHENDRA HSTL";

    /*CALLIN API BY CURL METHOD*/   
    $ch = curl_init($url);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $url);    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);    
    $response = curl_exec($ch);
    curl_close($ch);

    print_r($response);
}

?>