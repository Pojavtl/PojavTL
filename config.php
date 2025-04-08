<?php
// config.php
session_start();

// kya dekha h be laude
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'pojav_tl');

echo password_hash("eeelaudejyadabolegatohterigandkaatdunga@#123", PASSWORD_BCRYPT);

// Tier points mapping
$tierPoints = [
    'ht1' => 80, 'lt1' => 60, 'ht2' => 45, 'lt2' => 35,
    'ht3' => 20, 'lt3' => 15, 'ht4' => 10, 'lt4' => 7,
    'ht5' => 3, 'lt5' => 1
];

// Tier colors
$tierColors = [
    'ht1' => '#FFD700', 'lt1' => '#EEE8AA',
    'ht2' => '#00FFFF', 'lt2' => '#E0FFFF',
    'ht3' => '#32CD32', 'lt3' => '#90EE90',
    'ht4' => '#FFA500', 'lt4' => '#FFDAB9',
    'ht5' => '#FF0000', 'lt5' => '#FF69B4'
];
?>
