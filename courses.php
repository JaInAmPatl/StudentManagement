<?php
session_start();
require_once 'config.php';


$subjects = [];
$grades = [];

$subjectNameAndIds = [];

$subject_ids;



if (isset($_SESSION['teacher_id'])) {

    $stmt = $conn->prepare("SELECT subject_name , grade FROM grades 
                        JOIN subjects  ON  SUBJECTS.subject_id = grades.subject_id
                        WHERE student_id = :student_id;");
    $stmt->bindParam(':student_id', $_SESSION['student_id']);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
           $subjects [ $row['subject_name'] ] = $row['grade'];
           $grades [] = $row['grade'];
        }     
    }else {
        echo "No subjects found";
    }
}
else {
    echo "Response Failed";
}

$average = array_sum($grades) / count($grades);


$stmt2 = $conn->prepare ("SELECT s.subject_id, s.subject_name 
                        FROM subjects s
                        LEFT JOIN enrollments e ON s.subject_id = e.subject_id AND e.student_id = :student_id
                        WHERE e.subject_id IS NULL;");
$stmt2->bindParam(':student_id', $_SESSION['student_id']);
$stmt2->execute();

if ($stmt2->rowCount() > 0) {
    $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $subjectNameAndIds[$row['subject_id']] = $row['subject_name'];   
        $subject_ids = $row['subject_id'];
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>My Courses</title>
    <style>
        .accordion-body textarea {
            width: 100%;
            height: 100%;
            resize: none; /* Prevent manual resizing */
            border: none; /* Remove textarea border for a clean look */
            outline: none; /* Remove outline when focused */
            padding: 10px; /* Optional padding for better readability */
            box-sizing: border-box; /* Ensure padding doesn't affect dimensions */
        }
        .form-popup {
        display: none;
        position: fixed;
        bottom: 0;
        right: 15px;
        border: 3px solid #f1f1f1;
        z-index: 9;
        }

        /* Add styles to the form container */
        .form-container {
        max-width: 300px;
        padding: 10px;
        background-color: white;
        }
        .form-container input[type=text], .form-container input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        border: none;
        background: #f1f1f1;
        }

        /* When the inputs get focus, do something */
        .form-container input[type=text]:focus, .form-container input[type=password]:focus {
        background-color: #ddd;
        outline: none;
        }
    </style>
</head>
<body>
    <h1 class="bg-info text-center">My Courses</h1>

    <div class="accordion accordion-flush" id="accordionFlushExample">
        <?php
        $count = 1;
        foreach ($subjects as $index => $subject) { 
            // Use a unique ID for each accordion item
            $collapseId = "flush-collapse" . $count;
            $headerId = "flush-heading" . $count;
        ?>
        <div class="accordion-item p-3 mb-2 bg-info text-dark">
            <h2 class="accordion-header" id="<?php echo $headerId; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="false"  aria-controls="<?php echo $collapseId; ?>">
                    <?php echo $index; ?>
                </button>
            </h2>
            <div id="<?php echo $collapseId; ?>"  class="accordion-collapse collapse"  aria-labelledby="<?php echo $headerId; ?>" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <textarea readonly class="text-left">
                    Your Grades for this subject are <?php echo $subject; ?>.
                    </textarea>
                </div>
            </div>
        </div>
        <?php $count++;} ?>
    </div>
    <p class="fs-2">Average Grade: <?php echo $average ?> </p>

    <button class="btn btn-success" onclick ="openForm()">Add new Courses</button>

   

<!-- The form -->
<div class="form-popup" id="myForm">
  <form class="form-container" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for ="text"><b>Subject ID</b></label>
    <input type="text" placeholder="Enter Subject ID " name="subjectIdToAdd" required>

    <label> There are subjects available to add are <?php foreach ($subjectNameAndIds as $subjectId => $subjectName) { echo $subjectId . " : " . $subjectName . "<br>"; } ?> </label>

    <button type="submit" class="btn btn-success">Add this subject</button>

    

    <button type="submit" class="btn btn-danger" onclick="closeForm()">Close</button>
  </form>
</div>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $stmt3 = $conn->prepare ("INSERT INTO enrollments (student_id, subject_id)
                                    SELECT :student_id, :subject_id
                                    WHERE NOT EXISTS (
                                    SELECT 1 FROM enrollments 
                                    WHERE student_id = :student_id AND subject_id = :subject_id);
                                ");

        $stmt3->bindParam(':student_id', $_SESSION['student_id']);
        $stmt3->bindParam(':subject_id', $_POST['subjectIdToAdd']);
        $stmt3->execute();

        $stmt4 = $conn->prepare ("INSERT INTO grades (student_id, subject_id)
                                    SELECT :student_id, :subject_id
                                    WHERE NOT EXISTS (
                                    SELECT 1 FROM grades
                                    WHERE student_id = :student_id AND subject_id = :subject_id);
                                ");
        $stmt4->bindParam(':student_id', $_SESSION['student_id']);
        $stmt4->bindParam(':subject_id', $_POST['subjectIdToAdd']);
        $stmt4->execute();
    }
?>



<script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>   
</body>
</html>
