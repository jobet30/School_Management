<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require_once 'database.php';

class CourseController {
    private $pdo;
    
    public function __construct(){
        $this->pdo = getDatabaseConnection();
    }
    
    public function createCourse($data){
        $validationErrors = $this->validateCourseData($data);
        if(!empty($validationErrors)){
            return['success' => false, 'errors' => $validationErrors];
        }
        
        try{
            $sql = "INSERT INTO courses (name, description, created_at, updated_at) 
                    VALUES (:name, :description, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (Exception $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }
    
    public function getAllCourses() {
        try {
            $sql = "SELECT * FROM courses";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }
    
    public function getCourseById($id) {
        try {
            $sql = "SELECT * FROM courses WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateCourse($id, $data) {
        $validationErrors = $this->validateCourseData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE courses SET name = :name, description = :description, 
                    updated_at = NOW() WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteCourse($id) {
        try {
            $sql = "DELETE FROM courses WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateCourseData($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Course name is required.';
        }
        if (empty($data['description'])) {
            $errors[] = 'Description is required.';
        }

        return $errors;
    }
}
