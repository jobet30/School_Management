<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
include 'views/includes/header.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Welcome to the Student Management System</h1>
    <p class="text-center">Manage your students, courses, teachers, and more efficiently.</p>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-primary text-white">
                    Students
                </div>
                <div class="card-body">
                    <h5 class="card-title">Manage Students</h5>
                    <p class="card-text">Add, edit, or delete student records.</p>
                    <a href="students/index.php" class="btn btn-primary">View Students</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-success text-white">
                    Courses
                </div>
                <div class="card-body">
                    <h5 class="card-title">Manage Courses</h5>
                    <p class="card-text">Create and manage course offerings.</p>
                    <a href="courses/index.php" class="btn btn-success">View Courses</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-danger text-white">
                    Teachers
                </div>
                <div class="card-body">
                    <h5 class="card-title">Manage Teachers</h5>
                    <p class="card-text">Assign subjects and manage teacher records.</p>
                    <a href="teachers/index.php" class="btn btn-danger">View Teachers</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-warning text-white">
                    Subjects
                </div>
                <div class="card-body">
                    <h5 class="card-title">Manage Subjects</h5>
                    <p class="card-text">Create and manage subject offerings.</p>
                    <a href="subjects/index.php" class="btn btn-warning">View Subjects</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-info text-white">
                    Announcements
                </div>
                <div class="card-body">
                    <h5 class="card-title">Post Announcements</h5>
                    <p class="card-text">Keep students informed with announcements.</p>
                    <a href="announcements/index.php" class="btn btn-info">View Announcements</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header bg-secondary text-white">
                    Grades
                </div>
                <div class="card-body">
                    <h5 class="card-title">Manage Grades</h5>
                    <p class="card-text">Track and manage student grades.</p>
                    <a href="grades/index.php" class="btn btn-secondary">View Grades</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include 'views/includes/footer.php';
?>