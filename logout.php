<?php
	include('testFunctions.php');
	$ip = $_SERVER['REMOTE_ADDR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
	//Log user logout
	try{
		funLoginActivity($_SESSION['user']['staffId'], $_SESSION['user']['AccessLevel'], 'LOGOUT', $ip, $user_agent);
	}catch(Exception $e){
		die($e->getMessage());
	}

	unset($_SESSION['username'], $_SESSION['user']['AccessLevel']);
	session_destroy();
	header('Location: login');
	exit;
?>