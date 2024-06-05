<?php
require('../config.php');

if(isset($_REQUEST)){

	$resultset['error'] = 1;
	$roll_no 	  = isset($_REQUEST['roll_no'])?$_REQUEST['roll_no']:"";
	$college_name = isset($_REQUEST['college_name'])?$_REQUEST['college_name']:"";

	if($college_name && $roll_no){
		
		$sql = "SELECT college_name,hostel_code,roll_no,dep_name,name,f_name,mobile FROM hostel_candidate WHERE college_name='".$college_name."' AND (roll_no='".$roll_no."' OR hostel_code='".$roll_no."')  AND status=1 LIMIT 0,1";
		//$result = $conn->query($sql);
		$result = mysqli_query($con,$sql);

		if( $result->num_rows > 0 ){

			$resultset['error'] = 0;
			while($row = mysqli_fetch_assoc($result)) {
				$resultset['data'] = $row;
			}

		} 
		else{
			$resultset['message'] = 'No record found!';
		}

		mysqli_close($con);
	}else{
		$resultset['message'] = 'College Name or Roll number missing!';
	}
}else{
	$resultset['message'] = 'Invalid access!';
}

echo json_encode($resultset);

?>