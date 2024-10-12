<?php
require 'db.php';

$submission_success = false; // Track the submission status

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetching form data
    $name = $_POST['name'];
    $contacts = $_POST['contacts'];
    $age = $_POST['age'];
    $student_number = $_POST['student_number'];
    $academic_level = $_POST['academic_level'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $section = $_POST['section'];
    $medical_history = $_POST['medical_history'];

    // Insert into student table
    $stmt = $conn->prepare("INSERT INTO students (name, contacts, age, student_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $contacts, $age, $student_number);
    $stmt->execute();
    $student_id = $stmt->insert_id;

    // Insert into educational_info table
    $stmt = $conn->prepare("INSERT INTO educational_info (student_id, academic_level, course, year_level, section) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $student_id, $academic_level, $course, $year_level, $section);
    $stmt->execute();

    // Insert into triage table
    $stmt = $conn->prepare("INSERT INTO triage (student_id, medical_history, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("is", $student_id, $medical_history);
    $stmt->execute();

    // Set the success flag
    $submission_success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Student Record</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Submit Student Medical Record</h1>

        <?php if ($submission_success): ?>
            <!-- Success Alert -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> The student record has been submitted successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="submit_record.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="contacts">Contacts:</label>
                <input type="text" class="form-control" id="contacts" name="contacts" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="student_number">Student Number:</label>
                <input type="text" class="form-control" id="student_number" name="student_number" required>
            </div>

            <h2 class="mt-4">Educational Information</h2>
            <div class="form-group">
                <label for="academic_level">Academic Level:</label>
                <input type="text" class="form-control" id="academic_level" name="academic_level" required>
            </div>
            <div class="form-group">
                <label for="course">Course:</label>
                <input type="text" class="form-control" id="course" name="course" required>
            </div>
            <div class="form-group">
                <label for="year_level">Year Level:</label>
                <input type="text" class="form-control" id="year_level" name="year_level" required>
            </div>
            <div class="form-group">
                <label for="section">Section:</label>
                <input type="text" class="form-control" id="section" name="section" required>
            </div>

            <h2 class="mt-4">Medical History</h2>
            <div class="form-group">
                <label for="medical_history">Medical History:</label>
                <textarea class="form-control" id="medical_history" name="medical_history" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Record</button>
        </form>

        <br>
        <!-- Back to Home Button -->
        <button class="btn btn-secondary mt-3" onclick="window.location.href='index.php'">Back to Home</button>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
