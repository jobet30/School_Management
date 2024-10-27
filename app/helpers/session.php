<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setSession($key, $value) {
    $_SESSION[$key] = $value;
}

function getSession($key) {
    return $_SESSION[$key] ?? null;
}

function isSessionSet($key) {
    return isset($_SESSION[$key]);
}

function unsetSession($key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

function destroySession() {
    session_unset();
    session_destroy();
}

function isLoggedIn() {
    return isSessionSet('user_id');
}

function requireLogin($redirectPage = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectPage");
        exit();
    }
}