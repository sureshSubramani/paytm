<?php 

session_start();
$data = array();

   if(!empty($_REQUEST)){
       // echo json_encode($_REQUEST);
	   // echo '<pre>';
	   // print_r($_REQUEST);
	   	   
	   $data = array(
		   'Response_Code'=>$_REQUEST['Response_Code'],
		   'Unique_Ref_Number'=>$_REQUEST['Unique_Ref_Number'],
		   'Service_Tax_Amount'=>$_REQUEST['Service_Tax_Amount'],
		   'Processing_Fee_Amount'=>$_REQUEST['Processing_Fee_Amount'],
		   'Total_Amount'=>$_REQUEST['Total_Amount'],
		   'Transaction_Amount'=>$_REQUEST['Transaction_Amount'],
		   'Transaction_Date'=>$_REQUEST['Transaction_Date'],
		   'Interchange_Value'=>$_REQUEST['Interchange_Value'],
		   'Payment_Mode'=>$_REQUEST['Payment_Mode'],
		   'SubMerchantId'=>$_REQUEST['SubMerchantId'],
		   'ReferenceNo'=>$_REQUEST['ReferenceNo'],
		   'TDR'=>$_REQUEST['TDR'],
		   'ID'=>$_REQUEST['ID'],
		   'RS'=>$_REQUEST['RS'],
		   'TPS'=>$_REQUEST['TPS'],
		   'mandatory_fields'=>$_REQUEST['mandatory_fields'],
		   'optional_fields'=>$_REQUEST['optional_fields'],
		   'RSV'=>$_REQUEST['RSV']
		   );
	   
	   $encrypted = base64_encode(serialize($data));

	   if(isset($_SESSION['CALLBACK_URL'])){
		$URL = isset($_SESSION['CALLBACK_URL'])?$_SESSION['CALLBACK_URL']:'';
		$uri_segments  = explode('/',$URL);
		$numOfSegments = count($uri_segments);
		$CUR_SEGMENT   = $uri_segments[1];

		$SEGMENTS = ['fee','hostelfee','outgone'];

		foreach($SEGMENTS as $k){
			if($k==$SEGMENTS[1])
			$CUR_SEGMENT = $k;
		}
	   
	   $encrypted = base64_encode(serialize($data));

	   if(isset($_SESSION['CALLBACK_URL'])){
			$URL = isset($_SESSION['CALLBACK_URL'])?$_SESSION['CALLBACK_URL']:'';
			$uri_segments  = explode('/',$URL);
			$numOfSegments = count($uri_segments);
			$CUR_SEGMENT   = $uri_segments[1];

			$SEGMENTS = ['fee','hostelfee','outgone'];

			foreach($SEGMENTS as $k){
				if($k==$SEGMENTS[1])
				$CUR_SEGMENT=$k;
			}
		}
	   
	   }else{
		echo 'Invalid Access OR Invalid Parametes..';
		exit;
	   }
	
	   //header('Location: http://localhost/mahendra_payments/'.$CUR_SEGMENT.'/TxnResponse.php?TxnResponse='.$encrypted);
	   header('Location: https://mahendra.org/online_payment/'.$CUR_SEGMENT.'/TxnResponse.php?TxnResponse='.$encrypted);
       exit;
	   
	   
   }else{
	  echo 'Invalid access or Parameter errors..';
   }

?>
