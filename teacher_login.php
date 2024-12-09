<?php

session_start();
header('Content-Type: application/json');

require_once 'config.php';

$response  = ["msg" =>"" , 'success' => false];

if (isset($_POST['username']) && isset($_POST['password'])) {
 
    $username = $_POST['username'];
    $password = $_POST['password'];
    
        //Check if the domain Selected is  student
        $stmt = $conn->prepare("SELECT * FROM TEACHERS WHERE teacher_id = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $row['password'])) {
                $response['msg'] = "Login successful";
                $response['success'] = true;
                $_SESSION['name'] = $row['name'];
                $_SESSION['teacher_id'] = $row['teacher_id'];
            }else {
                $response['msg'] = "Invalid username or password";
                $response['success'] = false;
            }
        }
}

echo json_encode($response);
?>
