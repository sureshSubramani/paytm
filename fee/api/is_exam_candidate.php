<?php
require('../config.php');

if(isset($_REQUEST)){
	$resultset[0]['error'] = 1;
	$roll_no = isset($_REQUEST['roll_no'])?$_REQUEST['roll_no']:"";

	if($roll_no){

		//$sql = "SELECT roll_no,reg_no FROM (SELECT roll_no,reg_no FROM student_details UNION ALL SELECT roll_no,reg_no FROM exam_students ) as temp WHERE (roll_no='".$roll_no."' OR reg_no='".$roll_no."') GROUP BY roll_no HAVING count(*) > 1";
		$sql1="select * from exam_students where roll_no='".$roll_no."' or reg_no='".$roll_no."' limit 1";
		$result1 = mysqli_query($con,$sql1);
		
		$sql = "SELECT * FROM student_details where roll_no='".$roll_no."' or reg_no='".$roll_no."' limit 1";
		//echo $sql1.$sql; exit;
		$result = mysqli_query($con,$sql);

		if(($result->num_rows > 0) && ($result1->num_rows > 0)){	
			$sql = "SELECT * FROM student_details WHERE (roll_no='".$roll_no."' OR reg_no='".$roll_no."')  AND status=1";
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
			$resultset[0]['payment'] = 0;
			$resultset[] = 'Please Contact your Head of Department';
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