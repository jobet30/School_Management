<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class GradesController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function addGrade($data) {
        $validationErrors = $this->validateGradeData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO grades (class_id, student_id, grade) 
                    VALUES (:class_id, :student_id, :grade)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':student_id' => $data['student_id'],
                ':grade' => $data['grade']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getGrades() {
        try {
            $sql = "SELECT * FROM grades";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getGradeById($id) {
        try {
            $sql = "SELECT * FROM grades WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateGrade($id, $data) {
        $validationErrors = $this->validateGradeData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE grades 
                    SET class_id = :class_id, student_id = :student_id, grade = :grade 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':student_id' => $data['student_id'],
                ':grade' => $data['grade'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteGrade($id) {
        try {
            $sql = "DELETE FROM grades WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateGradeData($data) {
        $errors = [];

        if (empty($data['class_id']) || !is_numeric($data['class_id'])) {
            $errors[] = 'Valid class ID is required.';
        }
        if (empty($data['student_id']) || !is_numeric($data['student_id'])) {
            $errors[] = 'Valid student ID is required.';
        }
        if (empty($data['grade']) || !is_numeric($data['grade'])) {
            $errors[] = 'Grade must be a numeric value.';
        }

        return $errors;
    }
}