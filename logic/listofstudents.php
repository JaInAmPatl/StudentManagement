<?php
require_once("../config.php");
session_name('teacher_session');
session_start();

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
    <style>
        body {
            background-image: url("../images/image2.png");
        }

        .logout-button {
            position: absolute;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: rgb(174, 26, 26);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: rgb(140, 20, 20);
        }

        .logout-button:active {
            background-color: rgb(100, 14, 14);
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .input-grade {
            width: 100px;
        }

    </style>
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

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Update Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($studentList as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['grade']) ?></td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">
                                <input type="number" name="new_grade" class="form-control input-grade me-2" placeholder="New grade" required>
                                <button type="submit" name="update_grade" class="btn btn-success">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <a href="../logout/logout.php">
            <button class="logout-button">Logout</button>
        </a>
    </footer>
</body>
</html>
