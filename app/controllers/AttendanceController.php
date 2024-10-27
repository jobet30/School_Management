<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class AttendanceController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createAttendance($data) {
        $validationErrors = $this->validateAttendanceData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO attendance (class_id, student_id, attendance_date, status) 
                    VALUES (:class_id, :student_id, :attendance_date, :status)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':student_id' => $data['student_id'],
                ':attendance_date' => $data['attendance_date'],
                ':status' => $data['status'],
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllAttendanceRecords() {
        try {
            $sql = "SELECT * FROM attendance";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getAttendanceById($id) {
        try {
            $sql = "SELECT * FROM attendance WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateAttendance($id, $data) {
        $validationErrors = $this->validateAttendanceData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE attendance 
                    SET class_id = :class_id, student_id = :student_id, attendance_date = :attendance_date, 
                        status = :status 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':student_id' => $data['student_id'],
                ':attendance_date' => $data['attendance_date'],
                ':status' => $data['status'],
                ':id' => $id,
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteAttendance($id) {
        try {
            $sql = "DELETE FROM attendance WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateAttendanceData($data) {
        $errors = [];

        if (empty($data['class_id'])) {
            $errors[] = 'Class ID is required.';
        }
        if (empty($data['student_id'])) {
            $errors[] = 'Student ID is required.';
        }
        if (empty($data['attendance_date']) || !strtotime($data['attendance_date'])) {
            $errors[] = 'Valid attendance date is required.';
        }
        if (empty($data['status']) || !in_array($data['status'], ['present', 'absent', 'late'])) {
            $errors[] = 'Status is required and must be either present, absent, or late.';
        }

        return $errors;
    }
}