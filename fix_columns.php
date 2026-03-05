<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

echo "<h1>Fixing Database...</h1>";

try {
    // Force adding the name column. If it exists, it will throw an error which we catch.
    // simpler than checking first in some cases where permissions for 'SHOW COLUMNS' might be weird (unlikely but possible)
    
    $sql = "ALTER TABLE users ADD COLUMN name VARCHAR(100) NOT NULL AFTER id";
    $pdo->exec($sql);
    echo "<h2 style='color: green;'>Success: Added 'name' column to users table!</h2>";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
         echo "<h2 style='color: blue;'>Column 'name' already exists. You are good to go.</h2>";
    } else {
        echo "<h2 style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</h2>";
    }
}

echo "<br><a href='signup.php'>Go back to Sign Up</a>";
?>
