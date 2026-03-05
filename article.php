<?php
session_start();
require_once 'db.php';

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Article Data
$stmt = $pdo->prepare("SELECT news.*, categories.name as category_name FROM news JOIN categories ON news.category_id = categories.id WHERE news.id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    header("Location: index.php");
    exit;
}

// Fetch Related News (Same Category)
$stmt = $pdo->prepare("SELECT * FROM news WHERE category_id = ? AND id != ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$article['category_id'], $article_id]);
$related_news = $stmt->fetchAll();

// Fetch Categories for Menu
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - CNN Clone</title>
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
                <?php foreach ($categories as $cat): ?>
                    <a href="categories.php?id=<?php echo $cat['id']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="article-header">
            <div class="article-category"><?php echo htmlspecialchars($article['category_name']); ?></div>
            <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
            <div class="article-meta">
                <span>By <strong><?php echo htmlspecialchars($article['author']); ?></strong>, CNN Clone</span>
                <span>Updated <?php echo date('h:i A T, D F j, Y', strtotime($article['created_at'])); ?></span>
            </div>
        </div>

        <div class="article-layout">
            <div class="article-content">
                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image">
                <div class="article-text">
                    <p style="font-weight: 700;">(CNN Clone) - <?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
                </div>
            </div>

            <div class="article-sidebar">
                <h3 class="section-header">More from <?php echo htmlspecialchars($article['category_name']); ?></h3>
                <?php foreach ($related_news as $item): ?>
                <div class="story-card-small">
                    <a href="article.php?id=<?php echo $item['id']; ?>">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="News">
                    </a>
                    <h3>
                        <a href="article.php?id=<?php echo $item['id']; ?>">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </a>
                    </h3>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
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
