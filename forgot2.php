<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
include('testFunctions.php');
include('inc_random-password.php');

if (isset($_POST['submit'])) {
	try{
		$password = randomPassword(10,1,"lower_case,upper_case,numbers,special_symbols");
		$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$fullurl = base64_encode($_POST['fullurl']);
	
		$sql = "select COUNT(*) AS counter, Email, staffId, Name from user where Email='".$email."'";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($result);
		//$count = mysqli_num_rows($result);
		$count = $row['counter'];
    
		$staffName = $row['Name'];

		if($count > 0 && !empty($row)) {
			$sql2 = "update user set Password='".md5($password)."', Password1='".md5($password)."', Status=1 where Email='".$email."' ";
			mysqli_query($db, $sql2);
			echo '<meta http-equiv="refresh" content="0; URL=http://192.168.1.40/sims/inc_forgot_ucua.php?email='.$email.'&password='.$password.'&staffName='.$staffName.'&fullurl='.$fullurl.'" />';
			exit;
		}
	}catch(Exception $e){
		array_push($errors, $e);
	}
}
?>