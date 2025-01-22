<?php
session_name('teacher_session');
session_start();


if (!isset($_SESSION['domain']) && $_SESSION['domain'] !== "teacher") {
    header("Location: index.html");
    exit();
}
$teacherName = isset($_SESSION['teacher_name']) ? $_SESSION['teacher_name'] : "undefined";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Teacher Dashboard</title>
        <style>
            body {
                background-image: url("Assets/images/image3.png");
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

           
            button.logoutButton {
                position: absolute; /* Position absolutely within the page */
                bottom: 20px; /* Align to the bottom */
                right: 20px; /* Align to the right */
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

            button.logoutButton:hover {
                background-color: rgb(0, 20, 20);
            }

            button.logoutButton:active {
                background-color: rgb(100, 14, 14);
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Dashboard</h1>
        </header>
        <main>
            <p id="name">Welcome <strong><?php echo htmlspecialchars($teacherName); ?></strong></p>
            <a href="classes/logic/listofstudents.php">View your Students</a>
        </main>

        <footer>
            <a href="classes/logout/logout.php">
                <button class="logoutButton">Logout</button>
            </a>
        </footer>
       
       

    </body>
</html>
