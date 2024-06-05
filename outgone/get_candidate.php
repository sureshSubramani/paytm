<?php
require('config.php');

if(isset($_REQUEST)){
	$resultset[0]['error'] = 1;
	$reg_no = isset($_REQUEST['reg_no'])?$_REQUEST['reg_no']:"";
	$roll_no = isset($_REQUEST['roll_no'])?$_REQUEST['roll_no']:"";

	if($reg_no){

		$sql = "SELECT * FROM out_student_details WHERE (reg_no='".$reg_no."')  AND status=1";
		$result = mysqli_query($con,$sql);

		if($result->num_rows > 0){	

			$resultset[0]['error'] = 0;
								
			while($row = mysqli_fetch_assoc($result)) {
				$resultset[] = $row;
			}
			
		}else{
			$resultset[] = 'No record found!';
		}

		mysqli_close($con);
	}else if($roll_no){
		$sql = "SELECT * FROM out_student_details WHERE (roll_no='".$roll_no."')  AND status=1";
		$result = mysqli_query($con,$sql);

		if($result->num_rows > 0){	

			$resultset[0]['error'] = 0;
								
			while($row = mysqli_fetch_assoc($result)) {
				$resultset[] = $row;
			}
			
		}else{
			$resultset[] = 'No record found!';
		}
	}else{
		$resultset[] = 'Roll or Register number is missing!';
	}
}
else{
	$resultset[] = 'Invalid access!';
}

echo json_encode($resultset);
?>