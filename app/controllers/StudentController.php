<?php

require_once 'database.php';

class StudentController {
    private $pdo;

    public function __construct(){
        $this->pdo = getDatabaseConnection();
    }

    public function createStudent($data){
        $validationErrors = $this->validateStudentData($data);
        if(!empty($validationErrors)){
            return ['success' => false, 'errors' => $validationErrors];
        }
        
        try{
            $sql = "INSERT INTO students (name, age, email, course) VALUES (:name, :age, :email, :course)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':age' => $data['age'],
                ':email' => $data['email'],
                ':course' => $data['course']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            echo 'Error creating student: ' . $ex->getMessage();
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllStudents(){
        try{
            $sql = "SELECT * FROM students";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo 'Error fetching students: ' . $ex->getMessage();
            return [];
        }
    }

    public function getStudentById($id){
        try{
            $sql = "SELECT * FROM students WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo 'Error fetching student: ' . $ex->getMessage();
            return null;
        }
    }

    public function updateStudent($id, $data){
        $validationErrors = $this->validateStudentData($data);
        if(!empty($validationErrors)){
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE students SET name = :name, age = :age, email = :email, course = :course WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':age' => $data['age'],
                ':email' => $data['email'],
                ':course' => $data['course'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch(PDOException $ex){
            echo 'Error updating student: ' . $ex->getMessage();
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteStudent($id){
        try {
            $sql = "DELETE FROM students WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            echo 'Error deleting student: ' . $ex->getMessage();
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateStudentData($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }
        if (empty($data['age']) || !is_numeric($data['age'])) {
            $errors[] = 'Age must be a number.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }
        if (empty($data['course'])) {
            $errors[] = 'Course is required.';
        }

        return $errors;
    }
}
