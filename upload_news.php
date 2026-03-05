<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Fetch Categories for Dropdown
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $content = trim($_POST['content']);
    
    // File Upload Handling
    $target_dir = "uploads/";
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Basic Validation
    if (empty($title) || empty($content) || empty($category_id)) {
        $error = "All fields are required.";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = "Please upload a valid image.";
    } else {
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = "Only JPG, JPEG, PNG, GIF, & WEBP files are allowed.";
        } else {
            // Generate unique filename
            $new_filename = uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                
                // Insert into Database
                // Note: 'author' is stored as the logged-in user's name
                $author = $_SESSION['user_name'];
                
                $stmt = $pdo->prepare("INSERT INTO news (title, image, content, category_id, author) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$title, $target_file, $content, $category_id, $author])) {
                    $success = "News article published successfully!";
                } else {
                    $error = "Database error. Please try again.";
                }
            } else {
                $error = "Failed to upload image.";
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
    <title>Upload News - CNN Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Helvetica+Neue:wght@400;500;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="top-bar">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="logout.php">Log Out</a>
        </div>
        <div class="main-nav">
            <div class="menu-toggle">☰</div>
            <a href="index.php" class="logo">CNN</a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="upload_news.php" class="active">Upload News</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="auth-container" style="max-width: 800px;">
            <h2 class="auth-title">Upload News Article</h2>
            
            <?php if ($error): ?>
                <div class="flash-message flash-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="flash-message flash-success">
                    <?php echo htmlspecialchars($success); ?> 
                    <a href="index.php">View Homepage</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="upload_news.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Article Title</label>
                    <input type="text" name="title" class="form-control" required placeholder="Enter a catchy headline">
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select a Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Cover Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                    <small style="color: #666;">Recommended size: 1200x675px (16:9 aspect ratio)</small>
                </div>

                <div class="form-group">
                    <label>Article Content</label>
                    <textarea name="content" class="form-control" rows="10" required placeholder="Write your full article here..."></textarea>
                </div>

                <button type="submit" class="btn">Publish Article</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h4>CNN Clone</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="upload_news.php">Upload News</a></li>
                </ul>
            </div>
        </div>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>
