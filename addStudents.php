<?php

require_once 'config.php';

$response = ['msg' => '', 'success' => false];

//Check if the form is submitted
if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) ) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO STUDENTS (student_id, name, password , email) VALUES (:id, :name, :password, :email)");

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $response['msg'] = "Student added successfully";
        $response['success'] = true;
        $response['data'] = $name;
     } 
     else {
        $response['msg'] = "Failed to add student";
        $response['success'] = false;
        
    }

}
echo json_encode($response);


?>