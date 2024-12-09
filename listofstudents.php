<?php
session_start();

require_once 'config.php';


$studentList = [];


if (isset($_SESSION['teacher_id']))  {

    $stmt = $conn->prepare("SELECT students.name , grades.grade from GRADES
                            JOIN SUBJECTS ON subjects.subject_id = grades.subject_id
                            JOIN STUDENTS ON students.student_id = grades.student_id
                            WHERE teacher_id = :teacher_id;"
                        );
    $stmt->bindParam(':teacher_id', $_SESSION['teacher_id']);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $studentList [ $row['name'] ] = $row['grade'];
        }
    }

}

else {
    echo "No teacher logged in";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>My Student's List</title>
</head>
<body>                
    <h2 class="bg-info text-center">My list of Students</h2>   

    <?php
        foreach ($studentList as $name => $grade) {
            echo "<p class='fs-4'>Name: $name <br> Grade: $grade </p>";
        }
    ?>
    <div class="d-flex h-100">
        <div class="align-self-start mr-auto">
                    
            <button class="btn btn-danger">Change student Grade</button>

            <p class="me"></p>
        </div>
    </div>




    
</body>
</html>               
        