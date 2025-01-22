<?php
session_name('student_session');
session_start();
require_once 'config.php';

$subjects = [];
$grades = [];
$subjectNameAndIds = [];
$subject_ids = null;

if (isset($_SESSION['student_id'])) {
    $stmt = $conn->prepare(
        "SELECT subject_name, grade 
         FROM grades 
         JOIN subjects ON subjects.subject_id = grades.subject_id
         WHERE student_id = :student_id"
    );
    $stmt->bindParam(':student_id', $_SESSION['student_id']);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $subjects[$row['subject_name']] = $row['grade'];
            $grades[] = $row['grade'];
        }
    } else {
        echo "<div class='alert alert-warning'>No subjects found.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Response Failed</div>";
}

$average = count($grades) > 0 ? round(array_sum($grades) / count($grades), 2) : 0;

$stmt2 = $conn->prepare(
    "SELECT s.subject_id, s.subject_name 
     FROM subjects s
     LEFT JOIN enrollments e ON s.subject_id = e.subject_id AND e.student_id = :student_id
     WHERE e.subject_id IS NULL"
);
$stmt2->bindParam(':student_id', $_SESSION['student_id']);
$stmt2->execute();

if ($stmt2->rowCount() > 0) {
    $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $subjectNameAndIds[$row['subject_id']] = $row['subject_name'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subjectIdToAdd'])) {
    $stmt3 = $conn->prepare(
        "INSERT INTO enrollments (student_id, subject_id)
         SELECT :student_id, :subject_id
         WHERE NOT EXISTS (
             SELECT 1 FROM enrollments 
             WHERE student_id = :student_id AND subject_id = :subject_id
         )"
    );
    $stmt3->bindParam(':student_id', $_SESSION['student_id']);
    $stmt3->bindParam(':subject_id', $_POST['subjectIdToAdd']);
    $stmt3->execute();

    $stmt4 = $conn->prepare(
        "INSERT INTO grades (student_id, subject_id)
         SELECT :student_id, :subject_id
         WHERE NOT EXISTS (
             SELECT 1 FROM grades
             WHERE student_id = :student_id AND subject_id = :subject_id
         )"
    );
    $stmt4->bindParam(':student_id', $_SESSION['student_id']);
    $stmt4->bindParam(':subject_id', $_POST['subjectIdToAdd']);
    $stmt4->execute();

    header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
                background-image: url("images/image2.png");
            }
            
        .form-popup {
            display: none;
            position: fixed;
            bottom: 0;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 9;
            background-color: white;
            width: 100%;
            max-width: 400px;
            padding: 15px;
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
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center bg-danger text-white py-3 rounded">My Courses</h1>

        <!-- Average Grade -->
        <p class="fs-4 text-center">Average Grade: <strong><?php echo $average; ?></strong></p>

        <!-- Courses Accordion -->
        <div class="accordion accordion-flush" id="accordionCourses">
            <?php
            $count = 1;
            foreach ($subjects as $subjectName => $grade) { 
                $collapseId = "flush-collapse" . $count;
                $headerId = "flush-heading" . $count;
            ?>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="<?php echo $headerId; ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="false" aria-controls="<?php echo $collapseId; ?>">
                        <?php echo htmlspecialchars($subjectName); ?>
                    </button>
                </h2>
                <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $headerId; ?>" data-bs-parent="#accordionCourses">
                    <div class="accordion-body">
                        Your grade for this subject is: <strong><?php echo htmlspecialchars($grade); ?></strong>.
                    </div>
                </div>
            </div>
            <?php $count++; } ?>
        </div>

        <!-- Add Course Button -->
        <div class="text-center mt-4">
            <button class="btn btn-success" onclick="openForm()">Add New Course</button>
        </div>
    </div>

    <!-- Add Course Form -->
    <div class="form-popup" id="courseForm">
        <form class="form-container" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h3 class="text-center">Add a New Course</h3>
            <div class="mb-3">
                <label for="subjectIdToAdd" class="form-label">Subject ID</label>
                <input type="text" id="subjectIdToAdd" name="subjectIdToAdd" class="form-control" placeholder="Enter Subject ID" required>
            </div>
            <p>Available Subjects:</p>
            <ul>
                <?php foreach ($subjectNameAndIds as $subjectId => $subjectName) {
                    echo "<li>" . htmlspecialchars($subjectId) . ": " . htmlspecialchars($subjectName) . "</li>";
                } ?>
            </ul>
            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-primary">Add Course</button>
                <button type="button" class="btn btn-danger" onclick="closeForm()">Close</button>
            </div>
        </form>
    </div>

    <footer>
        <a href="logout.php"> 
            <button class="logout-button">Logout</button>
        </a>
    </footer>

    <script>
        function openForm() {
            document.getElementById("courseForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("courseForm").style.display = "none";
        }
    </script>



</body>
</html>
