<?php
include('testFunctions.php');

if(isset($_POST['btnSave'])){
	$sql="INSERT INTO chatbot_db.responses (response_message) VALUES ('".$_POST['response_message']."')";
	mysqli_query($db, $sql);
	$last_id = mysqli_insert_id($db);
	
	$sql2="INSERT INTO chatbot_db.questions (question, response_id) VALUES ('".$_POST['question']."', '".$last_id."')";
	mysqli_query($db, $sql2);
	
	echo 'Record created successfully';
	exit;
}

if(isset($_POST['btnUnanswered'])){
	$sql="INSERT INTO chatbot_db.responses (response_message) VALUES ('".$_POST['response_message']."')";
	mysqli_query($db, $sql);
	$last_id = mysqli_insert_id($db);
	
	$sql2="INSERT INTO chatbot_db.questions (question, response_id) VALUES ('".$_POST['question']."', '".$last_id."')";
	mysqli_query($db, $sql2);
	
	$sql3="DELETE FROM chatbot_db.unanswered WHERE id='".$_POST['idq']."' ";
	mysqli_query($db, $sql3);
	
	echo 'Record created successfully';
	exit;
}

if(isset($_POST['btnUpdate'])){
	$sql="UPDATE chatbot_db.responses SET response_message='".$_POST['response_message']."' WHERE id = '".$_POST['idr']."' ";
	mysqli_query($db, $sql);
	
	$sql2="UPDATE chatbot_db.questions SET question='".$_POST['question']."' WHERE id='".$_POST['idq']."' ";
	mysqli_query($db, $sql2);
	
	echo 'Record updated successfully';
	exit;
}


?>