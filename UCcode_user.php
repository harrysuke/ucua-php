<?php
include('testFunctions.php');

$direct = "location: uc_form";
$_SESSION['statusType'] = '';
$_SESSION['status'] = '';

function history($ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp)
{
	global $db;
	$sql = "INSERT INTO history (ucuaid, staffid, accessLevel, action, activity, status, datetimestamp) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

	$stmt = $db->prepare($sql);
	$stmt->bind_param("issssss", $ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp);

	// Execute returns true on success, false on failure
	if ($stmt->execute()) {
		$_SESSION['statusType'] = "success";
		$_SESSION['status'] = "New record added successfully";
	} else {
		$_SESSION['statusType'] = "error";
		$_SESSION['status'] = "Error: " . $stmt->error;
	}

	$stmt->close();
}
function showName($n)
{
	global $db;
	$sql = "SELECT Name, Email FROM user WHERE staffId = ?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $n);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['Name'];
	$stmt->close();
}

function ucuapix($ucuaid, $pixname, $uploadedby, $uploadeddate)
{
	global $db;
	$sql = "INSERT INTO ucuapix (ucuaid, pixname, uploadedby, uploadeddate) VALUES (?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("isss", $ucuaid, $pixname, $uploadedby, $uploadeddate);
	if ($stmt->execute()) {
		$_SESSION['statusType'] = "success";
		$_SESSION['status'] = "New record added successfully";
	} else {
		$_SESSION['statusType'] = "error";
		$_SESSION['status'] = "Error: " . $stmt->error;
	}
	$stmt->close();
}

function insertUcuaphoto($ucuaform_Id, $evidenceImage, $createby, $createdate)
{
	global $db;
	$type = 'Photo Evidence';
	$sql = "INSERT INTO ucuaphoto (ucuaform_Id, evidenceImage, createby, createdate, type) VALUES (?, ?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("issss", $ucuaform_Id, $evidenceImage, $createby, $createdate, $type);
	if ($stmt->execute()) {
		$_SESSION['statusType'] = "success";
		$_SESSION['status'] .= " Photos uploaded successfully.";
	} else {
		$_SESSION['statusType'] = "error";
		$_SESSION['status'] .= " Error " . $stmt->error;
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

if (isset($_POST['submit_btn'])) {
	$location = $_POST['location'];
	$details = str_replace("'", "", $_POST['details']);
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$staffid = $_SESSION['user']['staffId'];

	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();

	$form_type = 'Unsafe Condition';
	$img_evidence = '';
	$status = 'Pending PSSD HSE Officer';
	$observerName = $_SESSION['user']['Name'];
	$observerDepartment = $_SESSION['user']['Department'];
	$observerEmail = $_SESSION['user']['Email'];
	$observerDatetime = date('Y-m-d h:i:s');

	$sql = "INSERT INTO ucuaform (
		LocationId, 
		conditionDetails, 
		staffId, 
		form_type, 
		submitdate, 
		status, 
		createby, 
		createdate,
		observerName,
		observerDepartment,
		observerEmail,
		observerDatetime
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param(
		"ssssssssssss",
		$location,
		$details,
		$staffid,
		$form_type,
		$submitdate,
		$status,
		$staffid,
		$submitdate,
		$observerName,
		$observerDepartment,
		$observerEmail,
		$observerDatetime
	);
	if ($stmt->execute()) {
		$last_id = $stmt->insert_id;

		//MULTI IMAGES UPLOAD 21/2/2023
		$x = 0;
		$fileNames = array_filter($_FILES['files']['name']);
		if (!empty($fileNames)) {
			foreach ($_FILES['files']['name'] as $key => $val) {
				// Get the file name and its extension
				$fileName4 = basename($_FILES['files']['name'][$key]);
				$targetFilePath = $target_dir . date("YmdHis") . "_" . $fileName4;
				$fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)); // Convert to lowercase for extension comparison

				// Check if the file type is allowed
				if (in_array($fileType, $allowTypes)) {
					// Check if the file was successfully moved from the temporary upload location
					if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
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
		} //if

		$history = $staffid . ' created report ID ' . $last_id . ' and uploaded ' . $targetFilePath . ' on ' . $submitdate;
		history($last_id, $staffid, $_SESSION['user']['AccessLevel'], 'UPLOAD', $history, 'SUCCESS', $submitdate);
		ucuapix($last_id, $targetFilePath, $staffid, $submitdate);

		$_SESSION['statusType'] = "success";
		$_SESSION['status'] .= 'Recorded successfully updated';
		echo '<meta http-equiv="refresh" content="0; URL=default.php?page=newuiuc_form&id=' . base64_encode($last_id) . '" />';
		exit();
	} else {
		$_SESSION['statusType'] = "error";
		$_SESSION['status'] .= $stmt->error;
		echo '<meta http-equiv="refresh" content="0; URL=default.php?page=newuiuc_form&id=' . base64_encode($last_id) . '" />';
		exit();
	}
	$stmt->close();
}

if (isset($_POST['draft_btn'])) {
	$location = $_POST['location'];
	$details = $_POST['details'];
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$submitdate = date("Y-m-d h:i:s");
	$staffid = $_SESSION['user']['staffId'];

	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();

	$form_type = 'Unsafe Condition';
	$img_evidence = '';
	$status = 'Pending PSSD HSE Officer';
	$observerName = $_SESSION['user']['Name'];
	$observerDepartment = $_SESSION['user']['Department'];
	$observerEmail = $_SESSION['user']['Email'];
	$observerDatetime = date('Y-m-d h:i:s');

	$sql = "INSERT INTO ucuadraft (
		LocationId, 
		conditionDetails, 
		staffId, 
		form_type, 
		submitdate, 
		ImgEvidence, 
		status, 
		createby, 
		createdate,
		observerName,
		observerDepartment,
		observerEmail,
		observerDatetime
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->bind_param(
		"sssssssssssss",
		$location,
		$details,
		$staffid,
		$form_type,
		$submitdate,
		$img_evidence,
		$status,
		$staffid,
		$submitdate,
		$observerName,
		$observerDepartment,
		$observerEmail,
		$observerDatetime
	);

	if ($stmt->execute()) {
		$last_id = $stmt->insert_id;
		//MULTI IMAGES UPLOAD 21/2/2023
		$x = 0;
		$fileNames = array_filter($_FILES['files']['name']);
		if (!empty($fileNames)) {
			foreach ($_FILES['files']['name'] as $key => $val) {
				$fileName4 = basename($_FILES['files']['name'][$key]);
				//$file_parts3 = explode(".",$filename4);
				//$file_end_part3 = end($file_parts3);
				//$filename5 = $x.date('YmdHisv') . "." . $file_end_part3;
				$targetFilePath = $target_dir . $fileName4;
				$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
				if (in_array($fileType, $allowTypes)) {
					if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
						insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
					}
				}
				$x++;
			}
		}

		$history = $staffid . ' created report ID ' . $last_id . ' and uploaded ' . $targetFilePath . ' on ' . $submitdate;
		history($last_id, $staffid, $_SESSION['user']['AccessLevel'], 'UPLOAD', $history, 'SUCCESS', $submitdate);
		ucuapix($last_id, $targetFilePath, $staffid, $submitdate);

		$_SESSION['statusType'] = "success";
		$_SESSION['status'] .= 'Recorded successfully updated';
		echo '<meta http-equiv="refresh" content="0; URL=default.php?page=uc_reportlist-individual_draft" />';
		exit();
	} else {
		$_SESSION['statusType'] = "error";
		$_SESSION['status'] .= $stmt->error;
		echo '<meta http-equiv="refresh" content="0; URL=default.php?page=uc_reportlist-individual_draft" />';
		exit();
	}
	$stmt->close();
}

if (isset($_POST['update'])) {
	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();
	$last_id = $_POST['Id'];
	$editby = $_SESSION['user']['staffId'];
	$editdate = date('Y-m-d h:i:s');
	$location = $_POST['LocationId'];
	$conditionDetails = $_POST['conditionDetails'];

	$sql = "UPDATE ucuaform SET LocationId=?, conditionDetails=?, editby=?, editdate=? WHERE Id=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("ssssi", $location, $conditionDetails, $editby, $editdate, $last_id);
	if ($stmt->execute()) {
		//MULTI IMAGES UPLOAD 21/2/2023
		$x = 0;
		$fileNames = array_filter($_FILES['files']['name']);
		if (!empty($fileNames)) {
			foreach ($_FILES['files']['name'] as $key => $val) {
				$fileName4 = basename($_FILES['files']['name'][$key]);
				//$file_parts3 = explode(".",$filename4);
				//$file_end_part3 = end($file_parts3);
				//$filename5 = $x.date('YmdHisv') . "." . $file_end_part3;
				$targetFilePath = $target_dir . $fileName4;
				$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
				if (in_array($fileType, $allowTypes)) {
					if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
						insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
					}
				}
				$x++;
			}
		}
	}
	$history = $_SESSION['user']['Name'] . ' update report ID ' . $_POST['Id'] . ' on ' . date('Y-m-d h:i:s');
	history($_POST['Id'], $_SESSION['user']['staffId'], $_SESSION['user']['AccessLevel'], 'UPDATE', $history, 'SUCCESS', date('Y-m-d h:i:s'));
	$stmt->close();
	echo '<meta http-equiv="refresh" content="0; URL=default.php?page=uc_form_edit&ucuaid=' . base64_encode($_POST['Id']) . '" />';
	exit();
}

if (isset($_POST['updateDraft'])) {
	$target_dir = "uploads/";
	//$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$allowTypes = getAllowedFileExtensions();
	$last_id = $_POST['Id'];
	$editby = $_SESSION['user']['staffId'];
	$editdate = date('Y-m-d h:i:s');
	$location = $_POST['LocationId'];
	$conditionDetails = $_POST['conditionDetails'];

	$sql = "UPDATE ucuadraft SET LocationId=?, conditionDetails=?, editby=?, editdate=? WHERE Id=?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("ssssi", $location, $conditionDetails, $editby, $editdate, $last_id);
	if ($stmt->execute()) {
		//MULTI IMAGES UPLOAD 21/2/2023
		$x = 0;
		$fileNames = array_filter($_FILES['files']['name']);
		if (!empty($fileNames)) {
			foreach ($_FILES['files']['name'] as $key => $val) {
				$fileName4 = basename($_FILES['files']['name'][$key]);
				//$file_parts3 = explode(".",$filename4);
				//$file_end_part3 = end($file_parts3);
				//$filename5 = $x.date('YmdHisv') . "." . $file_end_part3;
				$targetFilePath = $target_dir . $fileName4;
				$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
				if (in_array($fileType, $allowTypes)) {
					if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
						insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"));
					}
				}
				$x++;
			}
		}
	}
	$stmt->close();
	echo '<meta http-equiv="refresh" content="0; URL=default.php?page=uc_form_draft&ucuaid=' . base64_encode($_POST['Id']) . '" />';
	exit();
}
