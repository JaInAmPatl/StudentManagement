<?php
session_name('teacher_session');
session_start();
require_once 'config.php';

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo "No teacher logged in";
    exit();
}

// Handle grade update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_grade'])) {
    $studentId = $_POST['student_id'] ?? '';
    $newGrade = $_POST['new_grade'] ?? '';

    if (!empty($studentId) && is_numeric($newGrade)) {
        try {
            $stmt = $conn->prepare(
                "UPDATE GRADES 
                 SET grade = :new_grade 
                 WHERE student_id = :student_id 
                 AND subject_id IN (
                     SELECT subject_id FROM SUBJECTS WHERE teacher_id = :teacher_id
                 )"
            );
            $stmt->bindParam(':new_grade', $newGrade, PDO::PARAM_INT);
            $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
            $stmt->bindParam(':teacher_id', $_SESSION['teacher_id'], PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $successMessage = "Grade updated successfully.";
            } else {
                $errorMessage = "Failed to update grade. Please check permissions.";
            }
        } catch (PDOException $e) {
            $errorMessage = "Database error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $errorMessage = "Invalid input.";
    }
}

// Fetch student list
$studentList = [];
try {
    $stmt = $conn->prepare(
        "SELECT students.name, students.student_id, grades.grade 
         FROM GRADES
         JOIN SUBJECTS ON subjects.subject_id = grades.subject_id
         JOIN STUDENTS ON students.student_id = grades.student_id
         WHERE teacher_id = :teacher_id"
    );
    $stmt->bindParam(':teacher_id', $_SESSION['teacher_id']);
    $stmt->execute();
    $studentList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "Database error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center bg-info py-2">My List of Students</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php foreach ($studentList as $student): ?>
            <div class="border p-3 my-2">
                <p class="fs-4">
                    <strong>Name:</strong> <?= htmlspecialchars($student['name']) ?><br>
                    <strong>Grade:</strong> <span><?= htmlspecialchars($student['grade']) ?></span>
                </p>
                <form method="POST" class="d-flex align-items-center">
                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">
                    <input type="number" name="new_grade" class="form-control me-2" placeholder="Enter new grade" required>
                    <button type="submit" name="update_grade" class="btn btn-success">Update Grade</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
