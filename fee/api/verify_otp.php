<?php 

	if(isset($_REQUEST['otp_value']) && $_REQUEST['otp_value'] != ""){
		session_start();
		if(session_id() == $_REQUEST['otp_value']){
			echo json_encode(array('secret_code'=>session_id()));
		}else{
			echo json_encode(array('secret_code_failure'=>session_id()));
		}
		
	}else{
		$random_code = mt_rand(100000,999999);
		//Starting the session	
		$id = session_create_id();	
		session_id($random_code);
		//print("\n"."Id: ".$id);
		session_start();      
		echo json_encode(array('secret_code'=>session_id()));
	}

?>