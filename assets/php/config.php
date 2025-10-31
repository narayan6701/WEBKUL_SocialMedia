<?php
session_start();
// MySQL connection settings
const DB_NAME = 'pictogram';
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '2001';

// Attempt to connect to MySQL server (without selecting a database) so
// we can create the database if it doesn't exist and initialize schema
$server_conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($server_conn->connect_error) {
	// stop early with an informative message if the server isn't reachable
	die('MySQL connection failed: ' . $server_conn->connect_error);
}

// Create the database if it does not exist
$dbNameEsc = DB_NAME;
$createDbSql = "CREATE DATABASE IF NOT EXISTS `$dbNameEsc` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (!$server_conn->query($createDbSql)) {
	die('Failed to create database: ' . $server_conn->error);
}

// Select the database we just ensured exists
if (!$server_conn->select_db($dbNameEsc)) {
	die('Failed to select database: ' . $server_conn->error);
}

// Ensure proper charset
$server_conn->set_charset('utf8mb4');

// Check how many tables exist in this database. If zero, create required tables.
$tbl_count_result = $server_conn->query("SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = '$dbNameEsc'");
$table_count = 0;
if ($tbl_count_result) {
	$row = $tbl_count_result->fetch_assoc();
	$table_count = (int) $row['cnt'];
}

if ($table_count === 0) {
	// Create `users` table (inferred schema from project's usage)
		$create_users_sql = "CREATE TABLE IF NOT EXISTS `users` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`first_name` VARCHAR(100) NOT NULL,
			`last_name` VARCHAR(100) NOT NULL,
			`gender` TINYINT NOT NULL DEFAULT 1,
			`dob` DATE NOT NULL,
			`email` VARCHAR(255) NOT NULL UNIQUE,
			`username` VARCHAR(100) NOT NULL UNIQUE,
			`password` VARCHAR(255) NOT NULL,
			`profile_pic` VARCHAR(255) NOT NULL DEFAULT 'default_profile.jpg',
			`ac_status` TINYINT NOT NULL DEFAULT 0,
			`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

	if (!$server_conn->query($create_users_sql)) {
		die('Failed to create users table: ' . $server_conn->error);
	}

	// Create `posts` table (inferred schema from project's usage)
	$create_posts_sql = "CREATE TABLE IF NOT EXISTS `posts` (
	  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `user_id` INT UNSIGNED NOT NULL,
	  `post_text` TEXT,
	  `post_img` VARCHAR(255) DEFAULT NULL,
	  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

	if (!$server_conn->query($create_posts_sql)) {
		die('Failed to create posts table: ' . $server_conn->error);
	}
}

// Close this helper connection. The normal project code will open its own
// connection to the database (see `assets/php/functions.php`).
$server_conn->close();