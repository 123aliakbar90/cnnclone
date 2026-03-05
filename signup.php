<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already registered.";
        } else {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            
            try {
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$name, $email, $hashed_password]);
            } catch (PDOException $e) {
                // AUTO-FIX: If 'name' column is missing, add it and retry
                if (strpos($e->getMessage(), "Unknown column 'name'") !== false) {
                    $pdo->exec("ALTER TABLE users ADD COLUMN name VARCHAR(100) NOT NULL AFTER id");
                    // Retry insert
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute([$name, $email, $hashed_password]);
                } elseif (strpos($e->getMessage(), "Field 'username' doesn't have a default value") !== false) {
                    // AUTO-FIX: Make username nullable since we aren't using it yet
                    $pdo->exec("ALTER TABLE users MODIFY COLUMN username VARCHAR(100) NULL");
                    // Retry insert
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute([$name, $email, $hashed_password]);
                } else {
                    throw $e; // Re-throw other errors
                }
            }

            if ($result) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CNN Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Helvetica+Neue:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="main-nav">
            <a href="index.php" class="logo">CNN</a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="login.php" class="active">Login</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="auth-container">
            <h2 class="auth-title">Create Account</h2>
            
            <?php if ($error): ?>
                <div class="flash-message flash-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="flash-message flash-success">
                    <?php echo htmlspecialchars($success); ?> 
                    <a href="login.php">Login here</a>.
                </div>
            <?php endif; ?>

            <form method="POST" action="signup.php">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn">Sign Up</button>
            </form>
            
            <div class="auth-links">
                Already have an account? <a href="login.php">Log In</a>
            </div>
        </div>
    </div>
</body>
</html>
