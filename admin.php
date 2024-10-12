<?php
require 'db.php';

// Handle accept, archive, and pending actions via form submission
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $record_id = $_GET['id'];

    if (in_array($action, ['accepted', 'archived', 'pending'])) {
        $stmt = $conn->prepare("UPDATE triage SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $record_id);
        $stmt->execute();

        // Redirect back to admin.php after the update
        header("Location: admin.php");
        exit(); // Ensure that no further code is executed after the redirect
    }
}

// Fetch records
$pending_result = mysqli_query($conn, "SELECT triage.id, students.name AS student_name, triage.medical_history, triage.status, staff.name AS staff_name FROM triage LEFT JOIN students ON triage.student_id = students.id LEFT JOIN staff ON triage.assigned_staff = staff.id WHERE triage.status = 'pending'");
$pending_records = mysqli_fetch_all($pending_result, MYSQLI_ASSOC);

$accepted_result = mysqli_query($conn, "SELECT triage.id, students.name AS student_name, triage.medical_history, triage.status, staff.name AS staff_name FROM triage LEFT JOIN students ON triage.student_id = students.id LEFT JOIN staff ON triage.assigned_staff = staff.id WHERE triage.status = 'accepted'");
$accepted_records = mysqli_fetch_all($accepted_result, MYSQLI_ASSOC);

$archived_result = mysqli_query($conn, "SELECT triage.id, students.name AS student_name, triage.medical_history, triage.status, staff.name AS staff_name FROM triage LEFT JOIN students ON triage.student_id = students.id LEFT JOIN staff ON triage.assigned_staff = staff.id WHERE triage.status = 'archived'");
$archived_records = mysqli_fetch_all($archived_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Admin Panel</h1>

            <!-- Add Home button -->
        <div class="text-right mb-4">
            <a href="index.php" class="btn btn-secondary">Home</a>
        </div>

         <!-- Button for adding staff -->
         <div class="text-right mb-4">
            <a href="add_staff.php" class="btn btn-success">Add Staff</a>
        </div>

        <div class="mb-4">
            <h2>Pending Medical Records</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Medical History</th>
                            <th>Status</th>
                            <th>Assigned Staff</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['id']) ?></td>
                            <td><?= htmlspecialchars($record['student_name']) ?></td>
                            <td><?= htmlspecialchars($record['medical_history']) ?></td>
                            <td><?= htmlspecialchars($record['status']) ?></td>
                            <td><?= htmlspecialchars($record['staff_name']) ?></td>
                            <td>
                                <form method="GET" action="admin.php" class="d-inline">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
                                    <select name="action" class="custom-select d-inline" style="width: auto;">
                                        <option value="pending" <?= $record['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="accepted" <?= $record['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                        <option value="archived" <?= $record['status'] == 'archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                                <a href="edit_record.php?id=<?= htmlspecialchars($record['id']) ?>" class="btn btn-warning">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-4">
            <h2>Accepted Medical Records</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Medical History</th>
                            <th>Status</th>
                            <th>Assigned Staff</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accepted_records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['id']) ?></td>
                            <td><?= htmlspecialchars($record['student_name']) ?></td>
                            <td><?= htmlspecialchars($record['medical_history']) ?></td>
                            <td><?= htmlspecialchars($record['status']) ?></td>
                            <td><?= htmlspecialchars($record['staff_name']) ?></td>
                            <td>
                                <form method="GET" action="admin.php" class="d-inline">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
                                    <select name="action" class="custom-select d-inline" style="width: auto;">
                                        <option value="pending" <?= $record['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="accepted" <?= $record['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                        <option value="archived" <?= $record['status'] == 'archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                                <a href="edit_record.php?id=<?= htmlspecialchars($record['id']) ?>" class="btn btn-warning">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-4">
            <h2>Archived Medical Records</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Medical History</th>
                            <th>Status</th>
                            <th>Assigned Staff</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archived_records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['id']) ?></td>
                            <td><?= htmlspecialchars($record['student_name']) ?></td>
                            <td><?= htmlspecialchars($record['medical_history']) ?></td>
                            <td><?= htmlspecialchars($record['status']) ?></td>
                            <td><?= htmlspecialchars($record['staff_name']) ?></td>
                            <td>
                                <form method="GET" action="admin.php" class="d-inline">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
                                    <select name="action" class="custom-select d-inline" style="width: auto;">
                                        <option value="pending" <?= $record['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="accepted" <?= $record['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                        <option value="archived" <?= $record['status'] == 'archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                                <a href="edit_record.php?id=<?= htmlspecialchars($record['id']) ?>" class="btn btn-warning">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
