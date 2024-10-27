<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class ExtracurricularActivitiesController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createActivity($data) {
        $validationErrors = $this->validateActivityData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO extracurricular_activities (name, description) 
                    VALUES (:name, :description)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description']
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllActivities() {
        try {
            $sql = "SELECT * FROM extracurricular_activities";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getActivityById($id) {
        try {
            $sql = "SELECT * FROM extracurricular_activities WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateActivity($id, $data) {
        $validationErrors = $this->validateActivityData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE extracurricular_activities 
                    SET name = :name, description = :description 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteActivity($id) {
        try {
            $sql = "DELETE FROM extracurricular_activities WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateActivityData($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }

        return $errors;
    }
}