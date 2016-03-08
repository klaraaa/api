<?php

class grades{

	public $gradesID, $student, $course, $grade;

	public function __construct($data = NULL){
		if($data){			
			$db = DB::getInstance();
			$id = $db->real_escape_string($data); 
			$this->gradesID = $id;
			$query = "
				SELECT *
				FROM grades
				WHERE id=$this->gradesID";
			$result = $db->query($query);
			$grade = $result->fetch_assoc();
			
			$this->student = $grade['student_id'];
			$this->course = $grade['course_id'];
			$this->grade = $grade['grade'];
		}
	}

		function get($url_parts, $data = NULL, $parent_resource = array()){
		
		$db = DB::getInstance();
		if (count($url_parts) == 1) {
			$id = array_shift($url_parts);
			$cleanId = $db->real_escape_string($id); 
			$this->id = $cleanId;
			$query = "
				SELECT *
				FROM grades
				WHERE id = $this->id
			";
		}else{
			$query = "
				SELECT *
				FROM grades
		";
		}

		$result = $db->query($query);
		while($item = $result->fetch_assoc()){
			$grades[] = $item;
		}
		
		return json_encode($grades);
	}

	public function post($url_parts, $data){
		$db = DB::getInstance();

		$student = $db->real_escape_string($data['student']);
		$course = $db->real_escape_string($data['course']);
		$grade = $db->real_escape_string($data['grade']);


		$query = "
			INSERT INTO grades (student, course, grade) 
			VALUES ('$student','$course', '$grade')";

		
		if($db->query($query)){
			$respons = ['status' => 'ok'];
		}else{
			$respons = ['status' => 'fail'];
		}
		echo json_encode($respons);

		// $grades = new grades($db->insert_id);
	}

	public function put($url_parts, $data){
		$db = DB::getInstance();

		$fields = ['student', 'course', 'grade'];

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