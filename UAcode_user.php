<?php
include('testFunctions.php');

$_SESSION['error'] = '';
$_SESSION['success'] = '';

function history($ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp)
{
	global $db;
	$sql = "INSERT INTO history (ucuaid, staffid, accessLevel, action, activity, status, datetimestamp) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("issssss", $ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp);
	if ($stmt->execute()) {
		$_SESSION['success'] .= " History created successfully.";
	} else {
		$_SESSION['error'] .= " Error " . $stmt->error;
	}
	$stmt->close();
}

function ucuapix($ucuaid, $pixname, $uploadedby, $uploadeddate)
{
	global $db;
	$sql = "INSERT INTO ucuapix (ucuaid, pixname, uploadedby, uploadeddate) VALUES (?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("isss", $ucuaid, $pixname, $uploadedby, $uploadeddate);
	if ($stmt->execute()) {
		$_SESSION['success'] .= " Photos uploaded and log successfully.";
	} else {
		$_SESSION['error'] .= " Error " . $stmt->error;
	}
	$stmt->close();
}

function insertUcuaphoto($ucuaform_Id, $evidenceImage, $createby, $createdate, $type)
{
	global $db;
	$sql = "INSERT INTO ucuaphoto (ucuaform_Id, evidenceImage, createby, createdate, type) VALUES (?, ?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("issss", $ucuaform_Id, $evidenceImage, $createby, $createdate, $type);
	if ($stmt->execute()) {
		$_SESSION['success'] .= " Photos uploaded successfully.";
	} else {
		$_SESSION['error'] .= " Error " . $stmt->error;
	}
	$stmt->close();
}

function getAllowedFileExtensions()
{
	global $db;
	$query = "SELECT prefix FROM prefix";
	$result = mysqli_query($db, $query);
	$allowTypes = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$allowTypes[] = $row['prefix'];
	}
	return $allowTypes;
}

function handleFileUploads($last_id, $target_dir, $allowTypes, $fileInputName, $photoType)
{
	if (isset($_FILES[$fileInputName]) && !empty(array_filter($_FILES[$fileInputName]['name']))) {
		foreach ($_FILES[$fileInputName]['name'] as $key => $val) {
			$fileName = basename($_FILES[$fileInputName]['name'][$key]);
			$targetFilePath = $target_dir . date("YmdHis") . gettimeofday()["usec"] . "_" . $fileName;
			$fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

			if (in_array($fileType, $allowTypes)) {
				if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"][$key], $targetFilePath)) {
					insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d H:i:s"), $photoType);
				} else {
					// Log the error for debugging, but don't exit the script
					error_log("Failed to upload file $fileName from input $fileInputName.");
				}
			} else {
				error_log("File type $fileType of file $fileName is not allowed.");
			}
		}
	}
}

if (isset($_POST['submit_btn'])) {
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();
	$uploadOk = 1;
	$location = $_POST['location'];
	$offencecode = implode(", ", $_POST['offenceCode']); //array
	$staffid = $_SESSION['user']['staffId'];
	$observerName = $_POST['observerName'];
	$observerDepartment = $_POST['observerDepartment'];
	$observerEmail = $_POST['observerEmail'];
	$observerDatetime = $_POST['observerDatetime'];
	$vioname = str_replace("'", "", $_POST['violatorName']);
	$violatorContactNo = $_POST['violatorContactNo'];
	$VioDept = $_POST['violatorDepartment'];
	$vioCompany = $_POST['violatorCompanyName'];
	$vioic = $_POST['violatorICPassport'];
	$violatorDatetime = $_POST['violatorDatetime'];
	$chk1 = empty($_POST['actionTaken']) ? '' : $_POST['actionTaken'];
	$direct = $_POST['url'];
	$submitdate = date('Y-m-d H:i:s');
	$otherValue = $_POST['otherValue'];
	$sname = $_SESSION['user']['Name'];
	$sdepartment = $_SESSION['user']['Department'];
	$semail = $_SESSION['user']['Email'];
	$formType = 'Unsafe Action';
	$status = 'Pending PSSD HSE Officer';

	try {
		$sql = "INSERT INTO ucuaform (
			submitdate, LocationId, OffenceCode, staffId, form_type, ViolatorName, violatorContactNo,
			ViolatorDept, violatorCompany, ViolatorIc, violatorDatetime, ActionTaken, otherValue,
			createby, createdate, status, observerName, observerDepartment, observerEmail, observerDatetime
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$stmt->bind_param(
			"ssssssssssssssssssss",
			$submitdate,
			$location,
			$offencecode,
			$staffid,
			$formType,
			$vioname,
			$violatorContactNo,
			$VioDept,
			$vioCompany,
			$vioic,
			$violatorDatetime,
			$chk1,
			$otherValue,
			$staffid,
			$submitdate,
			$status,
			$sname,
			$sdepartment,
			$semail,
			$submitdate
		);
		if ($stmt->execute()) {
			$last_id = $stmt->insert_id;
			//MULTI IMAGES UPLOAD 21/2/2023
			handleFileUploads($last_id, $target_dir, $allowTypes, 'files', 'Photo Evidence');
			handleFileUploads($last_id, $target_dir, $allowTypes, 'files2', 'Violator Evidence');

			// Log the action to the history
			$history = $staffid . ' created report ID ' . $last_id . ' and uploaded ' . implode(' ', $_FILES['files']['name']) . ' on ' . $submitdate;
			history($last_id, $staffid, $_SESSION['user']['AccessLevel'], 'UPLOAD', $history, 'SUCCESS', $submitdate);
			//ucuapix($last_id, $_FILES['files']['name'], $staffid, $submitdate);

			$_SESSION['status'] .= " Report Has Been Submitted successfully.";
			$_SESSION['status_code'] = "success";
			echo '<meta http-equiv="refresh" content="0; URL=default.php?page=newuiua_form&id=' . base64_encode($last_id) . '" />';
			exit();
		}
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

if (isset($_POST['draft_btn'])) {
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();

	$uploadOk = 1;

	$location = $_POST['location'];
	$offencecode = implode(", ", $_POST['offenceCode']); //array
	$staffid = $_SESSION['user']['staffId'];
	$observerName = $_POST['observerName'];
	$observerDepartment = $_POST['observerDepartment'];
	$observerEmail = $_POST['observerEmail'];
	$observerDatetime = $_POST['observerDatetime'];
	$vioname = $_POST['violatorName'];
	$violatorContactNo = $_POST['violatorContactNo'];
	$VioDept = $_POST['violatorDepartment'];
	$vioCompany = $_POST['violatorCompanyName'];
	$vioic = $_POST['violatorICPassport'];
	$violatorDatetime = $_POST['violatorDatetime'];
	$chk1 = empty($_POST['actionTaken']) ? '' : $_POST['actionTaken'];
	$direct = $_POST['url'];
	$submitdate = date('Y-m-d H:i:s');
	$otherValue = $_POST['otherValue'];
	$sName = $_SESSION['user']['Name'];
	$sDepartment = $_SESSION['user']['Department'];
	$sEmail = $_SESSION['user']['Email'];


	try {
		$sql = "INSERT INTO ucuadraft (
			submitdate, LocationId, OffenceCode, staffId, form_type, ViolatorName, violatorContactNo,
			ViolatorDept, violatorCompany, ViolatorIc, violatorDatetime, ActionTaken, otherValue,
			createby, createdate, status, observerName, observerDepartment, observerEmail, observerDatetime
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$stmt->bind_param(
			"ssssssssssssssssssss",
			$submitdate,
			$location,
			$offencecode,
			$staffid,
			$formType,
			$vioname,
			$violatorContactNo,
			$VioDept,
			$vioCompany,
			$vioic,
			$violatorDatetime,
			$chk1,
			$otherValue,
			$staffid,
			$submitdate,
			$status,
			$sName,
			$sDepartment,
			$sEmail,
			$submitdate
		);
		if ($stmt->execute()) {
			$last_id = $stmt->insert_id;
			//MULTI IMAGES UPLOAD 21/2/2023
			handleFileUploads($last_id, $target_dir, $allowTypes, 'files', 'Photo Evidence');
			handleFileUploads($last_id, $target_dir, $allowTypes, 'files2', 'Violator Evidence');

			// Log the action to the history
			$history = $staffid . ' created report ID ' . $last_id . ' and uploaded ' . implode(' ', $_FILES['files']['name']) . ' on ' . $submitdate;
			history($last_id, $staffid, $_SESSION['user']['AccessLevel'], 'UPLOAD', $history, 'SUCCESS', $submitdate);
			//ucuapix($last_id, $_FILES['files']['name'], $staffid, $submitdate);

			$_SESSION['status'] .= " Report Has Been Submitted successfully.";
			$_SESSION['status_code'] = "success";
			echo '<meta http-equiv="refresh" content="0; URL=default.php?page=ua_reportlist-individual_draft" />';
			exit;
		}
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

if (isset($_POST['update'])) {
	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();
	$last_id = $_POST['Id'];
	$offencecode = implode(", ", $_POST['offenceCode']); //array
	$locationId = $_POST['LocationId'];
	$violatorName = $_POST['ViolatorName'];
	$violatorContactNo = $_POST['violatorContactNo'];
	$violatorDept = $_POST['ViolatorDept'];
	$violatorCompany = $_POST['violatorCompany'];
	$violatorIc = $_POST['ViolatorIc'];
	$violatorDatetime = $_POST['violatorDatetime'];
	$editdate = date('Y-m-d H:i:s');
	$staffid = $_SESSION['user']['staffId'];

	$sql = "UPDATE ucuaform SET 
					LocationId=?, 
					OffenceCode=?, 
					ViolatorName=?, 
					violatorContactNo=?, 
					ViolatorDept=?, 
					violatorCompany=?, 
					ViolatorIc=?, 
					violatorDatetime=?,
					editby=?,
					editdate=?
				WHERE Id=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param(
		"ssssssssssi",
		$locationId,
		$offencecode,
		$violatorName,
		$violatorContactNo,
		$violatorDept,
		$violatorCompany,
		$violatorIc,
		$violatorDatetime,
		$staffid,
		$editdate,
		$last_id
	);

	if ($stmt->execute()) {
		//MULTI IMAGES UPLOAD 21/2/2023
		handleFileUploads($last_id, $target_dir, $allowTypes, 'files', 'Photo Evidence', $staffid);
		handleFileUploads($last_id, $target_dir, $allowTypes, 'files2', 'Violator Evidence', $staffid);

		$history = $_SESSION['user']['staffId'] . ' update report ID ' . $last_id . ' on ' . date('Y-m-d h:i:s');
		history($last_id, $_SESSION['user']['staffId'], $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'SUCCESS', date('Y-m-d h:i:s'));

		echo '<meta http-equiv="refresh" content="0; URL=default.php?page=ua_form_edit&ucuaid=' . base64_encode($_POST['Id']) . '" />';
		exit();
	}
}

if (isset($_POST['updateDraft'])) {
	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();
	$last_id = $_POST['Id'];
	$offencecode = implode(", ", $_POST['offenceCode']); //array
	$staffid = $_SESSION['user']['staffId'];
	$editdate = date('Y-m-d h:i:s');
	$locationId = $_POST['LocationId'];
	$violatorName = $_POST['ViolatorName'];
	$violatorContactNo = $_POST['violatorContactNo'];
	$violatorDept = $_POST['ViolatorDept'];
	$violatorCompany = $_POST['violatorCompany'];
	$violatorIc = $_POST['ViolatorIc'];
	$violatorDatetime = $_POST['violatorDatetime'];
	$editby = $_SESSION['user']['staffId'];
	$editdate = date('Y-m-d h:i:s');

	$sql = "UPDATE ucuadraft SET 
					LocationId=?, 
					OffenceCode=?, 
					ViolatorName=?, 
					violatorContactNo=?, 
					ViolatorDept=?, 
					violatorCompany=?, 
					ViolatorIc=?, 
					violatorDatetime=?,
					editby=?,
					editdate=?
				WHERE Id=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param(
		"ssssssssssi",
		$locationId,
		$offencecode,
		$violatorName,
		$violatorContactNo,
		$violatorDept,
		$violatorCompany,
		$violatorIc,
		$violatorDatetime,
		$staffid,
		$editdate,
		$last_id
	);

	if ($stmt->execute()) {
		//MULTI IMAGES UPLOAD 21/2/2023
		handleFileUploads($last_id, $target_dir, $allowTypes, 'files', 'Photo Evidence', $staffid);
		handleFileUploads($last_id, $target_dir, $allowTypes, 'files2', 'Violator Evidence', $staffid);

		echo '<meta http-equiv="refresh" content="0; URL=default.php?page=ua_form_draft&ucuaid=' . base64_encode($_POST['Id']) . '" />';
		exit();
	}
}
