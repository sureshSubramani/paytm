<?php 

	$random_code = mt_rand(100000,999999);
	//Starting the session	
	//$id = session_create_id();	
	session_id($random_code);
	//print("\n"."Id: ".$id);
	session_start();      
	echo json_encode(array('secret_code'=>session_id()));

?>