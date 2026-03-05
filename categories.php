<?php
session_start();
require_once 'db.php';

$cat_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Current Category Info
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$cat_id]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: index.php");
    exit;
}

// Fetch News for this Category
$stmt = $pdo->prepare("SELECT * FROM news WHERE category_id = ? ORDER BY created_at DESC");
$stmt->execute([$cat_id]);
$news_list = $stmt->fetchAll();

// Fetch All Categories for Pills
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$all_categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> News - CNN Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Helvetica+Neue:wght@400;500;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="top-bar">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php">Log Out</a>
            <?php else: ?>
                <a href="login.php">Log In</a>
                <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </div>
        <div class="main-nav">
            <div class="menu-toggle">☰</div>
            <a href="index.php" class="logo">CNN</a>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="upload_news.php" style="color: var(--cnn-red);">+ Upload</a>
                <?php endif; ?>
                <?php foreach ($all_categories as $cat): ?>
                    <a href="categories.php?id=<?php echo $cat['id']; ?>" class="<?php echo ($cat['id'] == $cat_id) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Category Header -->
        <h1 class="cat-title" style="margin-bottom: 1rem; border-bottom: 4px solid var(--cnn-red); display: inline-block;"><?php echo htmlspecialchars($category['name']); ?></h1>
        
        <!-- Category Pills (Mobile Friendliness) -->
        <div class="category-pills">
            <?php foreach ($all_categories as $cat): ?>
                <a href="categories.php?id=<?php echo $cat['id']; ?>" class="cat-pill <?php echo ($cat['id'] == $cat_id) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- News Grid -->
        <?php if (count($news_list) > 0): ?>
            <div class="cat-grid">
                <?php foreach ($news_list as $item): ?>
                <div class="news-card">
                    <div class="news-img">
                        <a href="article.php?id=<?php echo $item['id']; ?>">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="News">
                        </a>
                    </div>
                    <h3>
                        <a href="article.php?id=<?php echo $item['id']; ?>">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </a>
                    </h3>
                    <div class="news-meta">
                        <span><?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
                        <span><?php echo htmlspecialchars($item['author']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No news found in this category.</p>
        <?php endif; ?>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h4>CNN Clone</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
