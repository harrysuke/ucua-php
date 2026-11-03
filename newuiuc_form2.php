<?php
include('testfunctions.php');
$_SESSION['success'] = '';
$_SESSION['error'] = '';
$_SESSION['status'] = '';

//hse reps/hos/hod <> u see/u act (ucua)
//public user after approved can see activities else cannot see (ucua)
function sendEmail($notifyHos, $notifyPssdHseOfficer, $history, $email_username, $email_password, $url, $notifyRelatedDept)
{
	echo 'Please use EMail Server.';
	$fullurl = 'http://192.168.1.40/ucua/notification2.php';
	$fullurl .= '?notifyHos=' . $notifyHos;
	$fullurl .= '&notifyPssdHseOfficer=' . $notifyPssdHseOfficer;
	$fullurl .= '&history=' . $history;
	$fullurl .= '&email_username=' . base64_encode($email_username);
	$fullurl .= '&email_password=' . base64_encode($email_password);
	$fullurl .= '&url=' . $url;
	$fullurl .= '&notifyRelatedDept=' . $notifyRelatedDept;
	echo '<meta http-equiv="refresh" content="0; URL=' . $fullurl . '" />';
}

function showName($n)
{
	try {
		global $db;
		$sql = "SELECT Name, Email FROM user WHERE staffId = '" . $n . "'";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['Name'];
	} catch (Exception $e) {
		return $e->getMessage();
	}
}

function insertUcuaphoto($ucuaform_Id, $evidenceImage, $createby, $createdate)
{
	global $db;
	$sql = "INSERT INTO ucuaphoto (ucuaform_Id, evidenceImage, createby, createdate, type) VALUES (" . $ucuaform_Id . ", '" . $evidenceImage . "', '" . $createby . "', '" . $createdate . "', 'Activity') ";
	if (mysqli_query($db, $sql)) {
		$_SESSION['success'] .= " Photos uploaded successfully.";
	} else {
		$_SESSION['error'] .= " Error " . mysqli_error($db);
	}
	//echo $sql;
}

function activity($GM, $reportId, $submitdate, $remark, $evidenceImage2)
{
	global $db;
	$sql = "INSERT INTO activity (ucuaid, staffid, activity, uploadfilename, datetimestamp) VALUES ($reportId, '$GM', '$remark', '$evidenceImage2', '$submitdate')";
	mysqli_query($db, $sql);

	//echo $sql;
	$_SESSION['status'] .= "3) Activity added successfully ";
	$_SESSION['status_code'] = "success";
}

function updateSheReps($GM, $reportId, $submitdate, $remark, $evidenceImage2)
{
	global $db;
	$action = 'Notify Related Parties';
	$sql = "UPDATE ucuaform SET HSEReps = '$GM', ActionTaken = '$action', isnotifysherep = 1, notifysherepby='$GM', notifysherepdate='$submitdate', pssdremark='$remark', evidenceImage2='$evidenceImage2' WHERE Id = '$reportId'";
	if (mysqli_query($db, $sql)) {
		$_SESSION['status'] .= "2) Record updated successfully";
		$_SESSION['status_code'] = "success";
	} else {
		$_SESSION['status'] .= "3) Error " . mysqli_error($db);
		$_SESSION['status_code'] = "error";
	}
}

function upload($filename, $tmp, $GM, $reportId, $submitdate, $remark, $filename0, $user)
{
	if (!empty($filename)) {
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($filename);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		$check = getimagesize($tmp);
		if ($check !== false) {
			$_SESSION['status'] .= "19) File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$_SESSION['status'] .= "20) File is not an image.";
			$uploadOk = 0;
		}

		// Check if file already exists
		if (file_exists($target_file)) {
			$_SESSION['status'] .= "21) Sorry, file already exists.";
			$uploadOk = 0;
		}

		// Allow certain file formats
		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
			$_SESSION['status'] .= "22) Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$_SESSION['status'] .= "23) Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($tmp, $target_file)) {
				$_SESSION['status'] .= "24) Report Has Been Approved By PSSD HSE Officer";
				$_SESSION['status_code'] = "success";
				updateSheReps($GM, $reportId, $submitdate, $remark, $target_file);
				//activity($_SESSION['user']['staffId'], $reportId, $submitdate, $remark, $target_file);
			}
		}
	} else {
		$_SESSION['status'] .= "26) Image was not uploaded ";
		$_SESSION['status_code'] = "error";
		$remark1 = $user . 'Failed to upload photo for report id ' . $reportId;
		//activity($_SESSION['user']['staffId'], $reportId, $submitdate, $remark, 0);
		updateSheReps($GM, $reportId, $submitdate, $remark, $filename0);
	}
}

function history($ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp)
{
	global $db;
	$sql = "INSERT INTO  history (ucuaid, staffid, accessLevel, action, activity, status, datetimestamp) VALUES (" . $ucuaid . ", '" . $staffid . "', '" . $accessLevel . "', '" . $action . "', '" . $activity . "', '" . $status . "', '" . $datetimestamp . "')";
	mysqli_query($db, $sql);
}

function activity2($GM, $reportId, $submitdate, $remark, $evidenceImage2)
{
	global $db;

	if (!empty($filename)) {
		$file = 'uploads/' . $evidenceImage2;
		$sql = "INSERT INTO activity (ucuaid, staffid, activity, uploadfilename, datetimestamp) VALUES ($reportId, '$GM', '$remark', '$file', '$submitdate')";
		mysqli_query($db, $sql);
		$_SESSION['status'] .= "4) Activity added successfully";
		$_SESSION['status_code'] = "success";
	} else {
		$sql = "INSERT INTO activity (ucuaid, staffid, activity, uploadfilename, datetimestamp) VALUES ($reportId, '$GM', '$remark', 0, '$submitdate')";
		mysqli_query($db, $sql);
	}
}

function HSEReps($reportId)
{
	global $db;
	$sql = "UPDATE ucuaform SET isverified = 2, status = 'Pending HSE Reps' WHERE Id = '$reportId'";
	mysqli_query($db, $sql);
}

function findHosHod($no)
{
	global $db;
	$sql = "SELECT Name, Email FROM viewhoshod WHERE AccessLevel = '" . $no . "'";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_assoc($result);
	return $row['Email'];
}

//CALL FROM BTNSAVE
function updateUCUA($observerName, $observerDepartment, $observerEmail, $observerConditionDetails, $conditionDetails, $remark, $actionTaken2, $id)
{
	global $db;
	$sql = "UPDATE ucuaform SET 
	remark = '" . $remark . "', 
	ActionTaken = '" . $actionTaken2 . "', 
	isverified = 0 
	WHERE id = '" . $id . "' ";
	mysqli_query($db, $sql);
	//echo $sql;
}

if (isset($_POST['btnSave'])) {
	try {
		$staffid = $_SESSION['user']['staffId'];
		$fullurl = $_POST['fullurl'];
		$id = $_POST['id'];
		$relatedDept2 = $_POST['relatedDept2'];
		$HOSreviewName = $_POST['HOSreviewName'];
		$conditionDetails = $_POST['conditionDetails'];
		//$location = $_POST['location'];
		//conditionDetails = $_POST['conditionDetails'];
		//$actionTaken = $_POST['actionTaken'];
		$array = '1, ' . $relatedDept2 . ', ' . $relatedDept2;
		$email_username = 0;
		$email_password = 0;

		if (!empty($_POST['relatedDept'])) {
			$relatedDept = $_POST['relatedDept'];
		} else {
			$relatedDept = '';
		}

		//$observerName = $_POST['observerName'];
		//$observerDepartment = $_POST['observerDepartment'];
		//$observerEmail = $_POST['observerEmail'];
		//$observerConditionDetails = $_POST['observerConditionDetails'];
		$remark = str_replace("'", "", $_POST['remark']);

		$fileName = $_FILES["files"]["name"];
		$target_dir = "uploads/";
		$allowTypes = array('jpg', 'png', 'jpeg', 'gif');

		$sql = "UPDATE ucuaform SET relatedDept = '" . $relatedDept2 . "' WHERE Id = '" . $id . "' ";
		mysqli_query($db, $sql);

		switch ($actionTaken) {
			case "Notify Related Department":
				$actionTaken2 = $actionTaken . ', ' . $relatedDept;
				break;
			case "Others":
				$actionTaken2 = $actionTaken . ', ' . $_POST['others'];
				break;
		}
		$fileNames = array_filter($_FILES['files']['name']);

		if (!empty($fileNames)) {
			//ACTIVITY
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $id . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);

			$last_id = mysqli_insert_id($db);
			$x = 0;
			foreach ($_FILES['files']['name'] as $key => $val) {
				$fileName4 = basename($_FILES['files']['name'][$key]);
				//$file_parts3 = explode(".",$filename4);
				//$file_end_part3 = end($file_parts3);
				//$filename5 = $x.date('YmdHisv') . "." . $file_end_part3;
				$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
				$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
				if (in_array($fileType, $allowTypes)) {
					if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
						//PHOTOS
						insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
						//UPDATE UCUA
						updateUCUA($observerName, $observerDepartment, $observerEmail, $observerConditionDetails, $conditionDetails, $remark, $actionTaken2, $id);
					}
				}
				$x++;
			} //for
			//mysqli_close($db);

			$history = $_SESSION['user']['Name'] . ' added activity for report ID ' . $id . ' on ' . date("Y-m-d h:i:s");
			history($id, $staffid, $_SESSION['user']['AccessLevel'], 'ACTIVITY', $history, 'SUCCESS', date("Y-m-d h:i:s"));

			$_SESSION['status'] .= ' Record has been saved successfully.';
			$_SESSION['status_code'] = 'success';
			header('location: ' . $fullurl);
			exit;
		} else {
			//ACTIVITY
			if (!empty($remark)) {
				$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $id . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
				mysqli_query($db, $sql0);
			}

			echo 'Photo is not upload.';
			//UPDATE UCUA
			updateUCUA($observerName, $observerDepartment, $observerEmail, $observerConditionDetails, $conditionDetails, $remark, $actionTaken2, $id);

			$history = $_SESSION['user']['Name'] . ' update report ID ' . $id . ' on ' . date("Y-m-d h:i:s");
			history($id, $staffid, $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'SUCCESS', date("Y-m-d h:i:s"));

			$_SESSION['status'] .= ' Record has been saved successfully.';
			$_SESSION['status_code'] = 'success';
			header('location: ' . $fullurl);
			exit;
		}
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

//$conditionDetails = $_POST['conditionDetails'];
if (isset($_POST['btnClose'])) {
	$staffid = $_SESSION['user']['staffId'];
	$fullurl = $_POST['fullurl'];
	$id = $_POST['id'];
	$location = $_POST['location'];
	$actionTaken = $_POST['actionTaken'];
	$observerName = $_POST['observerName'];
	$observerDepartment = $_POST['observerDepartment'];
	$observerEmail = $_POST['observerEmail'];
	$observerConditionDetails = $_POST['observerConditionDetails'];
	$remark = str_replace("'", "", $_POST['remark']);
	$history = $_SESSION['user']['Name'] . ' closed case for report ID ' . $id . ' on ' . date("Y-m-d h:i:s");

	switch ($actionTaken) {
		case "Notify Related Department":
			$actionTaken2 = $_POST['actionTaken'];
			break;
		case "Others":
			$actionTaken2 = $_POST['others'];
			break;
	}

	//UPDATE UCUA
	$sqlx = "UPDATE ucuaform SET 
	remark = '" . $remark . "', 
	isUserClosed = 1, 
	userClosedBy = '" . $observerName . "', 
	userClosedDate = '" . date("Y-m-d H:i:s") . "', 
	ActionTaken = '" . $actionTaken2 . "', 
	closedEvidence = '" . $conditionDetails . "', 
	status = 'Report closed by Public User',
	isverified = 2
	WHERE id = '" . $id . "' ";
	mysqli_query($db, $sqlx);
	//echo $sqlx;

	$filename = $_FILES["files"]["name"];
	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $id . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql);
		$last_id = mysqli_insert_id($db);

		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			//$file_parts3 = explode(".",$filename4);
			//$file_end_part3 = end($file_parts3);
			//$filename5 = $x.date('YmdHisv') . "." . $file_end_part3;
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
					history($id, $staffid, $_SESSION['user']['AccessLevel'], 'REQUEST CLOSE', $history, 'CLOSED', date("Y-m-d h:i:s"));
				}
			}
			$x++;
		} //for    
	} else {
		if (!empty($remark)) {
			//ACTIVITY
			$sql = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $id . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql);
			history($id, $staffid, $_SESSION['user']['AccessLevel'], 'REQUEST CLOSE', $history, 'CLOSED', date("Y-m-d h:i:s"));
		}
	}
	//mysqli_close($db);
	header('location: ' . $fullurl);
	exit;
}

if (isset($_POST['btnApprove4'])) {
	$GM = $_SESSION['user']['staffId'];
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['remark']);
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];

	$HOSreviewName = '0, ' . $_POST['HOSreviewName'];
	$notifyHos = explode(', ', $HOSreviewName);
	$notifyRelatedDept = $_POST['PICreviewName2'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$array = '1, ' . $notifyHos[1] . ', ' . findHosHod(6);
	$email_username = 0;
	$email_password = 0;
	$submitdate = date("Y-m-d h:i:s");

	// write the update query
	$sql = "UPDATE ucuaform SET GM = '" . $GM . "', isapproved = 1, approvedby = '" . $GM . "', approveddate = '" . $submitdate . "', isverified = 1, verifiedby = '" . $GM . "', verifieddate = '" . $submitdate . "', isNotifyHos = 1, notifyHosBy = '" . $GM . "', notifyHosDate = '" . $submitdate . "', HOSreviewName = '" . $HOSreviewName . "', approvedby_date = '" . $submitdate . "', status = 'Approved by PSSD Officer' WHERE Id = '" . $reportId . "' ";
	mysqli_query($db, $sql);
	//echo $sql;

	//upload images
	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	$history = showName($GM) . ' approved report ID ' . $reportId . ' on ' . $submitdate;
	history($reportId, $GM, $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'APPROVED', $submitdate);
	//$history = showName($GM).' PSSD HSE Officer sent notification to SHE Reps: '.$notifyHos[1].' for report ID '.$reportId.' on '.$submitdate;
	//history($reportId, $GM, $user, 'NOTIFICATION', $history, 'EMAIL', $submitdate);
	HSEReps($reportId);
	$_SESSION['status'] .= " Report Has Been Approved By PSSD HSE Officer";
	$_SESSION['status_code'] = "success";
	//4 August, 2023: Pn Zaini request to receive email
	sendEmail($notifyHos[1], $notifyPssdHseOfficer, $history, $email_username, $email_password, $url, $array);
	//echo '<meta http-equiv="refresh" content="0; URL='.$url.'" />';
	//exit(0);
}

if (isset($_POST['btnHosReject'])) {
	$GM = $_SESSION['user']['staffId'];
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['remark']);
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];
	$status = 'Rejected by ' . $user;

	//$notifyHos = explode(', ',$_POST['HSEreviewName2']);
	$HOSreviewName = $_POST['HOSreviewName'];
	$HosApprovedDate = date('Y-m-d h:i:s');
	$notifyHos1 = '0, ' . $_POST['createby'];
	$notifyHos = explode(', ', $notifyHos1);

	$notifyRelatedDept = $_POST['PICreviewName2'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$array = '1, ' . $notifyHos[1] . ', ' . findHosHod(6);
	$email_username = 0;
	$email_password = 0;
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	// write the update query
	$sql = "UPDATE ucuaform SET GM = '" . $GM . "', isverified = 0, HOSreviewName='" . $HOSreviewName . "', HosApprovedDate='" . $HosApprovedDate . "', verifiedby = '" . $GM . "', verifieddate = '" . $submitdate . "', isHOSrejected = 1, HOSrejectedby = '" . $GM . "', HOSrejecteddate = '" . $submitdate . "', approvedby_date = '" . $submitdate . "', remark = '" . $status . "' WHERE Id = '" . $reportId . "' ";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));

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
	$remark = str_replace("'", "", $_POST['remark']);
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];
	$status = 'Rejected by ' . $user;

	//$notifyHos = explode(', ',$_POST['HSEreviewName2']);
	$HODreviewName = trim($_POST['HODreviewName']);
	$HODapprovedDate = date('Y-m-d h:i:s');
	$notifyHos1 = '0, ' . $_POST['createby'];
	$notifyHos = explode(', ', $notifyHos1);

	$notifyRelatedDept = $_POST['PICreviewName2'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$array = '1, ' . $notifyHos[1] . ', ' . findHosHod(6);
	$email_username = 0;
	$email_password = 0;
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	// write the update query
	$sql = "UPDATE ucuaform SET GM = '" . $GM . "', isverified = 0, verifiedby = '" . $GM . "', HODreviewName='" . $HODreviewName . "', HODapprovedDate='" . $HODapprovedDate . "', verifieddate = '" . $submitdate . "', isHODrejected = 1, HODrejectedby = '" . $GM . "', HODrejecteddate = '" . $submitdate . "', approvedby_date = '" . $submitdate . "', remark = '" . $status . "' WHERE Id = '" . $reportId . "' ";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));

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

if (isset($_POST['btnReject4'])) {
	$GM = $_SESSION['user']['staffId'];
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['remark']);
	$url = $_POST['url'];
	$user = $_SESSION['user']['Name'];
	$userEmail = trim($_POST['createby']);
	$status = 'Rejected by ' . $user;

	//$notifyHos = explode(', ',$_POST['HSEreviewName2']);
	$notifyHos1 = '0, ' . $_POST['createby'];
	$notifyHos = explode(', ', $notifyHos1);

	$notifyRelatedDept = $_POST['PICreviewName2'];
	$notifyPssdHseOfficer = $_POST['notifyPssdHseOfficer'];
	$array = '1, ' . $notifyHos[1] . ', ' . findHosHod(6);
	$email_username = 0;
	$email_password = 0;
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	// write the update query
	$sql = "UPDATE ucuaform SET GM = '$GM', isverified = 0, verifiedby = '$GM', verifieddate = '$submitdate', isrejected = 1, rejectedby = '$GM', rejecteddate = '$submitdate', approvedby_date = '$submitdate', status = '$status' WHERE Id = '$reportId'";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));

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

if (isset($_POST['feedbackbtn'])) {
	$user = $_SESSION['user']['staffId'];
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['remark']);
	//$notifyHos = $_POST['notifySheReps'];
	//$notifyPssdHseOfficer = '';
	$filename0 = '';
	$tmp = $_FILES["files"]["tmp_name"];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));

					$history = $_SESSION['user']['Name'] . ' adding feedback to report ID ' . $reportId . ' on ' . $submitdate;
					history($reportId, $user, $_SESSION['user']['AccessLevel'], 'FEEDBACK', $history, 'ADDED', $submitdate);
					//upload($filename, $tmp, $user, $reportId, $submitdate, $remark, $filename);
					//sendEmail($notifyHos, $notifyPssdHseOfficer, $history, $email_username, $email_password);
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
	echo '<meta http-equiv="refresh" content="0; URL=' . $_POST['fullurl'] . '" />';
	exit;
}

if (isset($_POST['submitbtn6'])) {
	$remark = str_replace("'", "", $_POST['remark']);
	$reportId = $_POST['id'];
	$user = $_SESSION['user']['staffId'];
	$date = date('Y-m-d');
	$HOS = $_POST['staffId'];
	$HODreviewName = $_POST['HODreviewName'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$notifyHos = findHosHod(6);
	$notifyPssdHseOfficer = findHosHod(7);
	$url = $_POST['url'];
	$array = '1, ' . $notifyHos . ', ' . findHosHod(6);

	$sql = "UPDATE `ucuaform` SET `HOS`='$HOS', reviewedby_date='$submitdate', isHosApproved=1, HosApprovedBy='$HOS', HosApprovedDate='$submitdate', HODreviewName='$HODreviewName', evidenceImage2='', status = 'Pending HOD' WHERE `Id`='$reportId'";
	mysqli_query($db, $sql);

	//ucuapix($reportId, $filename2, $user, $submitdate);			

	$history = showName($user) . ' updated report ID ' . $reportId . ' on ' . $date;
	history($reportId, $HOS, $_SESSION['user']['AccessLevel'], 'APPROVED', $history, 'SUCCESS', $submitdate);


	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
					// write the update query

				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}
	sendEmail($HODreviewName, $notifyPssdHseOfficer, $history, 'email_username', 'email_password', $url, $array);
	exit;
}

if (isset($_POST['HosReassignBtn'])) {
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['remark']);
	$notifyHos = findHosHod(6);
	$notifyPssdHseOfficer = findHosHod(7);
	$array = '1, ' . $notifyHos . ', ' . findHosHod(6);
	$history = $_SESSION['user']['Name'] . ' HOS reassigned report ID ' . $reportId . ' on ' . date('Y-m-d h:i:s');

	$sql = "UPDATE ucuaform SET isverified=0, isapproved=0, isnotifysherep=0, isNotifyHos=0, isHosApproved=0, isHODapproved=0, status = 'Reassigned by HOD' WHERE Id = '" . $_POST['id'] . "' ";
	mysqli_query($db, $sql);


	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
					// write the update query

				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	sendEmail($notifyHos, $notifyPssdHseOfficer, $history, 'email_username', 'email_password', $_POST['url'], $array);
}

if (isset($_POST['submitbtn7'])) {
	$reportId = $_POST['id'];
	$user = $_SESSION['user']['staffId'];
	$date = date('Y-m-d');

	$HOS = $_SESSION['user']['staffId'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");

	$remark = str_replace("'", "", $_POST['remark']);
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$history = '';

	// write the update query
	$sql = "UPDATE `ucuaform` SET reviewedby_date='$submitdate', isHODapproved=1, HODapprovedBy='$HOS', HODapprovedDate='$submitdate', isclosed=1, closedby='$HOS', closeddate='$submitdate', evidenceImage2='', status='Approved by HOD' WHERE `Id`='$reportId'";
	mysqli_query($db, $sql);

	//echo $sql;

	$history .= showName($HOS) . ' approved report ID ' . $reportId . ' on ' . $submitdate;
	history($reportId, $HOS, $_SESSION['user']['AccessLevel'], 'APPROVED', $history, 'SUCCESS', $submitdate);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));

					//ucuapix($reportId, $filename2, $HOS, $submitdate);
				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	echo '<meta http-equiv="refresh" content="0; URL=' . $_POST['fullurl'] . '" />';
	exit;
}

if (isset($_POST['HodReassignBtn'])) {
	$reportId = $_POST['id'];
	$remark = str_replace("'", "", $_POST['remark']);
	$notifyHos = findHosHod(6);
	$notifyPssdHseOfficer = findHosHod(7);
	$array = '1, ' . $notifyHos . ', ' . findHosHod(6);
	$history = $_SESSION['user']['Name'] . ' HOD reassigned report ID ' . $reportId . ' on ' . date('Y-m-d h:i:s');

	$sql = "UPDATE ucuaform SET isNotifyHos=1, isHosApproved=0, isHODapproved=0, status = 'Reassigned by HOD' WHERE Id = '" . $_POST['id'] . "' ";
	mysqli_query($db, $sql);

	$target_dir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		//ACTIVITY
		$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
		mysqli_query($db, $sql0);
		$last_id = mysqli_insert_id($db);
		$x = 0;
		foreach ($_FILES['files']['name'] as $key => $val) {
			$fileName4 = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					//PHOTOS
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));

					//ucuapix($reportId, $filename2, $HOS, $submitdate);
				} //if
			} //if
			$x++;
		} //for
	} else {
		if (!empty($remark)) {
			$sql0 = "INSERT INTO activity (ucuaid, staffid, activity, datetimestamp) VALUES ('" . $reportId . "', '" . $_SESSION['user']['staffId'] . "', '" . $remark . "', '" . date("Y-m-d h:i:s") . "')";
			mysqli_query($db, $sql0);
		}
	}

	sendEmail($notifyHos, $notifyPssdHseOfficer, $history, 'email_username', 'email_password', $_POST['url'], $array);
	//echo '<meta http-equiv="refresh" content="0; URL='.$_POST['fullurl'].'" />';
	//exit(0);
}
