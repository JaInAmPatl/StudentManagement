<?php
session_name('teacher_session');
session_start();

require_once 'config/config.php';

$response  = ["msg" =>"" , 'success' => false];

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM TEACHERS WHERE teacher_id = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['password'])) {
            // Regenerate session ID to ensure unique session
            session_regenerate_id(true); // true to delete the old session
            
            $response['msg'] = "Login successful";
            $response['success'] = true;

            $_SESSION['domain'] = "teacher";

            $_SESSION['teacher_name'] = $row['name'];
            $_SESSION['teacher_id'] = $row['teacher_id'];
            $_SESSION['email'] = $row['email'];
        } else {
            $response['msg'] = "Invalid username or password";
            $response['success'] = false;
        }
    }
}

echo json_encode($response);
?>
