<?php
session_start();
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
date_default_timezone_set("Asia/Kuala_Lumpur");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
	$server = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'ucua';
	$db = mysqli_connect($server, $username, $password, $database);
} catch (mysqli_sql_exception $e) {
	header('HTTP/1.1 503 Service Unavailable');
	die(mysqli_connect_error());
}

$errors   = array();

if (empty($_SESSION['bruteForceCounter'])) {
	$_SESSION['bruteForceCounter'] = 1;
}

if ($_SESSION['bruteForceCounter'] > 3) {
	try {
		global $db;
		$d = date('Y-m-d h:i:s');
		$s = trim($_POST['staffId']);
		$sql = "UPDATE user SET Status = 2, editby = 'ADMIN', editdate = ? WHERE staffId = ? ";
		$stmt = $db->prepare($sql);
		$stmt->bind_param("ss", $d, $s);
		$stmt->execute();
		$_SESSION['bruteForceCounter'] = 1;
	} catch (Exception $e) {
		array_push($errors, $e);
	}
}

// return user array from their id
function getUserById($id)
{
	global $db;
	$sql = "SELECT * FROM user WHERE staffId=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	return $user;
}

function getBrandByCompanyID($cid)
{
	global $db;
	$sql = "SELECT brandName FROM branding WHERE companyCode=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $cid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($row) {
		return $row['brandName'];
	}
}

// escape string
function e($val)
{
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error()
{
	global $errors;

	if (count($errors) > 0) {
		echo '<div class="alert alert-danger">';
		foreach ($errors as $error) {
			echo $error . '.<br>';
		}
		echo '</div>';
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	} else {
		return false;
	}
}

function hasAccess($page_name)
{
	global $db;
	$roleId = $_SESSION['user']['AccessLevel'];
	$sql = "SELECT COUNT(*) AS has_access
            FROM pagepermissions AS a
			LEFT JOIN page AS p ON p.id=a.pageId
            WHERE p.pagename = ? AND a.roleId = ?";

	$stmt = $db->prepare($sql);
	$stmt->bind_param("si", $page_name, $roleId);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	return $row['has_access'] > 0;
}

function reportNotified($id)
{
	global $db;
	$status = '';
	$sql = "SELECT isapproved, isHosApproved, isHodApproved FROM ucuaform WHERE id = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	if ($row['isapproved'] == 1 && $row['isHosApproved'] == 0 && $row['isHodApproved'] == 0) {
		$status = '<span class="badge rounded-pill bg-info">PENDING HOS</span>';
	}

	if ($row['isapproved'] == 1 && $row['isHosApproved'] == 1 && $row['isHodApproved'] == 0) {
		$status = '<span class="badge rounded-pill bg-info">PENDING HOD</span>';
	}

	return $status;
}

function reportApproved($id)
{
	global $db;
	$status = '';
	$sql = "SELECT isapproved, isHosApproved, isHodApproved, isrejected, isHOSrejected, isHODrejected FROM ucuaform WHERE id = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	if ($row['isHOSrejected'] == 0 && $row['isapproved'] == 1 && $row['isHosApproved'] == 0 && $row['isHodApproved'] == 0) {
		$status = '<span class="badge rounded-pill bg-success">PENDING HOS</span>';
	}

	if ($row['isHODrejected'] == 0 && $row['isapproved'] == 1 && $row['isHosApproved'] == 1 && $row['isHodApproved'] == 0) {
		$status = '<span class="badge rounded-pill bg-info">PENDING HOD</span>';
	}

	if ($row['isrejected'] == 1 || $row['isHOSrejected'] == 1 || $row['isHODrejected'] == 1) {
		$status = '<span class="badge text-bg-danger">REJECTED</span>';
	}

	return $status;
}

function reportStatus($accessLevel, $id)
{
	global $db;
	$status = '';
	$sql = "SELECT id, isverified, isapproved, isrejected, isconfirm, isNotifyHos, isHosApproved, isHodApproved, isclosed, isreopen,  isHOSrejected, isHODrejected, isupload, isUserClosed,
        CASE 
            WHEN isverified = 0 THEN 0
            WHEN isverified = 1 THEN 1
            WHEN isverified = 2 THEN 2
			WHEN isrejected = 1 THEN 3
        END AS status
    FROM ucuaform WHERE id = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($accessLevel == 6) { //HOS
		if ($row['isHosApproved'] == 0 && $row['isHOSrejected'] == 0 && $row['isclosed'] == 0) {
			$status = '<span class="badge text-bg-warning">PENDING</span>';
		}
		if ($row['isHosApproved'] == 1 && $row['isHOSrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge text-bg-success">APPROVED</span>';
		}
		if ($row['status'] == 2 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge rounded-pill bg-info">Notified</span>';
		}
		if ($row['isHOSrejected'] == 1) {
			$status = '<span class="badge text-bg-danger">REJECTED</span>';
		}
		if ($row['isUserClosed'] == 1) {
			$status = '<span class="badge rounded-pill bg-secondary">Request Close</span>';
		}
		if ($row['isclosed'] == 1) {
			$status = '<span class="badge rounded-pill bg-secondary">Closed</span>';
		}
	} elseif ($accessLevel == 7) { //HOD
		if ($row['isHodApproved'] == 0 && $row['isHODrejected'] == 0 && $row['isclosed'] == 0) {
			$status = '<span class="badge text-bg-warning">PENDING</span>';
		}
		if ($row['isHodApproved'] == 1 && $row['isHODrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge text-bg-success">APPROVED</span>';
		}
		if ($row['status'] == 2 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge rounded-pill bg-info">Notified</span>';
		}
		if ($row['isHODrejected'] == 1) {
			$status = '<span class="badge text-bg-danger">REJECTED</span>';
		}

		if ($row['isclosed'] == 1) {
			$status = '<span class="badge rounded-pill bg-secondary">Closed</span>';
		}
	} elseif ($accessLevel == 4) { //PSSD HSE OFFICER
		if ($row['isapproved'] == 0 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge rounded-pill bg-warning">Pending Officer</span>';
		} else if ($row['isapproved'] == 1 && $row['isrejected'] != 1 && $row['isclosed'] != 1 && $row['isHosApproved'] == 0 && $row['isHOSrejected'] == 0) {
			//$status = '<span class="badge rounded-pill bg-success">Approved</span>';
			$status = reportApproved($row['id']);
		} else if ($row['status'] == 2 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
			//$status = '<span class="badge rounded-pill bg-info">Notified</span>';
			$status = reportNotified($row['id']);
		} else if ($row['isrejected'] == 1 && $row['isclosed'] != 1 || $row['isHOSrejected'] == 1 || $row['isHODrejected'] == 1) {
			$status = '<span class="badge rounded-pill bg-danger">Rejected</span>';
		} else if ($row['isUserClosed'] == 1) {
			$status = '<span class="badge rounded-pill bg-secondary">Request Close</span>';
		} else if ($row['isclosed'] == 1) {
			$status = '<span class="badge rounded-pill bg-secondary">Closed</span>';
		} else {
			$status = '<span class="badge text-bg-warning">PENDING</span>';
		}
	} else {
		if ($row['status'] == 0 && $row['isrejected'] != 1 && $row['isHOSrejected'] == 0 && $row['isHODrejected'] == 0) {
			$status = '<span class="badge rounded-pill bg-warning">Pending</span>';
		}
		if ($row['status'] == 1 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge rounded-pill bg-success">Approved</span>';
		}
		if ($row['status'] == 2 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge rounded-pill bg-info">Notified</span>';
		}
		if ($row['isrejected'] == 1 && $row['isclosed'] != 1 || $row['isHOSrejected'] == 1 || $row['isHODrejected'] == 1) {
			$status = '<span class="badge rounded-pill bg-danger">Rejected</span>';
		}
		if ($row['isupload'] == 1 && $row['isrejected'] != 1 && $row['isclosed'] != 1) {
			$status = '<span class="badge rounded-pill bg-success">Uploaded</span>';
		}
		if ($row['isUserClosed'] == 1) {
			$status = '<span class="badge rounded-pill bg-secondary">Request Close</span>';
		}
		if ($row['isclosed'] == 1) {
			$status = '<span class="badge rounded-pill bg-secondary">Closed</span>';
		}
	}
	return $status;
}

function timeago($timestamp)
{
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

	if ($seconds <= 60) {
		return "Just Now";
	} else if ($minutes <= 60) {

		if ($minutes == 1) {
			return "one minute ago";
		} else {
			return "$minutes minutes ago";
		}
	} else if ($hours <= 24) {

		if ($hours == 1) {
			return "an hour ago";
		} else {
			return "$hours hrs ago";
		}
	} else if ($days <= 7) {

		if ($days == 1) {
			return "yesterday";
		} else {
			return "$days days ago";
		}
	} else if ($weeks <= 4.3) {

		if ($weeks == 1) {
			return "a week ago";
		} else {
			return "$weeks weeks ago";
		}
	} else if ($months <= 12) {

		if ($months == 1) {
			return "a month ago";
		} else {
			return "$months months ago";
		}
	} else {

		if ($years == 1) {
			return "one year ago";
		} else {
			return "$years years ago";
		}
	}
}

function showNotification($sid)
{
	global $db;
	$n = '';
	$y = date('Y');
	$sql = "SELECT id, ucuaid, staffid, action, activity, status, datetimestamp FROM history WHERE staffid=? AND action='NOTIFICATION' AND YEAR(datetimestamp)=? ORDER by id DESC";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("is", $sid, $y);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		$n .= '<a href="#!" class="text-reset notification-item">
			<div class="d-flex">
				<div class="flex-shrink-0 avatar-sm me-3">
					<span class="avatar-title bg-primary rounded-circle font-size-16">
					<i class="fa-regular fa-paper-plane"></i>
					</span>
				</div>
				<div class="flex-grow-1">
					<h6 class="mb-1">' . ucwords(strtolower($row['status'])) . ' ' . ucwords(strtolower($row['action'])) . '</h6>
					<div class="font-size-13 text-muted">
						<p class="mb-1">' . $row['activity'] . '</p>
						<p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>' . date('d M, Y', strtotime($row['datetimestamp'])) . '</span></p>
					</div>
				</div>
			</div>
		</a>';
	}
	return $n;
}

function showUserRole($id)
{
	global $db;
	$sql = "SELECT u.AccessLevel, r.rolename FROM user AS u LEFT JOIN userroles AS r ON (u.AccessLevel = r.id) WHERE u.staffid = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	//return '<small class="badge badge-secondary">'.$row['rolename'].'</small>';
}

function showPhoto($id)
{
	global $db;
	$img = '';
	$sql = "SELECT id, ucuaform_Id, evidenceImage FROM  ucuaphoto WHERE ucuaform_Id = ? AND type='Activity' ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		$img .= '<a href="' . $row['evidenceImage'] . '" class="float-start ms-1 image-popup-desc" data-title="Taking action" data-description="Taking action description.">';
		$img .= '<img class="img-fluid mx-auto d-block" src="' . $row['evidenceImage'] . '">';
		$img .= '</a>&nbsp;';
	}
	return $img;
}

function showActivity($reportid)
{
	global $db;
	$activity = '';
	$sql = "SELECT a.id, a.staffid, a.datetimestamp, a.activity, a.uploadfilename, u.Name FROM activity AS a LEFT JOIN user AS u ON (a.staffid=u.staffId) 
	WHERE a.ucuaid = ?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $reportid);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		$activity .= '<h5 class="font-size-15"><i class="bi bi-chat-left-dots text-muted align-middle me-1"></i> Comments </h5>';
		while ($row = mysqli_fetch_array($result)) {
			$activity .= '<div class="d-flex py-3 border-top">
							<div class="flex-shrink-0 me-3 ms-3">
								<img class="rounded-circle me-2" width="45" height="45" avatar="' . $row['Name'] . '">
							</div>
							<div class="flex-grow-1">
								<h5 class="font-size-14 mb-0">' . ucwords(strtolower($row['Name'] ?? '')) . ' ' . showUserRole($row['staffid']) . '</h5>
								<span class="font-size-13 text-black-50 time">' . date('d M, Y', strtotime($row['datetimestamp'])) . '</span>
								<p class="text-muted">' . ucfirst($row['activity']) . 'l</p>
							</div>
						</div>';
			$activity .= '<div class="feed-image p-2 px-3 mt-0">';
			$activity .= showPhoto($row['id']);
			$activity .= '</div>';
		}
	} else {
		$activity .= '';
	}
	return $activity;
}

function showActivity2($reportid)
{
	global $db;
	$activity = '';
	$sql = "SELECT a.id, a.staffid, a.datetimestamp, a.activity, a.uploadfilename, u.Name FROM activity AS a LEFT JOIN user AS u ON (a.staffid=u.staffId) WHERE ucuaid=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $reportid);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		$activity .= '<div class="card bg-light-subtle my-3">
				<div class="d-flex flex-row justify-content-between align-items-center p-2 border-bottom">
					<div class="d-flex flex-row align-items-center feed-text px-2">
						<img class="rounded-circle me-2" width="45" height="45" avatar="' . $row['Name'] . '">
						<div class="d-flex flex-column flex-wrap ml-2">
							<span class="font-weight-bold text-capitalize">' . $row['Name'] . ' ' . showUserRole($row['staffid']) . '</span>
							<span class="font-size-13 text-black-50 time">' . date('d M, Y', strtotime($row['datetimestamp'])) . '</span>
						</div>
					</div>
					<div class="feed-icon px-2"><i class="fa fa-ellipsis-v text-black-50"></i></div>
				</div>
				<div class="p-2 px-3 bg-gray-200"><span>' . ucfirst($row['activity']) . '</span></div>';

		$activity .= '<div class="feed-image p-2 px-3">';
		$activity .= showPhoto($row['id']);
		$activity .= '</div>';

		$activity .= '</div>';
	}
	return $activity;
}

function checkAccessRights($i, $r)
{
	global $db;
	$sql = "SELECT accesslevel FROM userright WHERE moduleid = ? AND roleid = ? ";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("ii", $i, $r);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($row) {
		return $row['accesslevel'];
	} else {
		return 0;
	}
}

function checkCompany($cid)
{
	global $db;
	$sql = "SELECT c.companyCode, c.companyName, bb.branchCode, bb.branchName, c.logo, b.brandName, b.brandColor, b.brandLogo, b.brandFavicon FROM admcompany AS c
	LEFT JOIN branding AS b ON b.companyCode = c.companyCode
	LEFT JOIN admbranch AS bb ON bb.companyCode = c.companyCode
	WHERE c.companyCode = ? LIMIT 1";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $cid);
	$stmt->execute();
	$result = $stmt->get_result();
	$company = $result->fetch_assoc();
	return $company;
}

function counterAccessRights()
{
	global $db;
	$sql = "SELECT COUNT(*) AS counter FROM usermodule ";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result);
	return $row['counter'];
}

function funLoginActivity($staffid, $AccessLevel, $action, $ip, $user_agent)
{
	global $db;
	$activity = $ip . ' ' . $user_agent;
	$d = date('Y-m-d h:i:s');
	$s = 'SUCCESS';
	try {
		$sql = "INSERT INTO history (staffid, accessLevel, action, activity, status, ipaddress, useragent, datetimestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$stmt->bind_param("ssssssss", $staffid, $AccessLevel, $action, $activity, $s, $ip, $user_agent, $d);
		$stmt->execute();
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

// LOGIN USER
function login()
{
	try {
		global $db, $staffId, $errors;
		// grap form values
		$staffId = trim($_POST['staffId']);
		$password = md5($_POST['password']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		// make sure form is filled properly
		if (empty($staffId)) {
			array_push($errors, "Staff id is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		$stmt_unapproved = $db->prepare("SELECT Status FROM user WHERE staffId = ? AND Status = 3 AND isdelete = 0 LIMIT 1");
		$stmt_unapproved->bind_param("s", $staffId);
		$stmt_unapproved->execute();
		$result_unapproved = $stmt_unapproved->get_result();
		if ($result_unapproved->num_rows == 1) {
			array_push($errors, "Your account has not been approved");
		}

		$stmt_disabled = $db->prepare("SELECT Status FROM user WHERE staffId = ? AND Status = 2 AND isdelete = 0 LIMIT 1");
		$stmt_disabled->bind_param("s", $staffId);
		$stmt_disabled->execute();
		$result_disabled = $stmt_disabled->get_result();
		if ($result_disabled->num_rows == 1) {
			array_push($errors, "Your account has been DISABLED");
		}

		// attempt login if no errors on form
		if (count($errors) == 0) {
			$query = "SELECT u.companyCode, u.branchCode, u.staffId, u.Name, u.PhoneNo, u.Department, u.Designation, u.AccessLevel, u.Email, u.CompanyID, r.rolename 
			FROM user AS u LEFT JOIN userroles AS r ON r.id = u.AccessLevel 
			WHERE u.staffId=? AND u.Password=? AND u.Status=1 AND u.isdelete=0 LIMIT 1";
			$stmt = $db->prepare($query);
			$stmt->bind_param("ss", $staffId, $password);
			$stmt->execute();
			$results = $stmt->get_result();
			$logged_in_user = $results->fetch_assoc();
			//array_push($errors, $query);

			if (mysqli_num_rows($results) == 1) { // user found
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['company'] = checkCompany($_SESSION['user']['companyCode']);
				$counter = counterAccessRights();

				for ($i = 0; $i <= $counter; $i++) {
					$_SESSION['accesslevel' . $i] = checkAccessRights($i, $_SESSION['user']['AccessLevel']);
				}
				//Log user login
				try {
					funLoginActivity($_SESSION['user']['staffId'], $_SESSION['user']['AccessLevel'], 'LOGIN', $ip, $user_agent);
				} catch (Exception $e) {
					array_push($errors, $e);
				}
				header('location: default.php?page=dashboard');
				exit;
			} else {
				try {
					array_push($errors, $_SESSION['bruteForceCounter'] . ') Wrong username/password combination');
					$_SESSION['bruteForceCounter'] = $_SESSION['bruteForceCounter'] + 1;
				} catch (Exception $e) {
					array_push($errors, $e);
				}
			}
		}
	} catch (Exception $e) {
		array_push($errors, $e);
	}
}
