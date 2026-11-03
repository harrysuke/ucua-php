<?php
include('testFunctions.php');
include('inc_email_password.php');
$_SESSION['status'] = '';


function sendEmail($notifyHos, $notifyPssdHseOfficer, $history, $email_username, $email_password, $url, $notifyRelatedDept)
{
	echo 'Please use EMail Server.';
	$fullurl = 'http://192.168.1.40/ucua/notification2.php';
	$fullurl .= '?notifyHos=' . $notifyHos;
	$fullurl .= '&notifyPssdHseOfficer=' . $notifyPssdHseOfficer;
	$fullurl .= '&history=' . $history;
	$fullurl .= '&email_username=' . base64_encode($email_username);
	$fullurl .= '&email_password=' . base64_encode($email_password);
	$fullurl .= '&url=' . base64_encode($url);
	$fullurl .= '&notifyRelatedDept=email, khairi@johorport.com.my';
	echo '<meta http-equiv="refresh" content="0; URL=' . $fullurl . '" />';
}

function showName($n)
{
	global $db;
	$sql = "SELECT Name, Email FROM user WHERE staffId = '" . $n . "'";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($row) {
		return $row['Name'];
	} else {
		return $n;
	}
}

function findHosHod($no)
{
	global $db;
	$sql = "SELECT Name, Email FROM viewhoshod WHERE AccessLevel = '" . $no . "'";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_assoc($result);
	return $row['Email'];
}

function activity($GM, $reportId, $submitdate, $remark, $evidenceImage2)
{
	global $db;
	$sql = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ($reportId, '$GM', '$remark', '$submitdate')";
	if (mysqli_query($db, $sql)) {
		$last_id = mysqli_insert_id($db);
		between($evidenceImage2, $last_id);
		$_SESSION['status'] .= " Activity added successfully";
		$_SESSION['status_code'] = "success";
	}
	//echo $sql;
}

function between($evidenceImage2, $last_id)
{
	//MULTI IMAGES UPLOAD 21/2/2023
	if (!empty($evidenceImage2)) {
		$target_dir = "uploads/";
		$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
		$x = 0;
		$fileNames = array_filter($evidenceImage2);
		if (!empty($fileNames)) {
			foreach ($evidenceImage2 as $key => $val) {
				$fileName4 = basename($evidenceImage2[$key]);
				//$file_parts3 = explode(".",$filename4);
				//$file_end_part3 = end($file_parts3);
				//$filename5 = $x.date('YmdHisv') . "." . $file_end_part3;
				$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
				$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
				if (in_array($fileType, $allowTypes)) {
					if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
						insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Activity');
					}
				}
				$x++;
			}
		}
	}
}

function insertUcuaphoto($ucuaform_Id, $evidenceImage, $createby, $createdate, $type)
{
	global $db;
	$sql = "INSERT INTO ucuaphoto (ucuaform_Id, evidenceImage, createby, createdate, type) VALUES (" . $ucuaform_Id . ", '" . $evidenceImage . "', '" . $createby . "', '" . $createdate . "', '" . $type . "') ";
	mysqli_query($db, $sql);
	echo $sql;
}


function history($ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp)
{
	global $db;
	$sql = "INSERT INTO  history (ucuaid, staffid, accessLevel, action, activity, status, datetimestamp) VALUES (" . $ucuaid . ", '" . $staffid . "', '" . $accessLevel . "', '" . $action . "', '" . $activity . "', '" . $status . "', '" . $datetimestamp . "')";

	//echo $sql;

	if (mysqli_query($db, $sql)) {
		$_SESSION['status'] .= " History created successfully.";
		$_SESSION['status_code'] = "success";
	} else {
		$_SESSION['status'] .= "2) " . $sql . mysqli_error($db);
		$_SESSION['status_code'] = "error";
	}
}

//Update Function
if (isset($_POST['submitbtn'])) {
	$GM = $_SESSION['user']['staffId'];
	$reportId = $_POST['reportId'];
	$notifyHos = $_POST['notifyHos'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$ObsEmail = $_POST['ObsEmail'];
	$pssdremark = str_replace("'", "", $_POST['pssdRemark']);
	$url = $_POST['url'];
	$user = $_POST['user'];
	$fineamount = 0;
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	if (!empty($_POST['actionTaken'])) {
		$chk2 = $_POST['actionTaken'];
		$chk3 = $_POST['actionTaken'];
	} else {
		$chk2 = 0;
		$chk3 = 0;
	}

	if (isset($_POST['actionTakenByHsePssd'])) {
		$actionTakenByHsePssd = implode(", ", $_POST['actionTakenByHsePssd']); //array
	} else {
		$actionTakenByHsePssd = 'No actions taken';
	}
	$fine = $_POST['fine'];
	$from = $_POST['from'];
	$until = $_POST['until'];
	$month = $_POST['month'];
	$month2 = $_POST['month2'];
	$others = $_POST['others'];

	$history = showName($GM) . ' updated the follow-up details with &quot;' . $fineamount . '&quot; and &quot;' . $pssdremark . '&quot; and verified report ID ' . $reportId . ' on ' . $submitdate;
	$history2 = showName($GM) . ' sent email to ' . $notifyHos . ' CC to ' . $notifyPssdHseOfficer . ' for approved report ID ' . $reportId . ' on ' . $submitdate;

	// write the update query
	$sql = "UPDATE ucuaform SET 
		GM = '$GM', 
		isverified = 1, 
		verifiedby = '$GM', 
		verifieddate = '$submitdate', 
		isapproved = 1, 
		approvedby = '$GM', 
		approveddate = '$submitdate', 
		approvedby_date = '$submitdate', 
		followup = '$actionTakenByHsePssd', 
		ActionTakenBy='$GM', 
		actiontaken_date='$submitdate', 
		pssdremark='$pssdremark', 
		fineamount='$fineamount', 
		isNotifyHos=1,
		HOSreviewName = '0, " . $notifyHos . "',		
		status = 'Approved by PSSD HSE Officer' 
		WHERE `Id` = '$reportId'";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files2']['name']);

	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $pssdremark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Activity');
					//header('Location: default.php?page=uc_reportlist');
					//exit(0);
				} //if
			} //if
			$x++;
		} //for
	} else {
		//ACTIVITY
		if (!empty($pssdremark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $pssdremark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
			//echo $sql0;
		}
	}

	history($reportId, $GM, $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'APPROVED', $submitdate);
	history($reportId, $GM, $_SESSION['user']['AccessLevel'], 'NOTIFICATION', $history2, 'EMAIL', $submitdate);

	$_SESSION['status'] .= " Report Has Been Approved.";
	$_SESSION['status_code'] = "success";
	sendEmail($notifyHos, $notifyPssdHseOfficer, $history, $email_username, $email_password, $url, $ObsEmail);
}

if (isset($_POST['reject2btn'])) {
	$GM = $_SESSION['user']['staffId'];
	$reportId = $_POST['reportId'];
	$notifyHos = $_POST['observerEmail'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$ObsEmail = $_POST['ObsEmail'];
	$pssdremark = str_replace("'", "", $_POST['pssdRemark']);
	$url = $_POST['url'];
	$user = $_POST['user'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$history = showName($GM) . ' rejected report ID ' . $reportId . ' on ' . $submitdate;
	$history2 = showName($GM) . ' sent email to ' . $notifyHos . ' CC to ' . $notifyPssdHseOfficer . ' for rejected report ID ' . $reportId . ' on ' . $submitdate;
	// write the update query
	$sql = "UPDATE ucuaform SET GM = '$GM', isverified = 1, verifiedby = '$GM', verifieddate = '$submitdate', isrejected = 1, rejectedby = '$GM', rejecteddate = '$submitdate', pssdremark='$pssdremark', status = 'Rejected by PSSD HSE Officer' WHERE `Id` = '$reportId'";
	mysqli_query($db, $sql);

	//echo $sql;

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files2']['name']);

	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $pssdremark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Activity');
					//header('Location: default.php?page=uc_reportlist');
					//exit(0);
				} //if
			} //if
			$x++;
		} //for
	} else {
		//ACTIVITY
		if (!empty($pssdremark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $pssdremark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
		//echo $sql0;
	}

	$_SESSION['status'] .= " Report Has Been Rejected.";
	$_SESSION['status_code'] = "success";
	//activity($user, $reportId, $submitdate, $pssdremark, 0);
	history($reportId, $GM, $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'REJECTED', $submitdate);
	history($reportId, $GM, $_SESSION['user']['AccessLevel'], 'NOTIFICATION', $history2, 'EMAIL', $submitdate);
	sendEmail($notifyHos, $notifyPssdHseOfficer, $history, $email_username, $email_password, $url, $ObsEmail);
	//echo '<meta http-equiv="refresh" content="0; URL='.$url.'" />';
	//exit(0);
}

if (isset($_POST['btnHosReject'])) {
	$GM = $_SESSION['user']['staffId'];
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['pssdRemark']);
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];
	$status = 'Rejected by ' . $user;

	//$notifyHos = explode(', ',$_POST['HSEreviewName2']);
	$notifyHos1 = '0, ' . $_POST['createby2'];
	$notifyHos = explode(', ', $notifyHos1);

	$notifyRelatedDept = $_POST['notifyHod'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$array = '1, ' . $notifyHos[1] . ', ' . findHosHod(6);
	$email_username = 0;
	$email_password = 0;
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	// write the update query
	$sql = "UPDATE ucuaform SET GM = '$GM', isverified = 0, verifiedby = '$GM', verifieddate = '$submitdate', isHOSrejected = 1, HOSrejectedby = '$GM', HOSrejecteddate = '$submitdate', approvedby_date = '$submitdate', remark = '$status' WHERE Id = '$reportId'";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files2']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Reject');

					$history = $_SESSION['user']['Name'] . ' adding feedback to report ID ' . $reportId . ' on ' . $submitdate;
					history($reportId, $user, $_SESSION['user']['AccessLevel'], 'FEEDBACK', $history, 'ADDED', $submitdate);
					//upload($filename, $tmp, $user, $reportId, $submitdate, $remark, $filename);
				} //if
			} //if
			$x++;
		} //for
	} else {
		//if there's no photo accept the comment only
		//ACTIVITY
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	$history = showName($GM) . ' rejected report ID ' . $reportId . ' on ' . $submitdate;
	history($reportId, $GM, $user, 'UPDATE', $history, 'REJECTED', $submitdate);

	$_SESSION['status'] .= " Report Has Been Rejected By PSSD HSE Officer";
	$_SESSION['status_code'] = "success";

	sendEmail($notifyHos[1], $notifyPssdHseOfficer, $history . '<br/>Remark: ' . $remark, $email_username, $email_password, $url, $array);
	//echo '<meta http-equiv="refresh" content="0; URL='.base64_decode($url).'" />';
	//exit(0);
}

if (isset($_POST['btnHodReject'])) {
	$GM = $_SESSION['user']['staffId'];
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['pssdRemark']);
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];
	$status = 'Rejected by ' . $user;

	//$notifyHos = explode(', ',$_POST['HSEreviewName2']);
	$HODreviewName = trim($_POST['notifyHod']);
	$HODapprovedDate = date('Y-m-d h:i:s');
	$notifyHos1 = '0, ' . $_POST['createby2'];
	$notifyHos = explode(', ', $notifyHos1);

	$notifyRelatedDept = $_POST['notifyPssdHseOfficer'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$array = '1, ' . $notifyHos[1] . ', ' . findHosHod(6);
	$email_username = 0;
	$email_password = 0;
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	// write the update query
	$sql = "UPDATE ucuaform SET GM = '" . $GM . "', isverified = 0, HODreviewName='" . $HODreviewName . "', HODapprovedDate='" . $HODapprovedDate . "', verifiedby = '" . $GM . "', verifieddate = '" . $submitdate . "', isHODrejected = 1, HODrejectedby = '" . $GM . "', HODrejecteddate = '" . $submitdate . "', approvedby_date = '" . $submitdate . "', remark = '" . $status . "' WHERE Id = '" . $reportId . "' ";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files2']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Reject');

					$history = $_SESSION['user']['Name'] . ' adding feedback to report ID ' . $reportId . ' on ' . $submitdate;
					history($reportId, $user, $_SESSION['user']['AccessLevel'], 'FEEDBACK', $history, 'ADDED', $submitdate);
					//upload($filename, $tmp, $user, $reportId, $submitdate, $remark, $filename);
				} //if
			} //if
			$x++;
		} //for
	} else {
		//if there's no photo accept the comment only
		//ACTIVITY
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	$history = showName($GM) . ' rejected report ID ' . $reportId . ' on ' . $submitdate;
	history($reportId, $GM, $user, 'UPDATE', $history, 'REJECTED', $submitdate);

	$_SESSION['status'] .= " Report Has Been Rejected By PSSD HSE Officer";
	$_SESSION['status_code'] = "success";

	sendEmail($notifyHos[1], $notifyPssdHseOfficer, $history . '<br/>Remark: ' . $remark, $email_username, $email_password, $url, $array);
	//echo '<meta http-equiv="refresh" content="0; URL='.$url.'" />';
	//exit(0);
}

if (isset($_POST['feedbackbtn1'])) {
	$reportId = $_POST['reportId'];
	$location = isset($_POST['location']) ? $_POST['location'] : '';
	$offenceCode = $_POST['offenceCode'];
	$actionTaken = isset($_POST['actionTaken']) ? $_POST['actionTaken'] : '';
	$otherValue = $_POST['otherValue'];
	$violatorName = $_POST['violatorName'];
	$violatorDepartment = $_POST['violatorDepartment'];
	$violatorCompanyName = $_POST['violatorCompanyName'];
	$violatorICPassport = $_POST['violatorICPassport'];
	$violatorDatetime = $_POST['violatorDatetime'];
	$remark = str_replace("'", "", $_POST['pssdRemark']);
	$files2 = $_FILES['files2']['name'];
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];
	$actionTakenByHsePssd = implode(", ", $_POST['actionTakenByHsePssd']); //array
	$fine = $_POST['fine'];
	$from = $_POST['from'];
	$until = $_POST['until'];
	$month = $_POST['month'];
	$month2 = $_POST['month2'];
	$others = $_POST['others'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$history = showName($user) . ' adding feedback to report ID ' . $reportId . ' on ' . $submitdate;

	$sql = "UPDATE ucuaform SET followup = '" . $actionTakenByHsePssd . "', fineamount = '" . $fine . "', briefingFrom = '" . $from . "', briefingUntil = '" . $until . "', bannedMonths = '" . $month . "', bannedMonths2 = '" . $month2 . "', otherAction = '" . $others . "', isfollowup=1, followupby='" . $_SESSION['user']['Name'] . "', followupdate = '" . date('Y-m-d h:i:s') . "', OffenceCode = '" . $offenceCode . "', ActionTaken = '" . $actionTaken . "', otherValue = '" . $otherValue . "', ViolatorName = '" . $violatorName . "', ViolatorDept = '" . $violatorDepartment . "', violatorCompany = '" . $violatorCompanyName . "', ViolatorIc = '" . $violatorICPassport . "', violatorDatetime = '" . $violatorDatetime . "' WHERE Id = '" . $reportId . "' ";
	mysqli_query($db, $sql);
	//echo $sql;
	//echo $actionTakenByHsePssd;

	//activity($user, $reportId, $submitdate, $remark, $files2);
	//upload($filename, $tmp, $user, $reportId, $submitdate, $remark, $filename);
	//sendEmail($notifyHos, $notifyPssdHseOfficer, $history, $email_username, $email_password, 0);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');

	$fileNames = array_filter($_FILES['files2']['name']);
	//echo $fileNames;

	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Activity');

					//header('Location: default.php?page=uc_reportlist');
					//exit(0);
				} //if
			} //if
			$x++;
		} //for
	} else {
		//ACTIVITY
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	echo '<meta http-equiv="refresh" content="0; URL=' . $url . '" />';
	exit(0);
}

if (isset($_POST['feedbackbtn2'])) {
	$reportId = $_POST['reportId'];
	$location = isset($_POST['location']) ? $_POST['location'] : '';
	$offenceCode = $_POST['offenceCode'];
	$actionTaken = isset($_POST['actionTaken']) ? $_POST['actionTaken'] : '';
	$otherValue = $_POST['otherValue'];
	$violatorName = $_POST['violatorName'];
	$violatorDepartment = $_POST['violatorDepartment'];
	$violatorCompanyName = $_POST['violatorCompanyName'];
	$violatorICPassport = $_POST['violatorICPassport'];
	$violatorDatetime = $_POST['violatorDatetime'];
	$remark = str_replace("'", "", $_POST['pssdRemark']);
	$files2 = $_FILES['files2']['name'];
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];
	//$actionTakenByHsePssd = implode(", ", $_POST['actionTakenByHsePssd']); //array
	$fine = $_POST['fine'];
	$from = $_POST['from'];
	$until = $_POST['until'];
	$month = $_POST['month'];
	$month2 = $_POST['month2'];
	$others = $_POST['others'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$history = showName($user) . ' adding feedback to report ID ' . $reportId . ' on ' . $submitdate;

	$sql = "UPDATE ucuaform SET fineamount = '" . $fine . "', briefingFrom = '" . $from . "', briefingUntil = '" . $until . "', bannedMonths = '" . $month . "', bannedMonths2 = '" . $month2 . "', otherAction = '" . $others . "', isfollowup=1, followupby='" . $_SESSION['user']['Name'] . "', followupdate = '" . date('Y-m-d h:i:s') . "', OffenceCode = '" . $offenceCode . "', ActionTaken = '" . $actionTaken . "', otherValue = '" . $otherValue . "', ViolatorName = '" . $violatorName . "', ViolatorDept = '" . $violatorDepartment . "', violatorCompany = '" . $violatorCompanyName . "', ViolatorIc = '" . $violatorICPassport . "', violatorDatetime = '" . $violatorDatetime . "' WHERE Id = '" . $reportId . "' ";
	mysqli_query($db, $sql);
	//echo $sql;
	//echo $actionTakenByHsePssd;

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');

	$fileNames = array_filter($_FILES['files2']['name']);
	print_r($fileNames);

	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;

		echo $sql0;

		foreach ($_FILES['files2']['name'] as $key => $val) {
			// Get the file name and its extension
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)); // Convert to lowercase for extension comparison

			// Check if the file type is allowed
			if (in_array($fileType, $allowTypes)) {
				// Check if the file was successfully moved from the temporary upload location
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					// Insert photo information into the database
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d H:i:s"), 'Activity');
				} else {
					// Handle the case when the file could not be moved
					echo "Failed to upload file $fileName4.";
				}
			} else {
				// Handle the case when the file type is not allowed
				echo "File type $fileType of file $fileName4 is not allowed.";
			}
		} //for
	} else {
		//ACTIVITY
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);

			echo $sql0;
		}
	}

	echo '<meta http-equiv="refresh" content="0; URL=' . $url . '" />';
	exit(0);
}


if (isset($_POST['savedbtn'])) {
	$actionTakenByHsePssd = implode(", ", $_POST['actionTakenByHsePssd']); //array
	$fine = $_POST['fine'];
	$from = $_POST['from'];
	$until = $_POST['until'];
	$month = $_POST['month'];
	$month2 = $_POST['month2'];
	$others = $_POST['others'];
	$reportId = $_POST['reportId'];
	$url = $_POST['url'];

	$sql = "UPDATE ucuaform SET followup = '" . $actionTakenByHsePssd . "', fineamount = '" . $fine . "', briefingFrom = '" . $from . "', briefingUntil = '" . $until . "', bannedMonths = '" . $month . "', bannedMonths2 = '" . $month2 . "', otherAction = '" . $others . "', isfollowup=1, followupby='" . $_SESSION['user']['Name'] . "', followupdate = '" . date('Y-m-d h:i:s') . "' WHERE id = '" . $reportId . "' ";
	mysqli_query($db, $sql);

	//echo $sql;

	echo '<meta http-equiv="refresh" content="0; URL=' . $url . '" />';
	exit(0);
}


if (isset($_POST['reviewedBtn'])) {
	$reportId = $_POST['reportId'];
	$url = $_POST['url'];
	$user = $_POST['user'];
	$date = date('Y-m-d');
	$HOS = $_SESSION['user']['staffId'];
	$fineamount = isset($_POST['fineamount']) ? $_POST['fineamount'] : 0;
	//$actionTakenByHsePssd = implode(", ", $_POST['actionTakenByHsePssd']); //array

	//if(!empty($actionTakenByHsePssd)){
	//$chk2 = $actionTakenByHsePssd;
	//$chk3 = $actionTakenByHsePssd;
	//}else{
	//$chk2 = 0;
	//$chk3 = 0;
	//}

	$hosremark = str_replace("'", "", $_POST['pssdRemark']);
	$notifyHod = $_POST['notifyHod'];
	$history = '';

	$notifyPssdHseOfficer = 'khairij@outlook.com';
	//$notifyPssdHseOfficer = 'G_PortSafety@johorport.com.my';
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$history .= showName($HOS) . ' HOS upadated the follow-up details with &quot;' . $fineamount . '&quot; and &quot;' . $hosremark . '&quot; and sent Email to ' . $notifyHod . ' and ' . $notifyPssdHseOfficer . ' and approved report ID ' . $reportId . ' on ' . $submitdate;

	// write the update query
	$sql = "UPDATE `ucuaform` SET `HOS`='$HOS', reviewedby_date='$submitdate', isHosApproved=1, HosApprovedBy='$HOS', HosApprovedDate='$submitdate', HODreviewName='$notifyHod', fineamount='$fineamount', hosremark='$hosremark', evidenceImage2='', status = 'Approved by HOS' WHERE `Id`='$reportId'";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files2']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $hosremark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Activity');

					//header('Location: default.php?page=uc_reportlist');
					//exit(0);
				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($hosremark)) {
			//ACTIVITY
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $hosremark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	history($reportId, $HOS, $_SESSION['user']['AccessLevel'], 'REVIEWED', $history, 'SUCCESS', $submitdate);
	sendEmail($notifyHod, $notifyPssdHseOfficer, $history, $email_username, $email_password, $url, $notifyPssdHseOfficer);
	//echo '<meta http-equiv="Refresh" content="0; url='.$url.'" />';
	//exit(1);
}

if (isset($_POST['reassignBtn'])) {
	$reportId = $_POST['reportId'];
	$GM = $_SESSION['user']['staffId'];
	$submitdate = date('Y-m-d h:i:s');
	$url = $_POST['url'];
	$remark = str_replace("'", "", $_POST['pssdRemark']);
	$history = '';

	$sql = "UPDATE ucuaform SET isverified = 0, verifiedby = '$GM', verifieddate = '$submitdate', isapproved = 0, approvedby = '$GM', approveddate = '$submitdate',  approvedby_date = '$submitdate', isHosApproved = 0, HosApprovedBy= '$GM', HosApprovedDate = '$submitdate', isclosed = 0, closedby = '$GM', closeddate = '$submitdate', status = 'Reassigned by PSSD HOS' WHERE Id = '$reportId'";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files2']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Activity');

					//header('Location: default.php?page=uc_reportlist');
					//exit(0);
				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($remark)) {
			//ACTIVITY
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	$history .= showName($GM) . ' HOS reassigned report ID ' . $reportId . ' on ' . $submitdate;
	history($reportId, $GM, $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'SUCCESS', $submitdate);

	$_SESSION['status'] .= "9) Report Has Been Reassigned By HoS. ";
	$_SESSION['status_code'] = "success";
	echo '<meta http-equiv="Refresh" content="0; url=' . $url . '" />';
	exit(2);
}

if (isset($_POST['closeCasebtn'])) {
	try {
		$reportId = $_POST['reportId'];
		$HOS = $_SESSION['user']['staffId'];
		$fineamount = !empty($_POST['fineamount']) ? $_POST['fineamount'] : 0;
		//$actionTakenByHsePssd = implode(", ", $_POST['actionTakenByHsePssd']); //array

		//if(!empty($actionTakenByHsePssd)){
		//$chk2 = $actionTakenByHsePssd;
		//$chk3 = $actionTakenByHsePssd;
		//}else{
		//$chk2 = 0;
		//$chk3 = 0;
		//}

		$hodremark = str_replace("'", "", $_POST['pssdRemark']);
		$notifyPssdHseOfficer = 'helpdesk@johorport.com.my';
		//$notifyPssdHseOfficer = 'G_PortSafety@johorport.com.my';
		$url = $_POST['url'];
		$user = $_POST['user'];
		$AccessLevel = $_SESSION['user']['AccessLevel'];
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$submitdate = date("Y-m-d h:i:s");

		$today = date('Y-m-d h:i:s');
		//New requirement 15/5/2023
		$closeddate = $today . '+ 7 days';

		$history = showName($HOS) . ' HOD updated the follow-up details with &quot;' . $fineamount . '&quot; and &quot;' . $hodremark . '&quot; and approved and closed report ID ' . $reportId . ' on ' . $submitdate;

		// write the update query
		$query = "UPDATE ucuaform SET isclosed=1, closedby='" . $HOS . "', closeddate='" . $closeddate . "', isHODapproved=1, HODapprovedBy='" . $HOS . "', HODapprovedDate='" . $submitdate . "', reviewedby_date='" . $submitdate . "', fineamount='" . $fineamount . "', hodremark='" . $hodremark . "', status = 'Approved by HODs' WHERE Id ='" . $reportId . "' ";
		mysqli_query($db, $query);
		//echo $query;

		//activity
		activity($user, $reportId, $submitdate, $hodremark, 0);
		history($reportId, $HOS, $AccessLevel, 'UPDATE', $history, 'APPROVED', $submitdate);
		//ucuapix($reportId, $filename2, $HOS, $submitdate);

		$_SESSION['status'] .= "12) Report Has Been Reviewed By HoD. ";
		$_SESSION['status_code'] = "success";

		echo '<p>Please wait...</p>';
		sendEmail($notifyPssdHseOfficer, $notifyPssdHseOfficer, $history, 'email_username', 'email_password', $url, $notifyPssdHseOfficer);
		//echo '<meta http-equiv="Refresh" content="0; url='.$url.'" />';
		//exit(0);
	} catch (Exception $e) {
		die($e->getMessage());
	}
}


if (isset($_POST['HodReassignBtn'])) {
	$url = $_POST['url'];
	$HOS = $_SESSION['user']['Name'];
	$reportId = $_POST['reportId'];
	$remark = str_replace("'", "", $_POST['pssdRemark']);
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$reopen = $_POST['reopen'] + 1;
	$history = $HOS . ' HOD reassigned report ID ' . $reportId . ' ' . $reopen . 'x on ' . $submitdate;

	// write the update query
	$sql = "UPDATE `ucuaform` SET `HOS`='$HOS', reviewedby_date='$submitdate', isverified=0, isfollowup=0, isupload=0, isconfirm=0, isNotifyHos=0, isHosApproved=0, isHODapproved=0, isclosed=0, closedby='$HOS', closeddate='$submitdate', isreopen='$reopen', remark='$remark', status = 'Reassigned by HOD' WHERE `Id`='$reportId'";
	mysqli_query($db, $sql);

	//activity($_SESSION['user']['staffId'], $reportId, $submitdate, $remark, 0);
	history($reportId, $HOS, $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'APPROVED', $submitdate);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files2']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files2']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files2']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files2"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Activity');

					//header('Location: default.php?page=uc_reportlist');
					//exit(0);
				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($remark)) {
			//ACTIVITY
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	//echo $sql;
	$_SESSION['status'] = " Report has been reopen by HoD";
	$_SESSION['status_code'] = "success";
	echo '<meta http-equiv="Refresh" content="0; url=' . $url . '" />';
	exit(0);
}
