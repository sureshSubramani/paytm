<?php
require('../config.php');

if(isset($_REQUEST)){

	$resultset[0]['error'] = 1;
    //$roll_no = '116UMC009';
	$roll_no = isset($_REQUEST['roll_no'])?$_REQUEST['roll_no']:"";

	if($roll_no){

		//college_name='".$college_name."' AND 
		
		$sql = "SELECT * FROM student_details WHERE (roll_no='".$roll_no."' OR reg_no='".$roll_no."')  AND status=1 LIMIT 0,1";
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
		$resultset[] = 'Roll or Register number is missing!';
	}
}
else{
	$resultset[] = 'Invalid access!';
}
echo json_encode($resultset);
?>