<?php

session_start();

// Remove session token from the database
if ($_SESSION['role'] === "student") {
    $stmt = $conn->prepare("UPDATE students SET session_token = NULL WHERE student_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
} elseif ($_SESSION['role'] === "teacher") {
    $stmt = $conn->prepare("UPDATE teachers SET session_token = NULL WHERE teacher_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

session_unset();
session_destroy();
header("Location:../index.html");
exit();


?>