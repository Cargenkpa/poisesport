<?php
include 'db_connect.php';

// DEBUG: See what is being sent
echo "<pre>";
print_r($_POST);
echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'] ?? 'NOT SET';
    $email = $_POST['email'] ?? 'NOT SET';
    $phone = $_POST['phone'] ?? 'NOT SET';
    $subject = $_POST['WHAT WOULD YOU LIKE TO TALK ABOUT?'] ?? 'NOT SET';
    $message = $_POST['message'] ?? 'NOT SET';

    $sql = "INSERT INTO contact_messages (fullname, email, phone, subject, message) 
            VALUES ('$fullname', '$email', '$phone', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "DATABASE RECORD CREATED SUCCESSFULLY";
    } else {
        echo "DATABASE ERROR: " . $conn->error;
    }
}
$conn->close();
?>