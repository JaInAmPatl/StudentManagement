<?php
session_name('admin_session');
session_start();


if (!isset($_SESSION['domain']) && $_SESSION['domain'] !== "admin") {
    header("Location: index.html");
    exit();
}
$adminName = isset($_SESSION['name']) ? $_SESSION['name'] : "undefined";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <script src="addStudents.js" defer></script>
        <style>
            body {
                background-image: url("images/image4.jpg");
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            h1 {
                font-family: Arial, sans-serif;
                background-color: rgb(174, 26, 26);
                color: white;
                margin: 0;
                padding: 15px 0;
                font-size: 4em;
                font-weight: bold;
                text-align: center;
            }

            main {
                flex-grow: 1;
                padding: 20px;
            }

            form {
                max-width: 300px;
                margin: 20px auto;
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            input, button.addButton {
                width: 100%;
                padding: 8px;
                margin: 5px 0 15px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            button.addButton {
                background-color: rgb(174, 26, 26);
                color: white;
                cursor: pointer;
                border: none;
                font-weight: bold;
            }

            button.addButton:hover {
                background-color: rgb(140, 20, 20);
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
            <p id="name">Welcome <strong><?php echo htmlspecialchars($adminName); ?></strong></p>
            <h3>Add Students</h3>
            <form id="addStudentForm">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="Studentname">Name:</label>
                <input type="text" id="Studentname" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="studentid@college.com">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>

                <button type="submit" class="addButton" id="addButton">Add</button>
                <p id="msg"></p>
            </form>

            
        </main>

        <footer>
            <a href="logout/logout.php">
                <button class="logoutButton">Logout</button>
            </a>
        </footer>
       
      

       

    </body>
</html>
