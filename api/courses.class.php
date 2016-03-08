<?php

class courses{

	public $courseID = NULL, $parentID = NULL, $grandParent_resource = array();


	function get($url_parts, $parent_resource = NULL){
		//Connect to database
		$db = DB::getInstance();

		//Look for parent resource
		if(isset($parent_resource['studentID'])){
			$this->grandParent_resource = $parent_resource['studentID'];
			$this->parentID = $parent_resource['studentID'];
			return $this->getJSONWithParent($url_parts);
		} else {
			return $this->getJSONWithoutParent($url_parts);
		}
	}


	//Look for URL parts and do different db requests depending on count
	private function getJSONWithParent($url_parts) {
		if(count($url_parts) == 0) {
			return $this->getStudentCourses();
		}
		elseif(count($url_parts)==1) {
			return $this->getStudentSingleCourse($url_parts);
		}
		elseif(count($url_parts)==2) {
			return $this->redirect($url_parts);
		}
	}


	//Look for URL parts and do different db requests depending on count
	private function getJSONWithoutParent($url_parts) {
		if(count($url_parts) == 0) {
			return $this->getCourses();
		}
		elseif(count($url_parts)==1) {
			return $this->getSingleCourse($url_parts);
		}
		elseif(count($url_parts)==2) {
			return $this->getAllGradesInCourse($url_parts);
		}
		elseif(count($url_parts) == 3) {
			return $this->redirect($url_parts);
		}
		elseif(count($url_parts) == 4) {
			return $this->redirect($url_parts);	
		}
	}


	private function getStudentCourses() {
		$db = DB::getInstance();
		$query = "
			SELECT courses.id, courses.name
			FROM courses, grades
			WHERE courses.id = grades.course_id
			AND grades.student_id = $this->parentID
		";

		$result = $db->query($query);
		while($item = $result->fetch_assoc()){
			$courses[] = $item;
		}
		
		return json_encode($courses);
	}


	private function getStudentSingleCourse($url_parts) {
		$db = DB::getInstance();

		$courseID = array_shift($url_parts);
		$cleanCourseID = $db->real_escape_string($courseID);
		$this->courseID = $cleanCourseID;
		$query = "
			SELECT courses.id, courses.name
			FROM courses, grades
			WHERE grades.course_id = courses.id
			AND grades.student_id = $this->parentID
			AND courses.id = $this->courseID
		";

		$result = $db->query($query);
		$item = $result->fetch_assoc();
		
		return json_encode($item);
	}


	private function redirect($url_parts) {
		$db = DB::getInstance();
		
		$courseID = array_shift($url_parts);
		$cleanCourseID = $db->real_escape_string($courseID);
		$this->courseID = $cleanCourseID;		
		
		//send url_parts info to new resource with sender resource and resource data that is needed in next step
		$nextResource = array_shift($url_parts);
		$allowed_resources = ['students', 'grades'];
		
		if(in_array($nextResource, $allowed_resources)){
			$resourceData = [
			'courseID' => $this->courseID,
			'studentID' => $this->grandParent_resource
			];

			//skicka array så att this type (classname) namnges så man vet vilken resurs som är avsändare skicka den som parameter till ny resursen
			require_once($nextResource.".class.php");
			$obj = new $nextResource();
			$output = $obj->get($url_parts, $resourceData);
			return $output;
		}else{
			header("HTTP/1.1 404 Not Found");
			echo "API error 404 from resource";
		}
	}


	private function getCourses() {
		$db = DB::getInstance();
		$query = "
			SELECT courses.id, courses.name
			FROM courses
		";

		$result = $db->query($query);
		while($item = $result->fetch_assoc()){
			$courses[] = $item;
		}
		
		return json_encode($courses);
	}


	private function getSingleCourse($url_parts) {
		$db = DB::getInstance();
		$courseID = array_shift($url_parts);
		$cleanCourseID = $db->real_escape_string($courseID);
		$this->courseID = $cleanCourseID;
		$query = "
			SELECT courses.id, courses.name
			FROM courses
			WHERE courses.id = $this->courseID
		";

		$result = $db->query($query);
		$item = $result->fetch_assoc();
		
		return json_encode($item);
	}

}//Close class

