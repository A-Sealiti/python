-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 18 mrt 2025 om 09:50
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `product_reviews`
--
CREATE DATABASE IF NOT EXISTS `product_reviews` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `product_reviews`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin@gmail.com', '$2y$10$ErUqWT6xmTSGymJrIoCh1OzqhI62czlSVpP33V.mBeF/7rFFlZULm', '2025-03-18 08:25:26');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Computers', 'Desktop computers, laptops, and accessories', '2025-03-18 08:15:33'),
(2, 'Mobile', 'Smartphones, tablets, and mobile accessories', '2025-03-18 08:15:33'),
(3, 'Audio', 'Headphones, speakers, and audio equipment', '2025-03-18 08:15:33'),
(4, 'Gaming', 'Gaming peripherals and accessories', '2025-03-18 08:15:33'),
(5, 'Storage', 'Hard drives, SSDs, and storage solutions', '2025-03-18 08:15:33'),
(6, 'Accessories', 'General computer and tech accessories', '2025-03-18 08:15:33');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `created_at`, `category_id`) VALUES
(1, 'Smartphone X', 'Latest smartphone with amazing features', 699.99, 'images/phone.jpg', '2025-03-18 07:52:08', 2),
(2, 'Laptop Pro', 'Professional laptop for all your needs', 1299.99, 'images/laptop.jpg', '2025-03-18 07:52:08', 1),
(3, 'Wireless Earbuds', 'High-quality wireless earbuds with noise cancellation', 149.99, 'images/earbuds.jpg', '2025-03-18 07:52:08', 3),
(4, 'Gaming Mouse', 'High-precision gaming mouse with RGB lighting and 16000 DPI sensor', 59.99, 'images/gaming-mouse.jpg', '2025-03-18 08:04:53', 4),
(5, '4K Monitor', '32-inch 4K Ultra HD Monitor with HDR support and 144Hz refresh rate', 499.99, 'images/monitor.jpg', '2025-03-18 08:04:53', 6),
(6, 'Mechanical Keyboard', 'RGB mechanical keyboard with Cherry MX Blue switches', 129.99, 'images/keyboard.jpg', '2025-03-18 08:04:53', 4),
(7, 'Smart Watch', 'Fitness tracking smartwatch with heart rate monitor and GPS', 199.99, 'images/smartwatch.jpg', '2025-03-18 08:04:53', 2),
(8, 'Bluetooth Speaker', 'Portable waterproof speaker with 20-hour battery life', 79.99, 'images/speaker.jpg', '2025-03-18 08:04:53', 3),
(9, 'Tablet Pro', '10.5-inch tablet with stylus support and 256GB storage', 649.99, 'images/tablet.jpg', '2025-03-18 08:04:53', 2),
(10, 'Wireless Mouse', 'Ergonomic wireless mouse with long battery life', 39.99, 'images/wireless-mouse.jpg', '2025-03-18 08:04:53', 6),
(11, 'USB-C Hub', '7-in-1 USB-C hub with HDMI, USB 3.0, and card reader', 45.99, 'images/usb-hub.jpg', '2025-03-18 08:04:53', 6),
(12, 'Gaming Headset', 'Surround sound gaming headset with noise-canceling mic', 89.99, 'images/headset.jpg', '2025-03-18 08:04:53', 3),
(13, 'Webcam HD', '1080p webcam with auto-focus and dual microphones', 69.99, 'images/webcam.jpg', '2025-03-18 08:04:53', 6),
(14, 'Power Bank', '20000mAh power bank with fast charging support', 49.99, 'images/powerbank.jpg', '2025-03-18 08:04:53', 6),
(15, 'External SSD', '1TB portable SSD with USB 3.2 support', 159.99, 'images/ssd.jpg', '2025-03-18 08:04:53', 5),
(16, 'Graphics Card', 'High-performance gaming graphics card with 8GB VRAM', 799.99, 'images/gpu.jpg', '2025-03-18 08:04:53', 1),
(17, 'RAM Kit', '32GB DDR4 RAM kit (2x16GB) with RGB lighting', 149.99, 'images/ram.jpg', '2025-03-18 08:04:53', 1),
(18, 'CPU Cooler', 'Liquid CPU cooler with RGB fans and LCD display', 129.80, 'https://www.googleadservices.com/pagead/aclk?sa=L&ai=DChcSEwjDi7CAm5OMAxVZtWgJHSDgHHkYABARGgJ3Zg&co=1&gclid=EAIaIQobChMIw4uwgJuTjAMVWbVoCR0g4Bx5EAQYAyABEgKsivD_BwE&ohost=www.google.com&cid=CAASJORoVWF2FKUwFiICAZ904p0w-jh5u1ddqmmFCSHklFc4QVRVQQ&sig=AOD64_0', '2025-03-18 08:04:53', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_name`, `rating`, `comment`, `created_at`) VALUES
(1, 3, 'Mike', 3, 'dfsd', '2025-03-18 07:56:49');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexen voor tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexen voor tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Beperkingen voor tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
