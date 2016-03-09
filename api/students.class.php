<?php

class students{

	public $studentID = NULL, $parentID = NULL, $grandParent_resource = array();


	function get($url_parts, $parent_resource = NULL){
		//Connect to database
		$db = DB::getInstance();

		//Look for parent resource and grab it's ID
		if(isset($parent_resource['courseID'])) {
			$this->grandParent_resource = $parent_resource['courseID'];
			$this->parentID = $parent_resource['courseID'];
			return $this->getJSONWithParent($url_parts);
		} else {
			return $this->getJSONWithoutParent($url_parts);
		}
	}


	private function getJSONWithParent($url_parts) {
		if(count($url_parts) == 0) {
			return $this->getAllStudentsInCourse();
		}
		elseif(count($url_parts) == 1) {
			return $this->getStudentInCourse($url_parts);
		}
		elseif(count($url_parts) == 2) {
			return $this->redirect($url_parts);
		}
	}


	private function getJSONWithoutParent($url_parts) {
		if(count($url_parts) == 0) {
			return $this->getStudents();
		}
		elseif(count($url_parts) == 1) {
			return $this->getSingleStudent($url_parts);
		}
		elseif(count($url_parts) == 2) {
			return $this->redirect($url_parts);
		}elseif(count($url_parts) == 3) {
			return $this->redirect($url_parts);
		}
		elseif(count($url_parts) == 4) {
			return $this->redirect($url_parts);
		}
	}


	private function getAllStudentsInCourse() {
		$db = DB::getInstance();
		$query = "
			SELECT students.id, students.firstname, students.lastname
			FROM grades, students
			WHERE students.id = grades.student_id
			AND grades.course_id = $this->parentID
		";

		$result = $db->query($query);
		while($item = $result->fetch_assoc()){
			$students[] = $item;
		}
		
		return $students;
	}


	private function getStudentInCourse($url_parts) {
		$db = DB::getInstance();
		$studentID = array_shift($url_parts);
		$cleanStudentID = $db->real_escape_string($studentID);
		$this->studentID = $cleanStudentID;
		$query = "
			SELECT students.id, students.firstname, students.lastname
			FROM students, grades
			WHERE grades.student_id = students.id
			AND grades.course_id = $this->parentID
			AND students.id = $this->studentID
		";

		$result = $db->query($query);
		$item = $result->fetch_assoc();
		
		return $item;
	}


	private function redirect($url_parts) {
		$db = DB::getInstance();
		
		$studentID = array_shift($url_parts);
		$cleanStudentID = $db->real_escape_string($studentID);
		$this->studentID = $cleanStudentID;		
		
		//send url_parts info to new resource with sender resource and resource data that is needed in next step
		$nextResource = array_shift($url_parts);
		$allowed_resources = ['courses', 'grades'];
		
		if(in_array($nextResource, $allowed_resources)){
			$resourceData = [
			'studentID' => $this->studentID,
			'courseID' => $this->grandParent_resource
			];

			//Skicka info om den nuvarande objektet
			require_once($nextResource.".class.php");
			$obj = new $nextResource();
			$output = $obj->get($url_parts, $resourceData);
			return $output;
		}else{
			header("HTTP/1.1 404 Not Found");
			echo "API error 404 from resource";
		}
	}


	private function getStudents() {
		$db = DB::getInstance();
		$query = "
			SELECT students.id, students.firstname, students.lastname
			FROM students
		";
		var_dump($query);
		$result = $db->query($query);
		while($item = $result->fetch_assoc()){
			$students[] = $item;
		}
		
		return $students;
	}


	private function getSingleStudent($url_parts) {
		$db = DB::getInstance();
		$studentID = array_shift($url_parts);
		$cleanStudentID = $db->real_escape_string($studentID);
		$this->studentID = $cleanStudentID;
		$query = "
			SELECT students.id, students.firstname, students.lastname
			FROM students
			WHERE students.id = $this->studentID
		";
		var_dump($query);
		$result = $db->query($query);
		$item = $result->fetch_assoc();
		
		return $item;
	}

}//Close class
