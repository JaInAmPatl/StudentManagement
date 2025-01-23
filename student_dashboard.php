<?php 
session_name('student_session');
session_start();
if (!isset($_SESSION['domain']) && $_SESSION['domain'] !== "student") {
    header("Location: index.html");
    exit();
}


    $studentName = isset($_SESSION['student_name']) ? $_SESSION['student_name'] : "undefined";

?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            body {
                background-image: url("images/image2.png");
            }
            h1 {
                font-family: Arial, sans-serif;
                background-color: rgb(174, 26, 26);
                color: white;

                margin: 0;
                padding: 15px 0;
                font-size: 4em;
                font-weight: bold;
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
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <title>Student Dashboard</title>
    </head>
    <body>
        <h1 class="text-center text-light fw-bold">Dashboard</h1>

        <p>Welcome <?php echo $studentName ?></p>

        <a href = "logic/courses.php">View My courses</a>

        <br>

        <footer>
            <a href="logout/logout.php">
                <button class="logout-button">Logout</button>
            </a>
        </footer>

    </body>
</html>


    

