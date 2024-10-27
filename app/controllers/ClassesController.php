<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class ClassesController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createClass($data) {
        $validationErrors = $this->validateClassData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO classes (course_id, subject_id, teacher_id, class_code, schedule, created_at, updated_at) 
                    VALUES (:course_id, :subject_id, :teacher_id, :class_code, :schedule, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':course_id' => $data['course_id'],
                ':subject_id' => $data['subject_id'],
                ':teacher_id' => $data['teacher_id'],
                ':class_code' => $data['class_code'],
                ':schedule' => $data['schedule']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllClasses() {
        try {
            $sql = "SELECT * FROM classes";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getClassById($id) {
        try {
            $sql = "SELECT * FROM classes WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateClass($id, $data) {
        $validationErrors = $this->validateClassData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE classes SET course_id = :course_id, subject_id = :subject_id, 
                    teacher_id = :teacher_id, class_code = :class_code, schedule = :schedule, 
                    updated_at = NOW() WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':course_id' => $data['course_id'],
                ':subject_id' => $data['subject_id'],
                ':teacher_id' => $data['teacher_id'],
                ':class_code' => $data['class_code'],
                ':schedule' => $data['schedule'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteClass($id) {
        try {
            $sql = "DELETE FROM classes WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateClassData($data) {
        $errors = [];

        if (empty($data['course_id'])) {
            $errors[] = 'Course ID is required.';
        }
        if (empty($data['subject_id'])) {
            $errors[] = 'Subject ID is required.';
        }
        if (empty($data['teacher_id'])) {
            $errors[] = 'Teacher ID is required.';
        }
        if (empty($data['class_code'])) {
            $errors[] = 'Class code is required.';
        }
        if (empty($data['schedule'])) {
            $errors[] = 'Schedule is required.';
        }

        return $errors;
    }
}