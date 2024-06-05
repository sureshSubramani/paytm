<?php

include 'config.php';

if(session_id() == '') {
    session_start();
}

if(isset($_POST['otp_val']) && $_POST['otp_val'] != ""){

    $resultset[0]['isMatched'] = 2;

    //VALIDATION
    if(isset($_POST['otp_val']) && $_POST['otp_val'] != ""){	

        $otp_val = $_POST['otp_val'];
        
        if(isset($_SESSION['mobile_otp']) && $_SESSION['mobile_otp'] == $otp_val){

            $resultset[0]['isMatched'] = 1;
            if(isset($_REQUEST)){
                $mobile = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";

                if($mobile){

                    $sql = "SELECT * FROM out_student_details WHERE (mobile='".$mobile."')  AND status=1 LIMIT 0,1";
                    $result = mysqli_query($con,$sql);

                    if($result->num_rows > 0){	
                                            
                        while($row = mysqli_fetch_assoc($result)) {
                            $resultset[] = $row;
                        }
                        
                    }else{
                        $resultset[] = 'No record found!';
                    }

                    mysqli_close($con);
                
                }
            }

            echo json_encode($resultset);
        }
        else{
            $resultset[0]['isMatched'] = 0;
            echo json_encode($resultset);
        }
    }
    else{        
        echo json_encode($resultset);
    }
}else{
    
    //OTP 6 digit random generation
    $randomid = mt_rand(100000,999999);
    $_SESSION["mobile_otp"] = $randomid;
    echo $_SESSION["mobile_otp"];

}

?>