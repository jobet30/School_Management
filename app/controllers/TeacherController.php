<?php

require_once 'database.php';

class TeacherController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createTeacher($data) {
        $validationErrors = $this->validateTeacherData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO teachers (id_number, name, email, subject_id, hire_date) 
                    VALUES (:id_number, :name, :email, :subject_id, :hire_date)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_number' => $data['id_number'],
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':subject_id' => $data['subject_id'],
                ':hire_date' => $data['hire_date']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            echo 'Error creating teacher: ' . $ex->getMessage();
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllTeachers() {
        try {
            $sql = "SELECT * FROM teachers";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo 'Error fetching teachers: ' . $ex->getMessage();
            return [];
        }
    }

    public function getTeacherById($id) {
        try {
            $sql = "SELECT * FROM teachers WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo 'Error fetching teacher: ' . $ex->getMessage();
            return null;
        }
    }

    public function updateTeacher($id, $data) {
        $validationErrors = $this->validateTeacherData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE teachers SET id_number = :id_number, name = :name, email = :email, 
                    subject_id = :subject_id, hire_date = :hire_date WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_number' => $data['id_number'],
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':subject_id' => $data['subject_id'],
                ':hire_date' => $data['hire_date'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            echo 'Error updating teacher: ' . $ex->getMessage();
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteTeacher($id) {
        try {
            $sql = "DELETE FROM teachers WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            echo 'Error deleting teacher: ' . $ex->getMessage();
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateTeacherData($data) {
        $errors = [];

        if (empty($data['id_number'])) {
            $errors[] = 'ID Number is required.';
        }
        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }
        if (empty($data['subject_id'])) {
            $errors[] = 'Subject ID is required.';
        }
        if (empty($data['hire_date'])) {
            $errors[] = 'Hire date is required.';
        }

        return $errors;
    }
}
