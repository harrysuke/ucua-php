<?php
try{
	$page = isset($_GET['page']) ? $_GET['page'] : 'resources';
	$p = $page.'.php';
	if(file_exists($p)){
		include $page.'.php';
	}else{
		include 'resources.php';
	}
}catch(Exception $e){
	die($e->getMessage());
}
?>