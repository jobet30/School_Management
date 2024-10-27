<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class StudentController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createStudent($data) {
        $validationErrors = $this->validateStudentData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $studentId = $this->generateStudentId();
            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = $createdAt;

            $sql = "INSERT INTO students (id_number, name, age, email, course_id, enrollment_date, created_at, updated_at) 
                    VALUES (:id_number, :name, :age, :email, :course_id, :enrollment_date, :created_at, :updated_at)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_number' => $studentId,
                ':name' => $data['name'],
                ':age' => $data['age'],
                ':email' => $data['email'],
                ':course_id' => $data['course_id'],
                ':enrollment_date' => $data['enrollment_date'],
                ':created_at' => $createdAt,
                ':updated_at' => $updatedAt
            ]);

            $newStudentId = $this->pdo->lastInsertId();
            $username = $data['id_number'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $role = 'student';

            $this->createUser($username, $data['name'], $data['email'], $password, $role, $createdAt, $updatedAt);
            return ['success' => true, 'id' => $newStudentId];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error: ' . $ex->getMessage()];
        }
    }

    private function createUser($username, $name, $email, $password, $role, $createdAt, $updatedAt) {
        try {
            $sql = "INSERT INTO users (name, email, password, role, created_at, updated_at) 
                    VALUES (:name, :email, :password, :role, :created_at, :updated_at)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $password,
                ':role' => $role,
                ':created_at' => $createdAt,
                ':updated_at' => $updatedAt
            ]);
        } catch (PDOException $ex) {
            throw new Exception('Failed to create user: ' . $ex->getMessage());
        }
    }

    private function generateStudentId() {
        $schoolYear = date('Y') . '-' . (date('Y') + 1);
        $sql = "SELECT id_number FROM students WHERE id_number LIKE :schoolYear ORDER BY id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':schoolYear' => $schoolYear . '%']);
        
        $lastStudentId = $stmt->fetchColumn();
        $nextNumber = 1;

        if ($lastStudentId) {
            $parts = explode('-', $lastStudentId);
            $nextNumber = (int)$parts[2] + 1;
        }

        return $schoolYear . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    public function getAllStudents() {
        try {
            $sql = "SELECT * FROM students";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getStudentById($id) {
        try {
            $sql = "SELECT * FROM students WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateStudent($id, $data) {
        $validationErrors = $this->validateStudentData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE students SET name = :name, age = :age, email = :email, course_id = :course_id, enrollment_date = :enrollment_date, updated_at = :updated_at WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':age' => $data['age'],
                ':email' => $data['email'],
                ':course_id' => $data['course_id'],
                ':enrollment_date' => $data['enrollment_date'],
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error: ' . $ex->getMessage()];
        }
    }

    public function deleteStudent($id) {
        try {
            $sql = "DELETE FROM students WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error: ' . $ex->getMessage()];
        }
    }

    private function validateStudentData($data) {
        $errors = [];

        if (empty($data['id_number'])) {
            $errors[] = 'ID Number is required.';
        }
        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }
        if (empty($data['age']) || !is_numeric($data['age'])) {
            $errors[] = 'Age must be a number.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }
        if (empty($data['course_id'])) {
            $errors[] = 'Course ID is required.';
        }
        if (empty($data['enrollment_date'])) {
            $errors[] = 'Enrollment date is required.';
        }
        if (empty($data['password'])) {
            $errors[] = 'Password is required.';
        }

        return $errors;
    }
}
