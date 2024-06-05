<?php

if(session_id() == '') {
    session_start();
}

require('../config.php');

if(isset($_REQUEST['otp_value']) && $_REQUEST['otp_value'] != ""){

    	//$resultset[0]['isMatched'] = 2;	

        $secret_code = $_REQUEST['otp_value'];
        
        //if(isset($_SESSION['mobile_otp']) && $_SESSION['mobile_otp'] == $secret_code){

            $resultset[0]['isMatched'] = 1;

            if(isset($_REQUEST)){
                
                $mobile = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";

                if($mobile){

                    $sql = "SELECT * FROM out_student_details WHERE mobile='$mobile'  AND status=1 LIMIT 0,1";
                    $result = mysqli_query($con,$sql);
                    $college = '';

                    if($result->num_rows > 0){	
						$resultset[1]['isRecord'] = 1;          
                        while($row = mysqli_fetch_assoc($result)) {
                            $resultset['data'] = $row;
                            $college = $row['college_name'];
                        }

                        $api = "SELECT merchant_id,merchant_key FROM paytm_merchent_details WHERE college='".$college."' AND status=1";
                        $res = mysqli_query($con,$api);
                        if($res->num_rows > 0){
                            while($record = mysqli_fetch_assoc($res)) {
                                $resultset['api'] = $record;
                            }
                        }
                        
                    }else{
						$resultset[1]['isRecord'] = 0;
                        $resultset['data'] = 'No record found!';
                    }

                    mysqli_close($con);
                
                }
            }

            echo json_encode($resultset);

        /*}else{
            $resultset[0]['isMatched'] = 0;
           echo json_encode($resultset);
        }*/

}else if(isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != ''){       
	//OTP 6 digit random generation
    //unset($_SESSION['mobile_otp']);
	$random_code = mt_rand(100000,999999);
    //$_SESSION['mobile_otp'] = $random_code;
	//echo json_encode(array('secret_code'=>$random_code));
	$mobile = $_REQUEST['mobile'];
	$res = send_sms($mobile,$random_code);
	echo json_encode($res);
}else{
	echo json_encode(array('status'=>400,'error'=>false,'message'=>'failure'));
}  

function send_sms($mobile,$random_code){
		
    $msg = "OTP is ".$random_code." valid for this transaction on  ".date('d-M-Y').". Please Do not share this OTP to anyone for security reasons. MAHENDRA";    
     
    $opt_url = "https://sms.nettyfish.com/api/v2/SendSMS?ApiKey=28781b87-f68e-41e5-8956-305b33520415&ClientId=7ee36543-dd6f-4c64-a42b-4b9784f9bcef&MobileNumbers=".$mobile."&SenderId=MEIADM&message=".urlencode($msg)."&response=Y";

    try {

        $curl = curl_init();
        if (FALSE === $curl)
            throw new Exception('failed to initialize');

        curl_setopt($curl, CURLOPT_URL, $opt_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_PROXYPORT, "80");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        $data = ['status'=>200,'error'=>false,'message'=>'success','code'=>$random_code];
        return $data;
        exit;
        // echo 'success'; exit;
        if (FALSE === $result) {				
            throw new Exception(curl_error($curl), curl_errno($curl));
        } 
        else {
            //return $result;
            if (strpos($result, 'GID') !== false) { 
                print_r('success');
            }					
        }

    }catch (Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);

        $data = ['status'=>400,'error'=>false,'message'=>'failure'];
        return $data;			
    }
    
    curl_close($curl); 
}

?>