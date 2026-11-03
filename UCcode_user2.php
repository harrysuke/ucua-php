<?php
include('testFunctions.php') ;

$direct = "location: ../mucua/uc.php";
$_SESSION['error'] = '';
$_SESSION['success'] = '';

function history($ucuaid, $staffid, $accessLevel, $action, $activity, $status, $datetimestamp){
		global $db;
		$sql = "INSERT INTO  history (ucuaid, staffid, accessLevel, action, activity, status, datetimestamp) VALUES (".$ucuaid.", '".$staffid."', ".$accessLevel.", '".$action."', '".$activity."', '".$status."', '".$datetimestamp."')";
		if(mysqli_query($db, $sql)){
			$_SESSION['success'] .= " History created successfully";
		}else{
			$_SESSION['error'] .= " Error ".mysqli_error($db);
		}
	}
	
	function ucuapix($ucuaid, $pixname, $uploadedby, $uploadeddate){
		global $db;
		$sql = "INSERT INTO ucuapix (ucuaid, pixname, uploadedby, uploadeddate) VALUES ('$ucuaid', '$pixname', '$uploadedby', '$uploadeddate')";
		if(mysqli_query($db, $sql)){
			$_SESSION['success'] .= " Photos uploaded and log successfully";
		}else{
			$_SESSION['error'] .= " Error ".mysqli_error($db);
		}
	}
	
	function insertUcuaphoto($ucuaform_Id, $evidenceImage, $createby, $createdate, $type){
		global $db;
		$sql = "INSERT INTO ucuaphoto (ucuaform_Id, evidenceImage, createby, createdate, type) VALUES (".$ucuaform_Id.", '".$evidenceImage."', '".$createby."', '".$createdate."', '".$type."') ";
		if(mysqli_query($db, $sql)){
			$_SESSION['success'] .= " Photos uploaded successfully.";
		}else{
			$_SESSION['error'] .= " Error ".mysqli_error($db);
		}
		//echo $sql;
	}
	
	if(isset($_POST['submit_btn'])){
		$location = $_POST['location'];
		$details = $_POST['details'];
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$submitdate= date("Y-m-d h:i:s");
		$staffid = $_SESSION['user']['staffId'];
		$fullurl = $_POST['fullurl'];
		
		$target_dir = "uploads/";
		$allowTypes = array('jpg','png','jpeg','gif');
		//$filename = $_FILES["fileToUpload"]["name"];
		//$file_parts = explode(".",$filename);
		//$file_end_part = end($file_parts);
		//$filename2 = date('YmdHis') . "." . $file_end_part;
		//$target_file = $target_dir . basename($filename2);
		//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$uploadOk = 1;
		
		
		

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$_SESSION['error'] .= 'Sorry, your file was not uploaded.';
			//echo '<script>window.location.href = history.back();</script>';
			// if everything is ok, try to upload file
		} else {
				$sql = "INSERT INTO ucuaform (LocationId, conditionDetails, staffId, evidenceImage, form_type, submitdate, status1, status2, status3, status4, ImgEvidence, status5, status6, status7, observerName,
			observerDepartment,
			observerEmail,
			observerDatetime) VALUES ('".$location."','".$details."', '".$staffid."','','Unsafe Condition', '".$submitdate."', 'Pending', 'Pending', 'Pending', 'Pending', 'Pending', 'Pending', 'Pending', 'Pending', '".$_SESSION['user']['Name']."',
				'".$_SESSION['user']['Department']."',
				'".$_SESSION['user']['Email']."',
				'".date('Y-m-d h:i:s')."')";
		
				if(mysqli_query($db, $sql)){
					$last_id = mysqli_insert_id($db);
					
					//MULTI IMAGES UPLOAD 21/2/2023
					$x = 0;
					$fileNames = array_filter($_FILES['files']['name']);
					if(!empty($fileNames)){ 
						foreach($_FILES['files']['name'] as $key=>$val){ 
							$fileName4 = basename($_FILES['files']['name'][$key]);
							//$file_parts3 = explode(".",$filename4);
							//$file_end_part3 = end($file_parts3);
							//$filename5 = $x.date('YmdHisv') . "." . $file_end_part3;
							$targetFilePath = $target_dir . $fileName4; 
							$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
							if(in_array($fileType, $allowTypes)){ 
								if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
									insertUcuaphoto($last_id, $targetFilePath, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Photo Evidence');
								}
							}
						$x++;
						}
					}
					
					
					$_SESSION['success'] = 'Success';
					$history = $staffid.' created report ID '.$last_id.' and uploaded '.$targetFilePath.' on '.$submitdate;
					history($last_id, $staffid, $_SESSION['user']['AccessLevel'], 'UPLOAD', $history, 'SUCCESS', $submitdate);
					ucuapix($last_id, $targetFilePath, $staffid, $submitdate);
					//if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
						//insertUcuaphoto($last_id, $target_file, $_SESSION['user']['staffId'], date("Y-m-d h:i:s"), 'Photo Evidence');
					//}
					
					echo '<meta http-equiv="refresh" content="0; URL='.base64_decode($fullurl).'" />';
					exit;
				} else {
					$_SESSION['error'] .= mysqli_error($db);
					echo '<meta http-equiv="refresh" content="0; URL='.base64_decode($fullurl).'" />';
					exit;
				}
				
				//$_SESSION['error'] .= 'Upload failed';
			
		}
		
		
	}
?>