<?php

require_once 'config.php';

$response = ["msg" => "" , 'success' => false];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (empty($username) || empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "All fields are required!";
        exit();
    }

    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit();
    }

   
   $enteringPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, password) VALUES (:username, :name, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $enteringPassword);
    $stmt->execute();

   

   $response['msg'] = "Student added successfully";
    $response['success'] = true;

    echo json_encode($response);
} else {
    $response['msg'] = "Invalid request method";
    
    echo json_encode($response);
}
?>
