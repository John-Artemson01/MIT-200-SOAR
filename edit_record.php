<?php
require 'db.php';

if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    // Fetch the full record from students, educational_info, and triage tables
    $stmt = $conn->prepare("SELECT triage.id AS triage_id, triage.medical_history, triage.status, 
                                   students.id AS student_id, students.name, students.contacts, students.age, students.student_number, 
                                   educational_info.academic_level, educational_info.course, educational_info.year_level, educational_info.section,
                                   triage.assigned_staff
                            FROM triage 
                            LEFT JOIN students ON triage.student_id = students.id 
                            LEFT JOIN educational_info ON students.id = educational_info.student_id
                            WHERE triage.id = ?");
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    // Fetch all staff members for the dropdown
    $staff_stmt = $conn->prepare("SELECT id, name FROM staff");
    $staff_stmt->execute();
    $staff_result = $staff_stmt->get_result();

    // Check if the form is submitted to update the records
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Collect updated information
        $name = $_POST['name'];
        $contacts = $_POST['contacts'];
        $age = $_POST['age'];
        $student_number = $_POST['student_number'];
        $academic_level = $_POST['academic_level'];
        $course = $_POST['course'];
        $year_level = $_POST['year_level'];
        $section = $_POST['section'];
        $medical_history = $_POST['medical_history'];
        $assigned_staff = $_POST['assigned_staff']; // Get selected assigned staff

        // Update the students table
        $stmt = $conn->prepare("UPDATE students SET name = ?, contacts = ?, age = ?, student_number = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $name, $contacts, $age, $student_number, $record['student_id']);
        $stmt->execute();

        // Update the educational_info table
        $stmt = $conn->prepare("UPDATE educational_info SET academic_level = ?, course = ?, year_level = ?, section = ? WHERE student_id = ?");
        $stmt->bind_param("ssisi", $academic_level, $course, $year_level, $section, $record['student_id']);
        $stmt->execute();

        // Update the triage table (medical history and assigned staff)
        $stmt = $conn->prepare("UPDATE triage SET medical_history = ?, assigned_staff = ? WHERE id = ?");
        $stmt->bind_param("sii", $medical_history, $assigned_staff, $record['triage_id']);
        $stmt->execute();

        echo "Records updated successfully!";
        header("Location: admin.php"); // Redirect back to the admin panel
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Records for <?= htmlspecialchars($record['name']) ?></h1>
        <form action="edit_record.php?id=<?= $record_id ?>" method="POST">
            <h3>Student Information</h3>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($record['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="contacts">Contacts:</label>
                <input type="text" class="form-control" id="contacts" name="contacts" value="<?= htmlspecialchars($record['contacts']) ?>" required>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name="age" value="<?= htmlspecialchars($record['age']) ?>" required>
            </div>

            <div class="form-group">
                <label for="student_number">Student Number:</label>
                <input type="text" class="form-control" id="student_number" name="student_number" value="<?= htmlspecialchars($record['student_number']) ?>" required>
            </div>

            <h3>Educational Information</h3>
            <div class="form-group">
                <label for="academic_level">Academic Level:</label>
                <input type="text" class="form-control" id="academic_level" name="academic_level" value="<?= htmlspecialchars($record['academic_level']) ?>" required>
            </div>

            <div class="form-group">
                <label for="course">Course:</label>
                <input type="text" class="form-control" id="course" name="course" value="<?= htmlspecialchars($record['course']) ?>" required>
            </div>

            <div class="form-group">
                <label for="year_level">Year Level:</label>
                <input type="number" class="form-control" id="year_level" name="year_level" value="<?= htmlspecialchars($record['year_level']) ?>" required>
            </div>

            <div class="form-group">
                <label for="section">Section:</label>
                <input type="text" class="form-control" id="section" name="section" value="<?= htmlspecialchars($record['section']) ?>" required>
            </div>

            <h3>Medical Information</h3>
            <div class="form-group">
                <label for="medical_history">Medical History:</label>
                <textarea class="form-control" id="medical_history" name="medical_history" required><?= htmlspecialchars($record['medical_history']) ?></textarea>
            </div>

            <h3>Assign Staff</h3>
            <div class="form-group">
                <label for="assigned_staff">Assigned Staff:</label>
                <select class="form-control" id="assigned_staff" name="assigned_staff" required>
                    <option value="">Select Staff</option>
                    <?php while ($staff = $staff_result->fetch_assoc()): ?>
                        <option value="<?= $staff['id'] ?>" <?= ($staff['id'] == $record['assigned_staff']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($staff['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Record</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
