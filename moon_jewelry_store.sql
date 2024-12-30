-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2024 at 05:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moon_jewelry_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `active`, `details`) VALUES
(1, 'Pendant', 1, 'A pendant is a piece of jewelry that hangs from a chain, usually worn around the neck. It can be a simple charm or a more elaborate design, often featuring gemstones or intricate detailing.'),
(2, 'Earring', 1, 'Earrings are accessories worn on the ears. They come in various styles, including studs, hoops, and dangling designs, and can be adorned with gemstones, pearls, or intricate metalwork.'),
(3, 'Bracelet', 1, 'A bracelet is a piece of jewelry worn around the wrist. It can be a simple band or a more decorative piece with charms, gemstones, or intricate designs.'),
(4, 'Ring', 1, 'A ring is a circular band worn on the finger. It can be plain or adorned with gemstones, intricate designs, or engravings, and is often used to signify personal milestones or style.');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 11, 1, 225.00),
(2, 2, 10, 1, 779.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `address_first_name` varchar(50) NOT NULL,
  `address_last_name` varchar(50) NOT NULL,
  `address_street` varchar(255) NOT NULL,
  `address_city` varchar(100) NOT NULL,
  `address_state` varchar(100) NOT NULL,
  `address_postal_code` varchar(20) NOT NULL,
  `address_country` varchar(100) NOT NULL,
  `address_mobile` varchar(20) DEFAULT NULL,
  `address_email` varchar(100) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `total_amount`, `address_first_name`, `address_last_name`, `address_street`, `address_city`, `address_state`, `address_postal_code`, `address_country`, `address_mobile`, `address_email`, `created_on`) VALUES
(1, '3338F454', 2, 225.00, 'Mital', 'Hirapara', 'king', 'waterloo', 'ON', 'N2J H0D', 'CANADA', '1234567890', 'mital@gmail.com', '2024-08-13 02:45:25'),
(2, 'A031C389', 2, 779.00, 'Mital', 'Hirapara', 'King', 'Waterloo', 'ON', 'J1H0H4', 'Canada', '123456789', 'mital@gmail.com', '2024-08-16 01:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `brief_description` varchar(255) DEFAULT NULL,
  `full_description` text NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image_file` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `brief_description`, `full_description`, `unit_price`, `category_id`, `image_file`, `active`, `created_on`) VALUES
(1, '18ct Yellow Gold Baroque Pearl Stud Earrings', 'These dazzling earrings feature Sterling Silver pave hoops with three 18K Yellow gold annulet accents.', '<ul><li>These exquisite stud earrings are crafted from 18ct yellow gold, offering a luxurious and timeless appeal. Each earring features a stunning baroque pearl, celebrated for its unique and organic shape that adds a touch of natural elegance to any look. The warm glow of the yellow gold perfectly complements the lustrous sheen of the pearls, creating a harmonious blend of classic and contemporary design.</li><li> The baroque pearls are carefully selected for their individuality, ensuring that each earring is a one-of-a-kind piece. The irregular shape of the pearls gives these earrings a distinctive character, making them stand out as a bold yet refined accessory. .</li><li> The 18ct yellow gold setting is polished to a high shine, enhancing the overall radiance of the earrings. Secure and comfortable to wear, these earrings are suitable for both everyday elegance and special occasions. Whether paired with a simple outfit or a more formal ensemble, these baroque pearl stud earrings are sure to make a sophisticated statement. .</li><li> Perfect as a gift or a personal indulgence, these earrings are a beautiful way to celebrate the timeless allure of pearls and the enduring luxury of gold.</li></ul>', 219.00, 2, '18ct_yellow_gold_baroque_pearl_stud_earrings.jpg', 1, '2024-08-13 02:03:58'),
(2, '9ct White Gold Diamond Open Hoop Earrings', '9ct White Gold elegant open hoop earrings 0.25ct total weight Diamond Open Hoop Earrings offering a modern touch.', '<ul>\r\n    <li>These elegant open hoop earrings are expertly crafted from 9ct white gold, offering a modern and refined touch to your jewelry collection.</li>\r\n    <li>The sleek white gold setting provides a striking backdrop for the 0.25 carat total weight of sparkling diamonds, which are meticulously set along the curve of each hoop.</li>\r\n    <li>The diamonds are carefully selected for their brilliance and clarity, adding a dazzling shimmer that catches the light with every movement.</li>\r\n    <li>The open hoop design offers a contemporary twist on the classic hoop earring, making these earrings a versatile choice for any occasion.</li>\r\n    <li>The clean lines and minimalistic aesthetic of the white gold enhance the radiant beauty of the diamonds, creating a sophisticated and understated elegance.</li>\r\n    <li>These earrings are designed for comfort and ease of wear, featuring a secure fastening that ensures they stay in place throughout the day or night.</li>\r\n    <li>Whether worn alone for a chic, minimalist look or paired with other pieces for added sparkle, these earrings are a timeless addition to any jewelry collection.</li>\r\n    <li>Perfect for both everyday wear and special occasions, these 9ct white gold diamond open hoop earrings make a thoughtful gift or a luxurious treat for yourself.</li>\r\n    <li>Their delicate design and sparkling diamonds make them an exquisite accessory that complements any style with effortless grace.</li>\r\n</ul>', 325.00, 2, '9ct_white_gold_0.25cttw_diamond_open_hoop_earrings.jpg', 1, '2024-08-13 02:13:16'),
(3, '9ct Yellow Gold 13mm Hoop Earrings', 'A versatile addition to any accessory collection, these striking 9ct Gold Hoop Earrings will instantly inject a generous.', '<ul>\r\n    <li>These classic 9ct yellow gold hoop earrings are a timeless accessory that effortlessly complements any look.</li>\r\n    <li>Measuring 13mm in diameter, these hoops are the perfect size for everyday wear, offering a subtle yet elegant touch of gold that adds warmth and sophistication to your style.</li>\r\n    <li>Crafted from high-quality 9ct yellow gold, these earrings boast a smooth, polished finish that enhances their radiant shine.</li>\r\n    <li>The simplicity of the design ensures versatility, making these hoops a go-to accessory for both casual and formal occasions.</li>\r\n    <li>Their compact size makes them comfortable to wear all day, whether you\'re at the office, running errands, or enjoying a night out.</li>\r\n    <li>The hoops feature a secure latch-back closure, providing peace of mind while wearing them.</li>\r\n    <li>Their lightweight construction ensures that they sit comfortably on the ear without causing any discomfort, making them an ideal choice for those who prefer subtle, understated jewelry.</li>\r\n    <li>These 9ct yellow gold 13mm hoop earrings are a must-have for any jewelry collection, offering a blend of elegance, versatility, and enduring style.</li>\r\n    <li>Perfect as a thoughtful gift or a personal treat, these hoops are sure to become a cherished favorite that you\'ll reach for time and time again.</li>\r\n</ul>', 150.00, 2, '9ct_yellow_gold_13mm_hoop_earrings.jpg', 1, '2024-08-13 02:14:45'),
(4, 'Sterling Silver Offspring Open Earhoops', 'These dazzling earrings feature Sterling Silver pave hoops with three 18K Yellow gold annulet accents.', '<ul>\r\n    <li>These Sterling Silver Offspring Open Earhoops are a beautifully designed piece of jewelry that embodies the perfect blend of elegance and modernity.</li>\r\n    <li>Crafted from high-quality sterling silver, these earhoops feature a unique open design that adds a contemporary twist to the traditional hoop earring style.</li>\r\n    <li>The fluid, organic shape of the hoops is inspired by the natural form of an egg, symbolizing the bond between parent and child—a perfect representation of love, connection, and heritage.</li>\r\n    <li>The smooth, polished surface of the sterling silver enhances the sleek, minimalist aesthetic of the earrings, reflecting light beautifully and adding a subtle shine that complements any outfit.</li>\r\n    <li>The open design of the earhoops gives them a lightweight and airy feel, making them comfortable to wear throughout the day.</li>\r\n</ul>', 89.00, 2, 'sterling_silver_offspring_open_earhoops.jpg', 1, '2024-08-13 02:15:44'),
(5, '18ct Yellow Gold 0.10cttw Baroque Pearl Pendant', 'Set out to impress and delight with these adorable 18 carat Gold 0. 20 total carat weight.', '<ul>\r\n    <li>This stunning 18ct yellow gold pendant is a true masterpiece, combining the timeless elegance of diamonds and the organic beauty of a baroque pearl.</li>\r\n    <li>The pendant features a lustrous baroque pearl, celebrated for its unique and irregular shape, which makes each piece one-of-a-kind.</li>\r\n    <li>Suspended beneath the pearl is a delicate 0.10 carat total weight diamond, adding a touch of brilliance that enhances the overall luxury of the piece.</li>\r\n    <li>The 18ct yellow gold setting perfectly complements the creamy hue of the pearl and the sparkle of the diamond, creating a harmonious blend of classic and modern design.</li>\r\n    <li>The warm tones of the gold contrast beautifully with the cool, shimmering surface of the pearl, making this pendant an exquisite accessory that stands out with understated sophistication.</li>\r\n</ul>', 865.00, 1, '18ct_yellow_gold_0.10cttw_diamond_baroque_pearl_pendant.jpg', 1, '2024-08-13 02:16:38'),
(6, '18ct Yellow Gold Open Heart Floating Pendant', 'Beautifully crafted from 18ct yellow gold, this necklace is a stylish symbol of love.', '<ul>\r\n    <li>This 18ct Yellow Gold Open Heart Floating Pendant is a stunning and elegant piece of jewelry that radiates timeless charm.</li>\r\n    <li>Crafted from high-quality 18-carat yellow gold, this pendant features a beautifully designed open heart shape that symbolizes love, affection, and warmth.</li>\r\n    <li>The heart\'s hollow center adds a delicate touch, allowing the pendant to have a floating appearance that is both modern and sophisticated.</li>\r\n    <li>The smooth and polished surface of the heart pendant reflects light beautifully, giving it a radiant glow that complements any skin tone.</li>\r\n    <li>The pendant hangs gracefully from a matching yellow gold chain, which enhances its overall elegance and ensures it sits perfectly around the neck.</li>\r\n</ul>', 311.00, 1, '18ct_yellow_gold_open_heart_floating_pendant.jpg', 1, '2024-08-13 02:19:47'),
(7, '9ct Yellow Gold Infinity Pendant', 'Set out to impress and delight with these adorable 9 carat Gold Diamond Halo Stud Earrings.', '<ul>\r\n    <li>This 9ct Yellow Gold Infinity Pendant is a beautifully crafted symbol of endless love, commitment, and timeless elegance.</li>\r\n    <li>Made from high-quality 9-carat yellow gold, this pendant features the iconic infinity symbol, representing eternal bonds and unending connections.</li>\r\n    <li>The smooth, polished curves of the infinity design give the pendant a sleek and modern look, while the warm glow of yellow gold adds a touch of luxury and sophistication.</li>\r\n    <li>The pendant is delicately suspended from a matching yellow gold chain, allowing it to sit gracefully on the neckline.</li>\r\n    <li>Its versatile and understated design makes it perfect for both everyday wear and special occasions.</li>\r\n    <li>Whether worn alone for a minimalist look or paired with other pieces for a more layered style, this pendant is a beautiful addition to any jewelry collection.</li>\r\n</ul>', 315.00, 1, '9ct_yellow_gold_infinity_pendant.jpg', 1, '2024-08-13 02:20:31'),
(8, '9ct White Gold Diamond Pearl Pendant', '9ct White Gold Diamond and 6.5-7mm Fresh Water Pearl Pendant', '<ul>\r\n    <li>This exquisite 9ct White Gold Pendant combines the lustrous beauty of a 6.5-7mm Fresh Water Pearl with the sparkling brilliance of diamonds.</li>\r\n    <li>Crafted from high-quality 9-carat white gold, the pendant features a single, perfectly round Fresh Water Pearl that gleams with a soft, iridescent glow.</li>\r\n    <li>The pearl is gracefully suspended within a delicate setting that showcases its natural elegance.</li>\r\n    <li>Encircling the pearl is a halo of dazzling diamonds, which add a touch of sophistication and sparkle to the piece.</li>\r\n    <li>The diamonds are expertly set to maximize their brilliance, catching the light from every angle and creating a captivating contrast against the pearl’s creamy sheen.</li>\r\n</ul>', 299.00, 1, '9ct_white_gold_diamond_fresh_water_pearl_pendant.jpg', 1, '2024-08-13 02:21:43'),
(9, '18ct Yellow Gold Round Halo Engagement Ring', '18 carat yellow gold, 0.40 carat total weight diamond round halo ring. Sparkling 0.40 carat total weight diamonds', '<ul>\r\n    <li>This stunning 18ct Yellow Gold 0.40ct Round Halo Engagement Ring is a perfect blend of classic elegance and modern sophistication.</li>\r\n    <li>At its center, the ring features a brilliant 0.40-carat round diamond, known for its exceptional sparkle and clarity.</li>\r\n    <li>The diamond is expertly cut to enhance its brilliance and is set within a delicate halo of smaller diamonds that frame it beautifully, creating a captivating, radiant effect.</li>\r\n    <li>The ring is crafted from high-quality 18-carat yellow gold, which adds a warm, luxurious glow that complements the dazzling centerpiece.</li>\r\n    <li>The band is designed with a sleek and elegant profile, ensuring that the focus remains on the sparkling halo and central diamond.</li>\r\n    <li>The yellow gold setting provides a timeless contrast to the icy brilliance of the diamonds, making the ring a standout piece.</li>\r\n    <li>This engagement ring is a testament to exquisite craftsmanship and attention to detail, making it an ideal choice for celebrating a special commitment.</li>\r\n    <li>Its classic halo design and high-quality materials ensure that it will be a treasured symbol of love for years to come.</li>\r\n    <li>Whether given as a proposal ring or as a symbol of everlasting devotion, this 18ct Yellow Gold 0.40ct Round Halo Engagement Ring embodies elegance and timeless beauty.</li>\r\n</ul>', 549.00, 4, '18ct_yellow_gold_0.40ct_round_halo_engagement_ring.jpg', 1, '2024-08-13 02:22:45'),
(10, '18ct Yellow Gold Three Stone Engagement Ring', 'It consists of brilliant-cut diamonds totaling 1.00 carats, elegantly positioned in the shape of a claw.', '<ul>\r\n    <li>This exquisite 18ct Yellow Gold 1.50cttw Three Stone Engagement Ring is a breathtaking symbol of enduring love and commitment.</li>\r\n    <li>Crafted from premium 18-carat yellow gold, the ring features three stunning diamonds, each chosen for their exceptional quality and brilliance.</li>\r\n    <li>The total carat weight of 1.50 carats ensures a remarkable presence on the finger, with each diamond expertly cut to maximize its sparkle and fire.</li>\r\n    <li>The three diamonds are elegantly set in a row, representing the past, present, and future of your relationship.</li>\r\n    <li>The central diamond, being the largest, captures the eye with its captivating brilliance, while the two smaller stones on either side add complementary sparkle and balance.</li>\r\n    <li>The prong setting allows for maximum light exposure, enhancing the diamonds\' natural beauty.</li>\r\n    <li>The ring\'s yellow gold band is sleek and polished, providing a warm and luxurious backdrop that highlights the dazzling diamonds.</li>\r\n    <li>Its classic design ensures that it remains timeless and sophisticated, making it an ideal choice for marking a significant moment or celebrating a lifelong commitment.</li>\r\n</ul>', 779.00, 4, '18ct_yellow_gold_1.50cttw_three_stone_engagement_ring.jpg', 1, '2024-08-13 02:23:44'),
(11, '9ct Yellow Gold Rope Chain Bracelet', 'Whether you’re in the office or the bar, this striking Yellow Gold Hollow Rope Bracelet is sure to make a lasting impact.', '<ul>\r\n    <li>The 9ct Yellow Gold Rope Chain Bracelet is a sophisticated and timeless piece of jewelry that exudes elegance and style.</li>\r\n    <li>Crafted from high-quality 9-carat yellow gold, this bracelet features a distinctive rope chain design, where each link is intricately twisted to create a textured, helical pattern that catches the light beautifully.</li>\r\n    <li>The bracelet’s rope chain construction adds a touch of refinement and subtle movement, giving it a dynamic and eye-catching quality.</li>\r\n    <li>The warm, rich hue of the yellow gold complements a wide range of skin tones and outfits, making it a versatile accessory suitable for both everyday wear and special occasions.</li>\r\n    <li>The bracelet is finished with a secure and easy-to-use clasp, ensuring it stays comfortably in place while you go about your day.</li>\r\n    <li>Its classic design and durable craftsmanship make it an enduring piece that can be worn alone for a sleek look or layered with other bracelets for a more contemporary style.</li>\r\n</ul>', 225.00, 3, '9ct_yellow_gold_rope_chain_bracelet.jpg', 1, '2024-08-13 02:24:32'),
(12, 'Twist Rows Bracelet', 'Inspired by one of the oldest and most enduring styles of jewellery ever to have existed, this 18ct white gold bangle embraces classic torque silhouette.', '<ul>\r\n    <li>The Twist Rows Bracelet is a stunning piece of jewelry that showcases intricate craftsmanship and modern design.</li>\r\n    <li>Featuring a series of elegantly twisted rows, this bracelet creates a dynamic and eye-catching effect.</li>\r\n    <li>Each row is meticulously crafted to have a smooth, spiral twist, adding depth and texture to the piece.</li>\r\n    <li>The bracelet is available in various metals, including gold, silver, or a combination of both, depending on your preference.</li>\r\n    <li>The twist design reflects light beautifully, creating a shimmering effect that enhances its visual appeal.</li>\r\n    <li>Its versatile design allows it to be worn as a statement piece or layered with other bracelets for a more personalized look.</li>\r\n    <li>Finished with a secure clasp, the Twist Rows Bracelet ensures a comfortable fit and stays in place throughout the day.</li>\r\n    <li>Its timeless yet contemporary design makes it a perfect accessory for both casual and formal occasions.</li>\r\n</ul>', 719.00, 3, 'twist_rows_bracelet.jpg', 1, '2024-08-13 02:25:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email_address`, `password_hash`, `first_name`, `last_name`, `phone`, `role`, `active`, `created_on`) VALUES
(1, 'admin@gmail.com', '$2y$10$G4U7WBRb1PCJ6D8e1rXqJOfRc/5wJzAV3XclGk/Sn3k5GjTSncgua', NULL, NULL, NULL, 'admin', 1, '2024-08-13 00:00:14'),
(2, 'mital@gmail.com', '$2y$10$3z6nRNFN0gXwnTwtSGQID.khUyPhCKB2hILtJ.AnccY88iX26A0uq', NULL, NULL, NULL, 'user', 1, '2024-08-13 01:20:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
