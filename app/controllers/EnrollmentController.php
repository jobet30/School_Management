<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class EnrollmentController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createEnrollment($data) {
        $validationErrors = $this->validateEnrollmentData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO enrollments (class_id, student_id, enrollment_date) 
                    VALUES (:class_id, :student_id, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':student_id' => $data['student_id']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllEnrollments() {
        try {
            $sql = "SELECT * FROM enrollments";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getEnrollmentById($id) {
        try {
            $sql = "SELECT * FROM enrollments WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateEnrollment($id, $data) {
        $validationErrors = $this->validateEnrollmentData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE enrollments SET class_id = :class_id, student_id = :student_id 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':student_id' => $data['student_id'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteEnrollment($id) {
        try {
            $sql = "DELETE FROM enrollments WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateEnrollmentData($data) {
        $errors = [];

        if (empty($data['class_id'])) {
            $errors[] = 'Class ID is required.';
        }
        if (empty($data['student_id'])) {
            $errors[] = 'Student ID is required.';
        }

        return $errors;
    }
}