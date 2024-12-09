<?php
session_start();
 $teacherName = isset($_SESSION['name']) ? $_SESSION['name'] : "undefined";

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
        input,  button {
        width: 100%;
        padding: 8px;
        margin: 5px 0 15px;
        border-radius: 4px;
        border: 1px solid #ccc;
        }

        button {
            background-color: rgb(174, 26, 26);
            color: white;
            cursor: pointer;
            border: none;
        }


        .form-container {
            width: 300px;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
        }

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Student Dashboard</title>
    <script src="listofstudents.js?version=1" defer></script>

<body>
    <h1>Dashboard</h1>

    <p id="name">Welcome <?php echo $teacherName ?></p> 

    <a href = "listofstudents.php">View your Students</a>
    <h3>Add Students</h3>
    <form>
        <div class="form-container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="name">Name:</label>
            <input type="text" id="Studentname" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Student[studentid]@college.com" >

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required >

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required >

            <button type="submit" id="addButton">Add</button>  

            <p id="msg"></p>
        </div>

    </form>


    

</body>
</html>



