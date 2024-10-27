<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class ExamsController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createExam($data) {
        $validationErrors = $this->validateExamData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO exams (class_id, title, date, total_marks) 
                    VALUES (:class_id, :title, :date, :total_marks)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':title' => $data['title'],
                ':date' => $data['date'],
                ':total_marks' => $data['total_marks'],
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllExams() {
        try {
            $sql = "SELECT * FROM exams";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getExamById($id) {
        try {
            $sql = "SELECT * FROM exams WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateExam($id, $data) {
        $validationErrors = $this->validateExamData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE exams 
                    SET class_id = :class_id, title = :title, date = :date, total_marks = :total_marks 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':title' => $data['title'],
                ':date' => $data['date'],
                ':total_marks' => $data['total_marks'],
                ':id' => $id,
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteExam($id) {
        try {
            $sql = "DELETE FROM exams WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateExamData($data) {
        $errors = [];

        if (empty($data['class_id'])) {
            $errors[] = 'Class ID is required.';
        }
        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        if (empty($data['date']) || !strtotime($data['date'])) {
            $errors[] = 'Valid date is required.';
        }
        if (empty($data['total_marks']) || !is_numeric($data['total_marks'])) {
            $errors[] = 'Total marks must be a number.';
        }

        return $errors;
    }
}