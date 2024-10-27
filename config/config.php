<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

/**
 * Summary of config.php
 * @author Jobet P. Casquejo
 * @version 1.0
 * @package config
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_management');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf-8');

define('APP_NAME', 'School Management System');
define('APP_ENV', 'local');
define('APP_DEBUG', true);
define('APP_LOCALHOST', 'http://localhost/School_Management');

date_default_timezone_set('Asia/Manila');

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
