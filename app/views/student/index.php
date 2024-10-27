<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
$studentController = new StudentController();

require_once 'controllers/StudentController.php';
require_once 'views/includes/header.php';

$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
$students = $studentController->getAllStudents($searchQuery);
?>

<div class="container mt-4">
    <h2>Student List</h2>
    
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search by name or ID number" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
            <a href="index.php" class="btn btn-outline-danger">Reset</a>
        </div>
    </form>
    
    <a href="add.php" class="btn btn-primary mb-3">Add Student</a>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo $student['id']; ?></td>
                <td><?php echo $student['id_number']; ?></td>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['email']; ?></td>
                <td><?php echo $student['age']; ?></td>
                <td><?php echo $student['course_id']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'views/includes/footer.php';?>