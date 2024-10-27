<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class AssignmentController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createAssignment($data) {
        $validationErrors = $this->validateAssignmentData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO assignments (class_id, title, description, due_date) 
                    VALUES (:class_id, :title, :description, :due_date)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':due_date' => $data['due_date'],
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllAssignments() {
        try {
            $sql = "SELECT * FROM assignments";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getAssignmentById($id) {
        try {
            $sql = "SELECT * FROM assignments WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateAssignment($id, $data) {
        $validationErrors = $this->validateAssignmentData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE assignments 
                    SET class_id = :class_id, title = :title, description = :description, due_date = :due_date 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':class_id' => $data['class_id'],
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':due_date' => $data['due_date'],
                ':id' => $id,
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteAssignment($id) {
        try {
            $sql = "DELETE FROM assignments WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateAssignmentData($data) {
        $errors = [];

        if (empty($data['class_id'])) {
            $errors[] = 'Class ID is required.';
        }
        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        if (empty($data['description'])) {
            $errors[] = 'Description is required.';
        }
        if (empty($data['due_date']) || !strtotime($data['due_date'])) {
            $errors[] = 'Valid due date is required.';
        }

        return $errors;
    }
}