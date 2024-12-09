<?php 
session_start();
$studentName = isset($_SESSION['name']) ? $_SESSION['name'] : "undefined";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        h1 {
            font-family: Arial, sans-serif;
            background-color: rgb(174, 26, 26);
            color: white;

            margin: 0;
            padding: 15px 0;
            font-size: 4em;
            font-weight: bold;
        }

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Student Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>

    <p>Welcome <?php echo $studentName ?></p>

    <a href = "courses.php">View My courses</a>
    <p>Add new courses</p>

</body>
</html>

