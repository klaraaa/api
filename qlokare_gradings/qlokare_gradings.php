<?php
$outputHTML = "";

if (isset($_POST['gradeStudent'])) {
	echo $outputHTML = postGrade();
}

if (isset($_POST['updateGrade'])) {
	echo $outputHTML = putGrade();
}

if (isset($_POST['deleteGrade'])) {
	echo $outputHTML = deleteGrade();
}

if (isset($_GET['getGrade'])) {
	echo $outputHTML = getGrade();
}

function getGrade(){
	$data = $_GET;
	// kollar databasen genom fråga till API:
	$json = call("http://192.168.33.14/api/?/grades/", "get", $data);
	// $outputHTML = getHTML($json);
	// return $outputHTML;
}

function postGrade(){
	$data = $_POST;
	// kollar databasen genom fråga till API:
	echo $json = call("http://192.168.33.14/api/?/grades/", "post", $data);
}

function putGrade(){
	$data = $_POST;
	// kollar databasen genom fråga till API:
	if(empty($data)) {
		$json = call("http://192.168.33.14/api/?/grades/", "get", $data);
	}else{
		echo $json = call("http://192.168.33.14/api/?/grades/", "put", $data);
	}
}

function deleteGrade(){
	$data = $_POST;
	// kollar databasen genom fråga till API:
	if(empty($data)) {
		$json = call("http://192.168.33.14/api/?/grades/", "get", $data);
	}else{
		echo $json = call("http://192.168.33.14/api/?/grades/", "delete", $data);
	}
}

function getHTML($json) {
	$html = "<table>";
	$arr = json_decode($json);
	var_dump($arr);
	foreach($arr as $row) {
		$html .= "<tr>";
		foreach($row as $col) {
			$html .= "<td>";
			$html .= $col;
			$html .= "</td>";
		}
		$html .= "</tr>";
	}
	$html .= "</table>";
	return $html;
}

function call($url, $method = "get", $data = []){
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

//ska det stå såhär om det är en DELETE method
switch ($method) {
	case 'post':
		curl_setopt($ch, CURLOPT_POST, 1);
		//har ska man ändra method baserat på vilken http-metod som angivits.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		break;
	case 'put':
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // note the PUT here

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	break;
	case 'delete':
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		break;
}
$output = curl_exec($ch); 
curl_close($ch);

return $output;
}

?><!DOCTYPE html>
<html>
<head>
	<title>Sök-formulär</title>
</head>
<body>
<div>
	<form method="get" action="">
		<p>Get:</p>
		<label for="grade">Grade:</label>
		<input type="text" name="grade">
		<input type="hidden" name="getGrade"></input>
		<input type="submit">
	</form>
	<form method="post" action="">
	<p>Grade Student in Course:</p>
		<label for="student">Student:</label>
		<input type="text" name="student">
		<label for="course">Course:</label>
		<input type="text" name="course">
		<label for="grade">Grade:</label>
		<input type="text" name="grade">
		<input type="hidden" name="gradeStudent"></input>
		<input type="submit">
	</form>
	<form method="post" action="">
		<p>Update grade for student in course:</p>
		<label for="student">Student:</label>
		<input type="text" name="student">
		<label for="course">Course:</label>
		<input type="text" name="course">
		<label for="grade">Grade:</label>
		<input type="text" name="grade">
		<input type="hidden" name="updateGrade"></input>
		<input type="submit">
	</form>
		<form method="post" action="">
		<p>Delete: </p>
		<label for="id">ID:</label>
		<input type="text" name="id">
		<input type="hidden" name="deleteGrade"></input>
		<input type="submit">
	</form>
</div>
<div>
	<?php echo $outputHTML; ?>
</div>
</body>
</html>