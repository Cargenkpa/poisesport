<?php
include 'connect_db.php';

// Prepare the query to create the talents table
$sql = "CREATE TABLE IF NOT EXISTS talents (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Success!</h2><p>The <b>talents</b> table has been created successfully in your database.</p>";
    echo "<a href='admin.php'>Go to Admin Panel</a>";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();
?>
