<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'school_management');
define('DB_USER', 'root');
define('DB_PASS','');

function getDatabaseConnection(){
    static $pdo = null;

    if($pdo === null){
        try{
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            die("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}

function fetchAll($query, $params = []){
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function executeQuery($query, $params = []) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare($query);
    return $stmt->execute($params);
}

function getLastInsertId() {
    return getDatabaseConnection()->lastInsertId();
}