<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'database.php';

class AnnouncementController {
    private $pdo;

    public function __construct() {
        $this->pdo = getDatabaseConnection();
    }

    public function createAnnouncement($data) {
        $validationErrors = $this->validateAnnouncementData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "INSERT INTO announcements (title, content, created_by, is_public) 
                    VALUES (:title, :content, :created_by, :is_public)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':content' => $data['content'],
                ':created_by' => $data['created_by'],
                ':is_public' => $data['is_public'] // true or false
            ]);
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function getAllPublicAnnouncements() {
        try {
            $sql = "SELECT * FROM announcements WHERE is_public = TRUE";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            return [];
        }
    }

    public function getAnnouncementById($id) {
        try {
            $sql = "SELECT * FROM announcements WHERE id = :id AND is_public = TRUE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function updateAnnouncement($id, $data) {
        $validationErrors = $this->validateAnnouncementData($data);
        if (!empty($validationErrors)) {
            return ['success' => false, 'errors' => $validationErrors];
        }

        try {
            $sql = "UPDATE announcements SET title = :title, content = :content, is_public = :is_public 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':content' => $data['content'],
                ':is_public' => $data['is_public'], // true or false
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    public function deleteAnnouncement($id) {
        try {
            $sql = "DELETE FROM announcements WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $ex) {
            return ['success' => false, 'error' => 'Database error.'];
        }
    }

    private function validateAnnouncementData($data) {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        if (empty($data['content'])) {
            $errors[] = 'Content is required.';
        }
        if (empty($data['created_by'])) {
            $errors[] = 'Created by user ID is required.';
        }

        return $errors;
    }
}