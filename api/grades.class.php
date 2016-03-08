<?php

class grades{

	public $gradesID, $student, $course, $grade, $studentID, $courseID;

	function get($url_parts, $parent_resource = NULL){
		//Connect to database
		$db = DB::getInstance();

		//Look for parent resource
		if(isset($parent_resource['studentID'])){
			$this->studentID = $parent_resource['studentID'];
			$this->courseID = $parent_resource['courseID'];
			return $this->getJSONWithParent();
		} else {
			return $this->getJSONWithoutParent($url_parts);
		}
	}
	
	private function getJSONWithParent() {
		$db = DB::getInstance();
		$query = "
			SELECT *
			FROM grades
			WHERE grades.student_id = $this->studentID
			AND grades.course_id = $this->courseID
		";

		$result = $db->query($query);
		$item = $result->fetch_assoc();
		
		return json_encode($item);
	}

	private function getJSONWithoutParent($url_parts) {
		if(count($url_parts) == 0) {
			return $this->getGrades();
		}
		elseif(count($url_parts)==1) {
			return $this->getSingleGrade($url_parts);
		}
	}

	private function getGrades() {
		$query = "
			SELECT *
			FROM grades
		";

		$result = $db->query($query);
		while($item = $result->fetch_assoc()){
			$grades[] = $item;
		}
		
		return json_encode($grades);
	}


	private function getSingleGrade($url_parts) {
		$id = array_shift($url_parts);
		$cleanId = $db->real_escape_string($id); 
		$this->id = $cleanId;
		$query = "
			SELECT *
			FROM grades
			WHERE id = $this->id
		";

		$result = $db->query($query);
		$item = $result->fetch_assoc();
		
		return json_encode($item);
	}
	
//POST fungerar
	public function post($url_parts, $data){
		$db = DB::getInstance();

		$student = $db->real_escape_string($data['student_id']);
		$course = $db->real_escape_string($data['course_id']);
		$grade = $db->real_escape_string($data['grade']);


		$query = "
			INSERT INTO grades (student_id, course_id, grade) 
			VALUES ('$student','$course', '$grade')";

		
		if($db->query($query)){
			$respons = ['status' => 'ok'];
		}else{
			$respons = ['status' => 'fail post'];
		}
		echo json_encode($respons);
	}

//PUT fungerar
	public function put($url_parts, $data){
		$db = DB::getInstance();

		$fields = ['student_id', 'course_id', 'grade'];

		foreach($fields as $field){
			if(isset($data[$field])){
				$sql_parts[] = $field . " = "."'".$db->real_escape_string($data[$field])."'";
			}
		} 	

		$update_fields = implode(',',$sql_parts);	
		$id = $db->real_escape_string($data['id']); 

		$query = "
			UPDATE grades 
			SET $update_fields
			WHERE id = $id
			";

		if($db->query($query)){
			$respons = ['status' => 'ok'];
		}else{
			$respons = ['status' => 'fail'];
		}

		echo json_encode($respons);
	}

//delete fungerar
	public function delete($url_parts, $data){
		
		$db = DB::getInstance();

		$id = $db->real_escape_string($data['id']);
		$query = "
			DELETE FROM grades 
			WHERE id = $id";

		if($db->query($query)){
			$respons = ['status' => 'ok'];
		}else{
			$respons = ['status' => 'fail'];
		}

		echo json_encode($respons);
	}

}