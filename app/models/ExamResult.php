<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class ExamResult {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO exam_results (exam_id, student_id, marks_obtained) VALUES (:exam_id, :student_id, :marks_obtained)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAll() {
        $sql = "SELECT * FROM exam_results";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM exam_results WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE exam_results SET exam_id = :exam_id, student_id = :student_id, marks_obtained = :marks_obtained WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data[':id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "DELETE FROM exam_results WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}