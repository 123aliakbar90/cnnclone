<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

echo "<h1>Database Setup Script</h1>";

try {
    // Read the SQL file
    $sql_file = 'database.sql';
    if (!file_exists($sql_file)) {
        die("Error: database.sql file not found in the same directory.");
    }
    
    $sql = file_get_contents($sql_file);
    
    // Execute the SQL commands
    $pdo->exec($sql);
    
    // AUTO-FIX: Check if 'name' column exists in 'users' table because of the "Unknown column 'name'" error
    try {
        $check = $pdo->query("SHOW COLUMNS FROM users LIKE 'name'");
        if ($check->rowCount() == 0) {
            $pdo->exec("ALTER TABLE users ADD COLUMN name VARCHAR(100) NOT NULL AFTER id");
            echo "<p style='color: blue;'>Fixed: Added missing 'name' column to users table.</p>";
        }
    } catch (Exception $ex) {
        echo "<p>Warning: Could not check/add specific columns: " . htmlspecialchars($ex->getMessage()) . "</p>";
    }
    
    echo "<h2 style='color: green;'>Database imported successfully!</h2>";
    echo "<p>The tables 'users', 'categories', and 'news' should now exist.</p>";
    echo "<p><a href='index.php'>Go to Home Page</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Database Error:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Error:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
