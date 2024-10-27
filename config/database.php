<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once 'config/config.php';

/**
 * Create a Database connection
 * 
 * @return PDO|null Returns a PDO instance or null on failure
 * 
 * @author Jobet P. Casquejo
 * @version 1.0
 * @date 10/27/2024
 */
function getDatabaseConnection(){
    try{
        $conn = 'mysql:host' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        
        $option = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        $pdo = new PDO($conn, DB_USER, DB_PASS, $option);
        return $conn;
        
    } catch (PDOException $ex) {
        if(APP_DEBUG){
            echo 'Database Connection Error: ' . $ex->getMessage();
        } else {
            echo 'Could not connect to the Database';
        }
        return null;
    }
}
