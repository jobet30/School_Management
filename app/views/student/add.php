<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require_once 'controllers/StudentController.php';
require_once 'views/includes/header.php';
?>


<div class="container mt-4">
    <h2>Add New Student</h2>
    <form action="add_process.php" method="POST">
        <div class="mb-3">
            <label for="id_number" class="form-label">ID Number</label>
            <input type="text" class="form-control" id="id_number" name="id_number" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" name="age" required>
        </div>
        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <input type="text" class="form-control" id="course_id" name="course_id" required>
        </div>
        <button type="submit" class="btn btn-success">Add Student</button>
    </form>
</div>

<?php require_once 'views/includes/footer.php';?>