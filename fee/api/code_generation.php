<?php

if(session_id() == '') {
    session_start();
}

require('../config.php');

$data = array();

$message = array("verify_otp"=>false);

if(isset($_REQUEST['otp_value']) && $_REQUEST['otp_value'] != ""){
	
	//VALIDATION
	if(isset($_REQUEST['otp_value']) && $_REQUEST['otp_value'] != ""){	

		$code = $_REQUEST['otp_value'];

		//if(isset($_SESSION['otp']) && $_SESSION['otp'] == $code){

			$message = array("verify_otp"=>true);

			if(isset($_REQUEST['college_id']) && $_REQUEST['college_id'] != ""){        
				$college_id = isset($_REQUEST['college_id'])?$_REQUEST['college_id']:"";
				$sql        = "SELECT college,merchant_id,merchant_key FROM paytm_merchent_details WHERE college = '$college_id' AND status=1 LIMIT 0,1";
				$result     = $con->query($sql);
				$res        = mysqli_fetch_assoc($result);
			}
			
            $message['api_info'] = $res;
			
		//}

	}else{
		$message = array("verify_otp"=>"Enter your OTP");
	}

    echo json_encode($message);

}else if(isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != '' && !isset($_REQUEST['otp_value'])){
	
	//OTP 6 digit random generation
    if(isset($_SESSION['otp'])){
		unset($_SESSION['otp']);
	}
	
	$otp = '';
	$random_code = mt_rand(100000,999999);
    $_SESSION['otp'] = $random_code;
	//echo json_encode(array('secret_code'=>$random_code));
	$mobile = $_REQUEST['mobile'];	
	/*if(!empty(session_id())){
		$otp = session_id();
	}*/
	
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

			$data = ['status'=>400,'error'=>false,'message'=>'failure','code'=>$random_code];
			return $data;			
		}
		
	curl_close($curl); 
}

?>