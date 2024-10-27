<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class AssignmentSubmission {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO assignment_submission (assignment_id, student_id, submitted_at, grade, feedback) VALUES (:assignment_id, :student_id, :submitted_at, :grade, :feedback)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAll() {
        $sql = "SELECT * FROM assignment_submission";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM assignment_submission WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE assignment_submission SET assignment_id = :assignment_id, student_id = :student_id, submitted_at = :submitted_at, grade = :grade, feedback = :feedback WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data[':id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "DELETE FROM assignment_submission WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}