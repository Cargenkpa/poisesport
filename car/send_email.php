<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect and sanitize input data
    $name    = strip_tags(trim($_POST["full_name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone   = strip_tags(trim($_POST["phone"]));
    $subject_type = strip_tags(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // 2. Set the recipient email (YOUR EMAIL)
    $recipient = "info@poiseentertainmentgroup.com";

    // 3. Set the email subject
    $email_subject = "New Contact: $subject_type from $name";

    // 4. Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n\n";
    $email_content .= "Message:\n$message\n";

    // 5. Build email headers
    $email_headers = "From: $name <$email>";

    // 6. Send the email
    if (mail($recipient, $email_subject, $email_content, $email_headers)) {
        echo "<h1>Thank you! Your message has been sent.</h1>";
    } else {
        echo "<h1>Oops! Something went wrong and we couldn't send your message.</h1>";
    }
} else {
    echo "<h1>Access denied.</h1>";
}
?>