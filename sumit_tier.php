<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ign = htmlspecialchars($_POST['ign']);
    $tier = htmlspecialchars($_POST['tier']);
    $kit = htmlspecialchars($_POST['kit']);
    
    // Validate input
    if (empty($ign) || empty($tier) || empty($kit)) {
        $error = "All fields are required!";
    } else {
        // Handle file upload
        $skinPath = 'assets/default_skin.png';
        if (isset($_FILES['skin']) && $_FILES['skin']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = pathinfo($_FILES['skin']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $skinPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['skin']['tmp_name'], $skinPath)) {
                // File uploaded successfully
            } else {
                $error = "Failed to upload skin image.";
            }
        }

        // Calculate ELO
        $elo = $tierPoints[$tier] ?? 0;

        // Store submission in database
        try {
            $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            $stmt = $pdo->prepare("INSERT INTO tier_submissions (ign, tier, kit, elo, skin_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$ign, $tier, $kit, $elo, $skinPath]);
            $success = "Submission received! Admin will review it shortly.";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Tier</title>
    <!-- Use same styles as other pages -->
</head>
<body>
    <header>
        <div class="logo">PojavLauncher TL</div>
        <nav>
            <ul>
                <li><a href="guides.html">Guides</a></li>
                <li><a href="rankings.html">Rankings</a></li>
                <li><a href="community.html">Community</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Submit Your Tier Information</h2>
            
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Minecraft IGN:</label>
                    <input type="text" name="ign" required>
                </div>
                
                <div class="form-group">
                    <label>Tier:</label>
                    <select name="tier" required>
                        <option value="ht1">HT1</option>
                        <option value="lt1">LT1</option>
                        <option value="ht2">HT2</option>
                        <option value="lt2">LT2</option>
                        <option value="ht3">HT3</option>
                        <option value="lt3">LT3</option>
                        <option value="ht4">HT4</option>
                        <option value="lt4">LT4</option>
                        <option value="ht5">HT5</option>
                        <option value="lt5">LT5</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Kit:</label>
                    <select name="kit" required>
                        <option value="axe_shield">Axe and shield</option>
                        <option value="nethpot">Nethpot</option>
                        <option value="uhc">Uhc</option>
                        <option value="cpvp">Cpvp</option>
                        <option value="cart_pvp">Cart pvp</option>
                        <option value="sword">Sword</option>
                        <option value="dia_pot_pvp">Dia pot pvp</option>
                        <option value="crystal">Crystal</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Skin Image (optional):</label>
                    <input type="file" name="skin" accept="image/*">
                </div>
                
                <button type="submit">Submit</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 PojavLauncher TL. All rights reserved.</p>
    </footer>
</body>
</html>