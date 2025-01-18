-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 06:29 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `tasks` (
    `id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `priority` enum('low', 'medium', 'high') DEFAULT 'low',
    `is_completed` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
ALTER TABLE `tasks`
ADD PRIMARY KEY (`id`);
ALTER TABLE `tasks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;