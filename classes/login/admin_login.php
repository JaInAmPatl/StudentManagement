<?php
session_name('admin_session');
session_start();
header('Content-Type: application/json');

require_once 'config/config.php';

$response  = ["msg" =>"" , 'success' => false];

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM ADMIN WHERE admin_id = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['admin_password'])) {
            // Regenerate session ID to ensure unique session
           
            
            $response['msg'] = "Login successful";
            $response['success'] = true;

            $_SESSION['domain'] = "admin";

            $_SESSION['name'] = $row['name'];
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['email'] = $row['email'];
           

        } else {
            $response['msg'] = "Invalid username or password";
            $response['success'] = false;
        }
    }
}

echo json_encode($response);
?>
