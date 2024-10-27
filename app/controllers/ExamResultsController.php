<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require_once 'database.php';

class ExamResultsController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createExamResult($data) {
        $validationErrors = $this->validateExamResultData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO exam_results (exam_id, student_id, marks_obtained) 
                    VALUES (:exam_id, :student_id, :marks_obtained)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':exam_id' => $data['exam_id'],
                ':student_id' => $data['student_id'],
                ':marks_obtained' => $data['marks_obtained'],
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllExamResults() {
        try {
            $sql = "SELECT * FROM exam_results";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getExamResultById($id) {
        try {
            $sql = "SELECT * FROM exam_results WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateExamResult($id, $data) {
        $validationErrors = $this->validateExamResultData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE exam_results 
                    SET exam_id = :exam_id, student_id = :student_id, marks_obtained = :marks_obtained 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':exam_id' => $data['exam_id'],
                ':student_id' => $data['student_id'],
                ':marks_obtained' => $data['marks_obtained'],
                ':id' => $id,
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteExamResult($id) {
        try {
            $sql = "DELETE FROM exam_results WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateExamResultData($data) {
        $errors = [];

        if (empty($data['exam_id'])) {
            $errors[] = 'Exam ID is required.';
        }
        if (empty($data['student_id'])) {
            $errors[] = 'Student ID is required.';
        }
        if (empty($data['marks_obtained']) || !is_numeric($data['marks_obtained'])) {
            $errors[] = 'Marks obtained must be a number.';
        }

        return $errors;
    }
}