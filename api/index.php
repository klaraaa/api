<?php
header('Content-Type: text/json');

function __autoload($class){
	require_once($class.".class.php");
}

$url_parts = getUrlParts($_GET);
if (count($url_parts) > 0) {	
	$method = $_SERVER['REQUEST_METHOD']; # innehåller ex: POST, PUT, DELETE, GET
	// Vi antar att det alltid är GET som gäller.
	$resource = array_shift($url_parts);
	$allowed_resources = ['students', 'courses', 'grades'];
	if(in_array($resource, $allowed_resources)){
		require_once($resource.".class.php");
		$obj = new $resource();
		$data = getHTTPData($method);
		$output = $obj->$method($url_parts, $data);
		echo json_encode($output);	
	}else{
		header("HTTP/1.1 404 Not Found");
		echo "API error 404 from index";
	}
} else {
	$obj = (object) array('error'=>'Not found');
	echo json_encode($obj);
}

function getHTTPData($method){
	switch($method){
		case 'GET':
			$data = $_GET;
			break;
		case 'POST':
			$data = $_POST;
			break;
		case 'PUT':
		case 'DELETE':
			parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $data); //hämtar data från input-metoden i http, kollar "manuellt" eftersom php inte vet om input automatiskt.
			break;
	}
	return $data;
}

function getUrlParts($get){
	$get_params = array_keys($get);
	if(count($get_params)>0) {
		$url = $get_params[0];
		$url_parts = explode("/",$url);
		foreach($url_parts as $k => $v){
			if($v) $array[] = $v; # om det finns ett innehåll på platsen vi är på just nu, spara det i $array
		}
		$url_parts = $array;
		return $url_parts; 
	} else {
		return array();
	}
}