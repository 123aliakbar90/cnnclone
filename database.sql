-- Database: rsk36_rsk36_1


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT IGNORE INTO `categories` (`id`, `name`) VALUES
(1, 'World'),
(2, 'Politics'),
(3, 'Business'),
(4, 'Tech'),
(5, 'Health'),
(6, 'Entertainment'),
(7, 'Style'),
(8, 'Travel'),
(9, 'Sports');

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `author` varchar(100) DEFAULT 'CNN Staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news`
--

INSERT IGNORE INTO `news` (`title`, `image`, `content`, `category_id`, `author`, `created_at`) VALUES
('Global Summit Reaches Historic Agreement on Climate Action', 'https://images.unsplash.com/photo-1621274790572-7c3330a0b009?w=800&q=80', 'In a landmark decision, world leaders have agreed to aggressive new targets for carbon reduction. The summit, held in Geneva, concluded with a unanimous vote to accelerate the transition to renewable energy. Experts say this could be the turning point for the planet''s future.', 1, 'Sarah Jenkins', NOW()),
('Tech Giant Unveils Revolutionary Quantum Processor', 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=80', 'The new processor promises to solve problems in seconds that would take traditional supercomputers thousands of years. This breakthrough could revolutionize medicine, cryptography, and materials science.', 4, 'David Chen', NOW()),
('Markets Rally as Inflation Shows Signs of Cooling', 'https://images.unsplash.com/photo-1611974765270-ca1258822981?w=800&q=80', 'Major indices hit record highs today as the latest consumer price index report came in lower than expected. Analysts suggest the central bank may pause interest rate hikes in the coming months.', 3, 'Amanda Williams', NOW()),
('New Health Guidelines for Daily Exercise Released', 'https://images.unsplash.com/photo-1538805060504-d141e4322bbb?w=800&q=80', 'Health officials have updated their recommendations for physical activity, emphasizing the importance of strength training alongside cardio. The new guidelines aim to combat rising rates of sedentary lifestyle diseases.', 5, 'Dr. Robert Smith', NOW()),
('Election Update: Early Polls Show Tight Race', 'https://images.unsplash.com/photo-1540910419868-4749459ca6c8?w=800&q=80', 'With just weeks to go until election day, the latest polling data suggests a dead heat between the two major candidates. Both campaigns are ramping up efforts in key swing states.', 2, 'Jessica Brown', NOW()),
('Award-Winning Actor Announces Surprise Retirement', 'https://images.unsplash.com/photo-1493612276216-9c59019558f7?w=800&q=80', 'After a career spanning four decades, the beloved star says he is stepping back from the limelight to focus on family and philanthropy. Tributes are pouring in from across the industry.', 6, 'Michael O''Connor', NOW()),
('SpaceX Launches Next Generation Satellites', 'https://images.unsplash.com/photo-1516849841032-87cbac4d88f7?w=800&q=80', 'The successful launch marks another milestone in the company''s mission to provide global internet coverage. The reusable rocket landed safely on a drone ship in the Atlantic.', 4, 'Tech Desk', NOW()),
('The Future of Electric Vehicles: What to Expect', 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=800&q=80', 'As major automakers commit to all-electric lineups, we look at the infrastructure and battery technology hurdles that remain. Is the grid ready for millions of EVs?', 3, 'Auto Weekly', NOW()),
('Unexpected Discovery in the Amazon Rainforest', 'https://images.unsplash.com/photo-1552674605-469523170d9e?w=800&q=80', 'Scientists have identified a new species of primate deep in the Amazon. The finding highlights the incredible biodiversity of the region and the urgent need for conservation.', 1, 'Nature Watch', NOW()),
('Championship Finals set for Next Weekend', 'https://images.unsplash.com/photo-1521412644187-c49fa049e84d?w=800&q=80', 'The two top teams in the league will face off in what is expected to be a historic match. Tickets sold out in minutes as fans clamor to see the showdown.', 9, 'Sports Center', NOW());
COMMIT;
