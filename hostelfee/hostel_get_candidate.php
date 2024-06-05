<?php
require('config.php');

if(isset($_REQUEST)){

	$resultset[0]['error'] = 1;
	$roll_no = isset($_REQUEST['roll_no'])?$_REQUEST['roll_no']:"";
	$college_name = isset($_REQUEST['college_name'])?$_REQUEST['college_name']:"";

	if($roll_no){
		
		$sql = "SELECT * FROM hostel_candidate WHERE college_name='".$college_name."' AND (roll_no='".$roll_no."' OR hostel_code='".$roll_no."')  AND status=1";
		//$result = $conn->query($sql);
		$result = mysqli_query($con,$sql);

		if ( $result->num_rows > 0 ) {

			//$temp = $result->fetch_assoc();
			$resultset[0]['error'] = 0;
			while($row = mysqli_fetch_assoc($result)) {
				$resultset[] = $row;
				#print_r($row);
			}

		} 
		else{
			$resultset[] = 'No record found!';
		}

		mysqli_close($con);
	}
	else{
		$resultset[] = 'Roll number missing!';
	}
}
else{
	$resultset[] = 'Invalid access!';
}
echo json_encode($resultset);
?>