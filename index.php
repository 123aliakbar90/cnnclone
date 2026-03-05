<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

// Fetch Categories for Nav
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll();

// Fetch Latest News (Breaking)
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 1");
$breaking = $stmt->fetch();

// Fetch Featured Story (Latest excluding breaking)
$stmt = $pdo->query("SELECT news.*, categories.name as category_name FROM news JOIN categories ON news.category_id = categories.id ORDER BY created_at DESC LIMIT 1 OFFSET 1");
$featured = $stmt->fetch();

// Fetch Top Stories (Next 4)
$stmt = $pdo->query("SELECT news.*, categories.name as category_name FROM news JOIN categories ON news.category_id = categories.id ORDER BY created_at DESC LIMIT 4 OFFSET 2");
$top_stories = $stmt->fetchAll();

// Fetch Category Blocks (e.g., specific categories)
// Helper function to get news by category
function getNewsByCategory($pdo, $cat_id, $limit = 4) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE category_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1, $cat_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

$world_news = getNewsByCategory($pdo, 1); // World
$tech_news = getNewsByCategory($pdo, 4);  // Tech
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN Clone - Breaking News, Latest News and Videos</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Helvetica+Neue:wght@400;500;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Top Bar -->
        <div class="top-bar">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php">Log Out</a>
            <?php else: ?>
                <a href="login.php">Log In</a>
                <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </div>

        <!-- Main Nav -->
        <div class="main-nav">
            <div class="menu-toggle">☰</div>
            <a href="index.php" class="logo">CNN</a>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="upload_news.php" style="color: var(--cnn-red);">+ Upload News</a>
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
        <!-- Breaking News Banner -->
        <?php if ($breaking): ?>
        <div class="breaking-news">
            <span class="breaking-label">Breaking News</span>
            <a href="article.php?id=<?php echo $breaking['id']; ?>">
                <?php echo htmlspecialchars($breaking['title']); ?>
            </a>
        </div>
        <?php endif; ?>

        <!-- Main Grid -->
        <div class="news-grid">
            <!-- Featured Article -->
            <?php if ($featured): ?>
            <div class="featured-article">
                <div class="featured-img-container">
                    <a href="article.php?id=<?php echo $featured['id']; ?>">
                        <img src="<?php echo htmlspecialchars($featured['image']); ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>">
                    </a>
                </div>
                <h2 class="featured-title">
                    <a href="article.php?id=<?php echo $featured['id']; ?>">
                        <?php echo htmlspecialchars($featured['title']); ?>
                    </a>
                </h2>
                <p class="featured-excerpt">
                    <?php echo substr(htmlspecialchars($featured['content']), 0, 150) . '...'; ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Top Stories Sidebar -->
            <div class="top-stories">
                <h3 class="section-header">Top Stories</h3>
                <?php foreach ($top_stories as $story): ?>
                <div class="story-card-small">
                    <a href="article.php?id=<?php echo $story['id']; ?>">
                        <img src="<?php echo htmlspecialchars($story['image']); ?>" alt="News">
                    </a>
                    <h3>
                        <a href="article.php?id=<?php echo $story['id']; ?>">
                            <?php echo htmlspecialchars($story['title']); ?>
                        </a>
                    </h3>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Category Sections -->
        <?php if (!empty($world_news)): ?>
        <div class="category-section">
            <div class="category-header">
                <h2 class="cat-title">World</h2>
                <a href="categories.php?id=1" class="cat-see-all">See All World ></a>
            </div>
            <div class="cat-grid">
                <?php foreach ($world_news as $item): ?>
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
                        <span><?php echo date('M d', strtotime($item['created_at'])); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($tech_news)): ?>
        <div class="category-section">
            <div class="category-header">
                <h2 class="cat-title">Tech</h2>
                <a href="categories.php?id=4" class="cat-see-all">See All Tech ></a>
            </div>
            <div class="cat-grid">
                <?php foreach ($tech_news as $item): ?>
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
                        <span><?php echo date('M d', strtotime($item['created_at'])); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h4>World</h4>
                <ul>
                    <li><a href="#">Africa</a></li>
                    <li><a href="#">Americas</a></li>
                    <li><a href="#">Asia</a></li>
                    <li><a href="#">Europe</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>US Politics</h4>
                <ul>
                    <li><a href="#">Congress</a></li>
                    <li><a href="#">Supreme Court</a></li>
                    <li><a href="#">Election 2024</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Business</h4>
                <ul>
                    <li><a href="#">Markets</a></li>
                    <li><a href="#">Tech</a></li>
                    <li><a href="#">Media</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Follow CNN</h4>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
        <div style="text-align: center; margin-top: 2rem; border-top: 1px solid #333; padding-top: 1rem; color: #666; font-size: 0.8rem;">
            &copy; 2024 CNN Clone. All rights reserved. Dummy project for demonstration.
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
