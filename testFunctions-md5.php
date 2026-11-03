<?php
	date_default_timezone_set("Asia/Kuala_Lumpur");
	session_start();
	
	// connect to database
	$db = mysqli_connect('192.168.5.40', 'blink4', 'sc0rpene@747', 'ucua-staging');
	$errors   = array();

	if(empty($_SESSION['bruteForceCounter'])){
		$_SESSION['bruteForceCounter'] = 1;
	}
	
	if($_SESSION['bruteForceCounter'] > 3){
		try{
			global $db;
			$sql = "UPDATE user SET Status = 2, editby = 'ADMIN', editdate = '".date('Y-m-d h:i:s')."' WHERE staffId = '".trim($_POST['staffId'])."' ";
			mysqli_query($db, $sql);
			$_SESSION['bruteForceCounter'] = 1;
		}catch(Exception $e){
			array_push($errors, $e);
		}
	}

	// return user array from their id
	function getUserById($id){
		global $db;
		$query = "SELECT * FROM user WHERE staffId =" . $id;
		$result = mysqli_query($db, $query);
		$user = mysqli_fetch_assoc($result);
		return $user;
	}

	// escape string
	function e($val){
		global $db;
		return mysqli_real_escape_string($db, trim($val));
	}

	function display_error() {
		global $errors;

		if (count($errors) > 0){
			echo '<div class="alert alert-danger">';
				foreach ($errors as $error){
					echo $error .'.<br>';
				}
			echo '</div>';
		}
	}

	function isLoggedIn()
	{
		if (isset($_SESSION['user'])) {
			return true;
		}else{
			return false;
		}
	}
	


	function timeago($timestamp) {
		$time_ago        = strtotime($timestamp);
  		$current_time    = time();
  		$time_difference = $current_time - $time_ago;
  		$seconds         = $time_difference;
  
  		$minutes = round($seconds / 60); // value 60 is seconds  
  		$hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
  		$days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
  		$weeks   = round($seconds / 604800); // 7*24*60*60;  
  		$months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
  		$years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
                
  		if ($seconds <= 60){
    		return "Just Now";
  		} else if ($minutes <= 60){
    
			if ($minutes == 1){
      			return "one minute ago";
    		} else {
      			return "$minutes minutes ago";
    		}

  		} else if ($hours <= 24){

    		if ($hours == 1){
      			return "an hour ago";
    		} else {
      			return "$hours hrs ago";
    		}

  		} else if ($days <= 7){

    		if ($days == 1){
      			return "yesterday";
    		} else {
      			return "$days days ago";
    		}

  		} else if ($weeks <= 4.3){
    
			if ($weeks == 1){
      			return "a week ago";
    		} else {
      			return "$weeks weeks ago";
    		}

 	 	} else if ($months <= 12){

    		if ($months == 1){
      			return "a month ago";
    		} else {
      			return "$months months ago";
    		}

  		} else {
    
   	 		if ($years == 1){
      			return "one year ago";
    		} else {
      			return "$years years ago";
    		}
  		}
	}

	function showUserRole($id){
		global $db;
		$sql = "SELECT u.AccessLevel, r.rolename FROM user AS u LEFT JOIN userroles AS r ON (u.AccessLevel = r.id) WHERE u.staffid = '$id' ";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		//return '<small class="badge badge-secondary">'.$row['rolename'].'</small>';
	}

	function showPhoto($id){
		global $db;
		$img = '';
		$sql = "SELECT id, ucuaform_Id, evidenceImage FROM  ucuaphoto WHERE ucuaform_Id = '".$id."' AND type='Activity' ";
		$result = mysqli_query($db, $sql);
		While($row = mysqli_fetch_array($result)){
			$img .= '<a href="'.$row['evidenceImage'].'" class="image-popup-desc" data-title="Taking action" data-description="Taking action description.">';
			$img .= '<img class="img-thumbnail w-25" src="'.$row['evidenceImage'].'">';
			$img .= '</a>&nbsp;';
		}
		return $img;
	}
	
	function showActivity($reportid){
		global $db;

		$activity = '';

		$sql = "SELECT a.id, a.staffid, a.datetimestamp, a.activity, a.uploadfilename, u.Name FROM activity AS a LEFT JOIN user AS u ON (a.staffid=u.staffId) WHERE ucuaid = '$reportid'";
		$result = $db->query($sql);
		while ($row = mysqli_fetch_array($result)) {
			$activity .= '<div class="bg-white border mt-2">
				<div class="d-flex flex-row justify-content-between align-items-center p-2 border-bottom">
					<div class="d-flex flex-row align-items-center feed-text px-2">
						<img class="rounded-circle me-2" width="45" height="45" avatar="'.$row['Name'].'">
						<div class="d-flex flex-column flex-wrap ml-2">
							<span class="font-weight-bold text-capitalize">'.$row['Name'].' '.showUserRole($row['staffid']).'</span>
							<span class="text-black-50 time">'.$row['datetimestamp'].'</span>
						</div>
					</div>
					<div class="feed-icon px-2"><i class="fa fa-ellipsis-v text-black-50"></i></div>
				</div>
				<div class="p-2 px-3 bg-gray-200"><span>'.ucfirst($row['activity']).'</span></div>';

				$activity .= '<div class="feed-image p-2 px-3">';
				$activity .= showPhoto($row['id']);
				$activity .= '</div>';

			//if(!empty($row['uploadfilename'])){
				//$activity .= '<div class="feed-image p-2 px-3"><img class="img-fluid img-responsive" src="'.$row['uploadfilename'].'"></div>';
				//$activity .= '<div class="feed-image p-2 px-3"><img class="img-fluid img-responsive" src="/uploads/'.$row['uploadfilename'].'"></div>';
			//}

			$activity .= '</div>';
		}
		return $activity;
	}
	
	function checkAccessRights($i, $r){
		global $db;
		$sql = "SELECT accesslevel FROM userright WHERE moduleid = '".$i."' AND roleid = '".$r."' ";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result);
		if($row){
			return $row['accesslevel'];
		}else{
			return 0;
		}
	}

	function counterAccessRights(){
		global $db;
		$sql = "SELECT COUNT(*) AS counter FROM usermodule ";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result);
		return $row['counter'];
	}

	// call the login() function if register_btn is clicked
	if (isset($_POST['login_btn'])) {
		login();
	}

	// LOGIN USER
	function login(){
		try{
			global $db, $staffId, $errors;
			// grap form values
			$staffId = trim($_POST['staffId']);
			$password = trim($_POST['password']);

			// make sure form is filled properly
			if (empty($staffId)) {
				array_push($errors, "Staff id is required");
			}
			if (empty($password)) {
				array_push($errors, "Password is required");
			}
			$run = mysqli_query($db,"SELECT Status FROM user WHERE staffId ='$staffId' AND Status=3 AND isdelete=0 ");
			if (mysqli_num_rows($run) == 1) {
				array_push($errors, "Your account has not been approved");
			}elseif(mysqli_num_rows(mysqli_query($db,"SELECT Status FROM user WHERE  staffId ='$staffId' AND Status=2 AND isdelete=0 ")) == 1){
				array_push($errors, "Your account has been DISABLED");
			}
			// attempt login if no errors on form
			if (count($errors) == 0) {
				//echo"<script>alert('$staffId')</script>";
				$query = "SELECT *, userroles.rolename FROM user LEFT JOIN userroles ON userroles.id = user.AccessLevel WHERE user.staffId ='$staffId' AND user.Password='$password' AND user.Status=1 AND user.isdelete=0 LIMIT 1";
				$results = mysqli_query($db, $query);
				$logged_in_user = mysqli_fetch_assoc($results);
				//array_push($errors, $query);
			
				if (mysqli_num_rows($results) == 1) { // user found
					$_SESSION['user'] = $logged_in_user;
					$counter = counterAccessRights();

					for($i=0;$i<=$counter;$i++){
						$_SESSION['accesslevel'.$i] = checkAccessRights($i, $_SESSION['user']['AccessLevel']);
					}
				
					header('location: default.php?page=dashboard');
					exit;
				}else {
					try{
						array_push($errors, $_SESSION['bruteForceCounter'].') Wrong username/password combination');
						$_SESSION['bruteForceCounter'] = $_SESSION['bruteForceCounter'] + 1;
					}catch(Exception $e){
						array_push($errors, $e);
					}
				}
			}
		}catch(Exception $e){
			array_push($errors, $e);
		}
	}
?>