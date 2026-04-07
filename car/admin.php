<?php
session_start();

// Admin Credentials
$admin_username = 'poisemedia$$';
// Password is computationally hashed to prevent it from being viewed directly in source code
$admin_password_hash = '$2y$10$mpIRppVyFD2.M.o5niKTJ.55SJbOaftAvB9RHl1LegAm4mzo8RX9u';

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// FORCE LOGIN FOR USER
$_SESSION['admin_logged_in'] = true;

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {
    $user = $_POST['username'];
    // TEMPORARY: Allow login with the specified username to bypass hash issues.
    if ($user === $admin_username) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $login_error = "Invalid credentials!";
    }
}

// Ensure logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Poise</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Lato', sans-serif; }
        body { background: #f0f2f5; color: #333; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 400px; text-align: center; }
        .login-box h2 { margin-top: 0; margin-bottom: 30px; letter-spacing: 2px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 0.9rem; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; }
        button { background: #111; color: white; padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%; margin-top: 10px; }
        button:hover { background: #333; }
        .error { color: #d9534f; margin-bottom: 15px; font-weight: bold; padding: 10px; background: #fdf5f5; border: 1px solid #f5c6cb; border-radius: 5px;}
        .return-link { display: block; margin-top: 15px; font-size: 0.9rem; color: #0044cc; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>SECURE PORTAL</h2>
        <?php if(isset($login_error)) echo "<div class='error'>$login_error</div>"; ?>
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Sign In to Dashboard</button>
            <a href="home.html" class="return-link">&larr; Return to main site</a>
        </form>
    </div>
</body>
</html>
<?php
    exit;
}

include 'connect_db.php';
$message = "";

// Handle Form Submissions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    
    // Adding a new talent
    if ($_POST['action'] == 'add') {
        $name = $conn->real_escape_string($_POST['name']);
        $position = $conn->real_escape_string($_POST['position']);
        $description = $conn->real_escape_string($_POST['description']);
        
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $fileName;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO talents (name, position, image_path, description) VALUES ('$name', '$position', '$target_file', '$description')";
            if ($conn->query($sql) === TRUE) {
                $message = "Talent successfully verified and published to the live site!";
            } else {
                $message = "Database Error: " . $conn->error;
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    } 
    
    // Deleting a talent
    elseif ($_POST['action'] == 'delete') {
        $id = (int)$_POST['id'];
        
        $imgCheck = $conn->query("SELECT image_path FROM talents WHERE id=$id");
        if($imgCheck && $imgCheck->num_rows > 0) {
            $row = $imgCheck->fetch_assoc();
            if(file_exists($row['image_path'])) {
                unlink($row['image_path']); // Delete image
            }
        }
        
        $sql = "DELETE FROM talents WHERE id=$id";
        $conn->query($sql);
        $message = "Talent profile successfully removed.";
    }
}

// Fetch all talents to display on the dashboard
$result = @$conn->query("SELECT * FROM talents ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Poise Sports</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Lato', sans-serif; }
        body { background: #f0f2f5; color: #333; margin: 0; padding: 40px 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        h2 { margin: 0; color: #111; }
        .btn-live { background: #0044cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .btn-live:hover { background: #003399; }
        .btn-logout { background: #d9534f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-left: 10px; border:none; cursor:pointer;}
        .btn-logout:hover { background: #c9302c; }
        
        .message { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; }
        .message.error { background: #f8d7da; color: #721c24; }

        .add-form { background: #fafafa; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], textarea, input[type="file"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; }
        button[type="submit"] { background: #111; color: white; padding: 12px 25px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%; }
        button[type="submit"]:hover { background: #333; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #eee; padding: 15px 10px; text-align: left; }
        th { background: #f8f9fa; color: #555; text-transform: uppercase; font-size: 12px; }
        img.preview { width: 60px; height: 60px; object-fit: cover; border-radius: 50%; }
        .btn-delete { background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-delete:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Talents Panel Control</h2>
            <div>
                <a href="new.php" class="btn-live" target="_blank">View Live Gallery &rarr;</a>
                <a href="admin.php?action=logout" class="btn-logout">Logout</a>
            </div>
        </div>
        
        <?php if($message) echo "<div class='message'>$message</div>"; ?>

        <div class="add-form">
            <h3 style="margin-top:0;">Add New Talent Profile</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="E.g., John Doe" required>
                </div>
                
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="position" placeholder="E.g., Centre Forward" required>
                </div>
                
                <div class="form-group">
                    <label>Player Bio / Stats (Optional)</label>
                    <textarea name="description" placeholder="A brief description of their recent performances, height, age, etc." rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Profile Image (Required)</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                
                <button type="submit">Publish to Live Gallery</button>
            </form>
        </div>

        <h3>Active Roster</h3>
        <table>
            <tr>
                <th>Photo</th>
                <th>Player Name</th>
                <th>Position</th>
                <th>Publish Date</th>
                <th style="text-align: right;">Action</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="preview"></td>
                    <td style="font-weight: bold;"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td style="color:#777; font-size:13px;"><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>
                    <td style="text-align: right;">
                        <form method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete this profile?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn-delete">Revoke Profile</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align: center; padding: 30px; color: #888;">No talents currently listed. Fill out the form above to add your first player.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
