<?php
ob_start("ob_html_compress");

function ob_html_compress($code){
    //return str_replace(array("\n","\r","\t"),'',$code);
	$search = array(
	'/\>[^\S ]+/s',  // remove whitespaces after tags
	'/[^\S ]+\</s',  // remove whitespaces before tags
	'/(\s)+/s'       // remove multiple whitespace sequences
	);
	$replace = array('>','<','\\1');
	$code = preg_replace($search, $replace, $code);
	return $code;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'resources';
include $page.'.php';

ob_end_flush();
?>