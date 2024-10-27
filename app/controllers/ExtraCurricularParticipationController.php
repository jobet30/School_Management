<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require_once 'database.php';

class ExtraCurricularParticipationController{
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function addParticipation($data) {
        $validationErrors = $this->validateParticipationData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO extracurricular_participation (activity_id, student_id, participation_date) 
                    VALUES (:activity_id, :student_id, :participation_date)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':activity_id' => $data['activity_id'],
                ':student_id' => $data['student_id'],
                ':participation_date' => $data['participation_date']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getParticipations() {
        try {
            $sql = "SELECT * FROM extracurricular_participation";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getParticipationById($id) {
        try {
            $sql = "SELECT * FROM extracurricular_participation WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateParticipation($id, $data) {
        $validationErrors = $this->validateParticipationData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE extracurricular_participation 
                    SET activity_id = :activity_id, student_id = :student_id, participation_date = :participation_date 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':activity_id' => $data['activity_id'],
                ':student_id' => $data['student_id'],
                ':participation_date' => $data['participation_date'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteParticipation($id) {
        try {
            $sql = "DELETE FROM extracurricular_participation WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateParticipationData($data) {
        $errors = [];

        if (empty($data['activity_id']) || !is_numeric($data['activity_id'])) {
            $errors[] = 'Valid activity ID is required.';
        }
        if (empty($data['student_id']) || !is_numeric($data['student_id'])) {
            $errors[] = 'Valid student ID is required.';
        }
        if (empty($data['participation_date'])) {
            $errors[] = 'Participation date is required.';
        }

        return $errors;
    }
}