<?php
require_once 'config.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if (password_verify($_POST['password'], ADMIN_PASSWORD_HASH)) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            header("Location: rankings.html");
            exit();
        }
    } else {
        // Show login form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #121212; color: #f0f0f0; }
                .login-container { max-width: 400px; margin: 100px auto; padding: 20px; background: #1e1e1e; border-radius: 8px; }
                .form-group { margin-bottom: 15px; }
                input[type="password"] { width: 100%; padding: 10px; background: #333; border: 1px solid #555; color: white; }
                button { background: #1976d2; color: white; border: none; padding: 10px 15px; width: 100%; cursor: pointer; }
            </style>
        </head>
        <body>
            <div class="login-container">
                <h2>Admin Login</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Super Strong Password:</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);
    $kit = htmlspecialchars($_POST['kit']);
    $tier = htmlspecialchars($_POST['tier']);
    $peakTier = htmlspecialchars($_POST['peakTier']);
    
    // Connect to database (in a real app)
    try {
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $stmt = $pdo->prepare("INSERT INTO players (name, kit, tier, peak_tier, elo) VALUES (?, ?, ?, ?, ?)");
        $elo = $tierPoints[$tier] ?? 0;
        $stmt->execute([$username, $kit, $tier, $peakTier, $elo]);
        $success = "Player added successfully!";
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Player - Admin</title>
    <!-- Keep your existing styles -->
</head>
<body>
    <div class="admin-container">
        <h1>Add New Player</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Keep your existing form fields -->
        </form>
    </div>
</body>
</html>